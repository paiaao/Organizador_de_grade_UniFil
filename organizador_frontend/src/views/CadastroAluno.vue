<template>
  <div class="flex h-screen">
    <!-- Painel esquerdo: identidade UniFil -->
    <div class="hidden lg:flex lg:w-2/5 bg-gradient-to-br from-[#ff9421] to-[#c96a00] flex-col items-center justify-center p-12">
      <img src="/logo-unifil.png" alt="UniFil" class="w-56 object-contain mb-10" />
      <h2 class="text-white text-2xl font-bold text-center mb-3">Organizador de Grade</h2>
      <p class="text-orange-100 text-center text-sm leading-relaxed max-w-xs">
        Crie sua conta e gerencie seus horários acadêmicos
      </p>
    </div>

    <!-- Painel direito: formulário -->
    <div class="flex-1 flex flex-col items-center justify-center bg-white px-8 overflow-y-auto py-8">
      <div class="w-full max-w-sm">
        <!-- Logo mobile -->
        <div class="lg:hidden mb-6 flex justify-center">
          <img src="/logo-unifil.png" alt="UniFil" class="w-44 object-contain" />
        </div>

        <h1 class="text-2xl font-bold text-stone-800 mb-1">Criar conta</h1>
        <p class="text-stone-500 text-sm mb-6">Preencha os dados para se cadastrar</p>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Nome completo</label>
            <input
              type="text"
              placeholder="Insira seu nome"
              v-model="formData.nome"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Curso</label>
            <select
              v-model="formData.curso"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition bg-white"
            >
              <option value="">Selecione seu curso</option>
              <option value="Engenharia de Software">Engenharia de Software</option>
              <option value="Ciências da Computação">Ciências da Computação</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Turma</label>
            <select
              v-model="formData.turma_id"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition bg-white"
            >
              <option value="">Selecione sua turma</option>
              <option v-for="turma in turmas" :key="turma.id" :value="turma.id">
                {{ turma.turmaRepresentante }}
              </option>
            </select>
            <p v-if="carregandoTurmas" class="text-xs text-blue-500 mt-1">Carregando turmas...</p>
            <p v-if="erroTurmas" class="text-xs text-red-500 mt-1">{{ erroTurmas }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Matrícula</label>
            <input
              type="text"
              placeholder="Insira sua matrícula"
              v-model="formData.matricula"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Senha</label>
            <input
              type="password"
              placeholder="Mínimo 6 caracteres"
              v-model="formData.senhaHash"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Confirmar senha</label>
            <input
              type="password"
              placeholder="Repita sua senha"
              v-model="formData.senhaHash_confirmation"
              @keyup.enter="submitForm"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
            />
          </div>
        </div>

        <button
          @click="submitForm"
          :disabled="loading"
          class="mt-6 w-full bg-[#ff9421] hover:bg-[#e06800] text-white font-semibold rounded-lg py-3 transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ loading ? 'Cadastrando...' : 'Criar conta' }}
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

        <p class="mt-6 text-center text-sm text-stone-500">
          Já tem conta?
          <span
            @click="$router.push('/loginAluno')"
            class="text-[#ff9421] font-medium cursor-pointer hover:underline"
          >Fazer login</span>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, inject } from "vue";
import { useRouter } from 'vue-router';

const router = useRouter();
const loading = ref(false);
const carregandoTurmas = ref(false);
const erroTurmas = ref('');
const turmas = ref([]);

const mensagem = ref({
  texto: '',
  tipo: ''
});

const api = inject('api');

const formData = reactive({
  nome: '',
  curso: '',
  turma_id: '',
  matricula: '',
  senhaHash: '',
  senhaHash_confirmation: '',
  permissao: 3,
  entrada: new Date().toISOString().split('T')[0]
});

const mostrarMensagem = (texto, tipo = 'erro') => {
  mensagem.value = { texto, tipo };
  setTimeout(() => {
    if (mensagem.value.texto === texto) {
      mensagem.value = { texto: '', tipo: '' };
    }
  }, 5000);
};

const carregarTurmas = async () => {
  carregandoTurmas.value = true;
  erroTurmas.value = '';

  try {
    const response = await api.get('/api/turmas');

    if (response.data && Array.isArray(response.data)) {
      turmas.value = response.data;
    } else {
      erroTurmas.value = 'Resposta da API em formato inválido';
    }
  } catch (error) {
    if (error.response) {
      erroTurmas.value = 'Erro ao carregar turmas';
    } else if (error.request) {
      erroTurmas.value = 'Sem resposta do servidor';
    } else {
      erroTurmas.value = 'Erro ao carregar turmas';
    }
  } finally {
    carregandoTurmas.value = false;
  }
};

const submitForm = async () => {
  if (loading.value) return;

  mensagem.value = { texto: '', tipo: '' };

  if (!formData.nome || !formData.curso || !formData.matricula || !formData.senhaHash) {
    mostrarMensagem('Preencha todos os campos obrigatórios', 'erro');
    return;
  }

  if (formData.senhaHash !== formData.senhaHash_confirmation) {
    mostrarMensagem('As senhas não coincidem', 'erro');
    return;
  }

  loading.value = true;

  try {
    const dataToSend = {
      permissao: formData.permissao,
      nome: formData.nome,
      curso: formData.curso,
      matricula: formData.matricula,
      entrada: formData.entrada,
      senhaHash: formData.senhaHash,
      turma_id: formData.turma_id || null
    };

    const response = await api.post('/api/usuarios', dataToSend);

    if (response.status === 201) {
      mostrarMensagem('Cadastro realizado com sucesso!', 'sucesso');
      setTimeout(() => {
        router.push('/loginAluno');
      }, 1500);
    }
  } catch (error) {
    if (error.response?.data?.errors) {
      const firstError = Object.values(error.response.data.errors)[0][0];
      mostrarMensagem(firstError, 'erro');
    } else if (error.response?.data?.message) {
      mostrarMensagem(error.response.data.message, 'erro');
    } else {
      mostrarMensagem('Erro ao realizar cadastro', 'erro');
    }
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  carregarTurmas();
});
</script>
