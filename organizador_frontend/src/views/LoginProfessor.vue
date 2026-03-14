<template>
  <div class="flex flex-col items-center justify-center h-screen bg-gradient-to-b from-[#ff9421] to-[#ffbe78]">
    <div class="absolute top-1 w-full flex justify-center">
      <img src="/logo-unifil.png" alt="UniFil" class="w-80 md:w-96 lg:w-[800px] mx-auto" />
    </div>

    <div class="bg-[#ffeccf] w-80 h-100 rounded-2xl shadow-md p-10 flex flex-col items-center relative transition-all duration-300">
      <p class="text-center tracking-[7px] text-[#545252] leading-relaxed mb-2 font-black">
        Login
      </p>

      <div class="relative flex flex-col items-center w-full space-y-1">
        <div class="w-full">
          <label class="block text-[#545252] font-medium mb-1 text-sm">
            Usuario
          </label>
          <div class="border-b border-[#545252] pb-1 flex items-center">
            <input 
              type="text" 
              placeholder="Insira a seu login aqui" 
              v-model="formData.login"
              class="w-full bg-transparent outline-none text-[#545252] placeholder-[#a0a0a0] text-base"
            />
            <img src="/pessoa.png" alt="Usuário" class="w-6 h-6 ml-2" />
          </div>
        </div>

        <div class="w-full">
          <label class="block text-[#545252] font-medium mb-1 text-sm">
            Senha
          </label>
          <div class="border-b border-[#545252] pb-1 flex items-center">
            <input 
              type="password" 
              placeholder="Insira a sua senha aqui" 
              v-model="formData.senhaHash"
              class="w-full bg-transparent outline-none text-[#545252] placeholder-[#a0a0a0] text-base"
            />
            <img src="/olhosenha.png" alt="Mostrar senha" class="w-6 h-6 ml-2" />
          </div>
        </div>
      </div>

      <button 
        @click="submitLogin"
        :disabled="loading"
        class="bg-[#ffac26] tracking-[7px] w-60 h-12 text-white font-semibold text-lg rounded-full py-2 px-10 shadow-md hover:shadow-lg transition-all mt-35 disabled:opacity-50"
      >
        {{ loading ? 'Entrando...' : 'Enviar' }}
      </button>

      <div v-if="mensagem.texto" 
           :class="[
             'w-full text-center py-2 px-4 rounded-lg mt-3 text-xs transition-all duration-300',
             mensagem.tipo === 'erro' ? 'bg-red-100 text-red-700 border border-red-300' : 
             mensagem.tipo === 'sucesso' ? 'bg-green-100 text-green-700 border border-green-300' : 
             'bg-blue-100 text-blue-700 border border-blue-300'
           ]">
        {{ mensagem.texto }}
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
      
      if (usuario.permissao === 2) {
        localStorage.setItem('usuario', JSON.stringify(usuario));
        mostrarMensagem('Login realizado com sucesso!', 'sucesso');
        setTimeout(() => {
          router.push('/PainelProfessoresHorarios');
        }, 1000);
      } else {
        mostrarMensagem('Acesso restrito para professores', 'erro');
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

<style scoped>
    .fade-enter-active,
    .fade-leave-active {
    transition: opacity 0.3s ease;
    }
    .fade-enter-from,
    .fade-leave-to {
      opacity: 0;
    }
</style>