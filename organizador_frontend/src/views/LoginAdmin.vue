<template>
  <div class="flex h-screen">
    <div class="hidden lg:flex lg:w-2/5 bg-gradient-to-br from-[#ff9421] to-[#c96a00] flex-col items-center justify-center p-12">
      <img src="/logo-unifil.png" alt="UniFil" class="w-full object-contain mb-10" />
      <h2 class="text-white text-2xl font-bold text-center mb-3">Organizador de Grade</h2>
      <p class="text-orange-100 text-center text-sm leading-relaxed max-w-xs">
        Gerencie seus horários acadêmicos de forma simples e eficiente
      </p>
    </div>

    <div class="flex-1 flex flex-col items-center justify-center bg-white px-8">
      <div class="w-full max-w-sm">
        <div class="lg:hidden mb-8 flex justify-center">
          <img src="/logo-unifil.png" alt="UniFil" class="w-44 object-contain" />
        </div>

        <h1 class="text-2xl font-bold text-stone-800 mb-1">Área Administrativa</h1>
        <p class="text-stone-500 text-sm mb-8">Acesse o painel de administração</p>

        <div class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Usuário</label>
            <input
              type="text"
              placeholder="Insira seu login"
              v-model="formData.login"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Senha</label>
            <input
              type="password"
              placeholder="Insira sua senha"
              v-model="formData.senhaHash"
              @keyup.enter="submitLogin"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
            />
          </div>
        </div>

        <button
          @click="submitLogin"
          :disabled="loading"
          class="mt-6 w-full bg-[#ff9421] hover:bg-[#e06800] text-white font-semibold rounded-lg py-3 transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ loading ? 'Entrando...' : 'Entrar' }}
        </button>

        <div
          v-if="mensagem.texto"
          :class="['mt-4 py-2.5 px-4 rounded-lg text-sm text-center transition-all duration-300',
            mensagem.tipo === 'erro' ? 'bg-red-50 text-red-600 border border-red-200' :
            mensagem.tipo === 'sucesso' ? 'bg-green-50 text-green-600 border border-green-200' :
            'bg-blue-50 text-blue-600 border border-blue-200']"
        >
          {{ mensagem.texto }}
        </div>

        <p class="mt-8 text-center text-sm text-stone-500">
          <span
            @click="$router.push('/')"
            class="text-[#ff9421] font-medium cursor-pointer hover:underline"
          >← Voltar ao início</span>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, inject } from "vue";
import { useRouter } from 'vue-router';

const router = useRouter();
const api = inject('api');
const loading = ref(false);

const mensagem = ref({
  texto: '',
  tipo: ''
});

const formData = reactive({
  login: '',
  senhaHash: ''
});

const mostrarMensagem = (texto, tipo = 'erro') => {
  mensagem.value = { texto, tipo };
  setTimeout(() => {
    if (mensagem.value.texto === texto) {
      mensagem.value = { texto: '', tipo: '' };
    }
  }, 5000);
};

const submitLogin = async () => {
  if (loading.value) return;

  mensagem.value = { texto: '', tipo: '' };

  if (!formData.login || !formData.senhaHash) {
    mostrarMensagem('Por favor, preencha todos os campos', 'erro');
    return;
  }

  loading.value = true;

  try {
    const response = await api.post('/api/usuarios/login', {
      login: formData.login,
      senhaHash: formData.senhaHash
    });

    if (response.status === 200) {
      const usuario = response.data.usuario;

      if (usuario.permissao === 1) {
        localStorage.setItem('usuario', JSON.stringify(usuario));
        mostrarMensagem('Login realizado com sucesso!', 'sucesso');
        setTimeout(() => {
          router.push('/PainelAdminHorarios');
        }, 1000);
      } else {
        mostrarMensagem('Acesso restrito para administradores', 'erro');
      }
    }
  } catch (error) {
    if (error.response?.status === 401) {
      mostrarMensagem('Usuário ou senha incorretos', 'erro');
    } else {
      mostrarMensagem('Erro ao fazer login. Tente novamente', 'erro');
    }
  } finally {
    loading.value = false;
  }
};
</script>
