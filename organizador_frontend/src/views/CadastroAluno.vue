<template>
  <div class="flex flex-col items-center justify-center h-screen bg-gradient-to-b from-[#ff9421] to-[#ffbe78]">
    <div class="absolute top-1 w-full flex justify-center">
      <img src="/logo-unifil.png" alt="UniFil" class="w-80 md:w-96 lg:w-[800px] mx-auto" />
    </div>

    <div class="bg-[#ffeccf] w-90 rounded-2xl shadow-md p-10 flex flex-col items-center relative transition-all duration-300">
      <p class="text-center tracking-[7px] text-[#545252] leading-relaxed mb-2 font-black">
        Cadastro
      </p>

      <div class="relative flex flex-col items-center w-full space-y-1">
        <div class="w-full">
          <label class="block text-[#545252] font-medium mb-1 text-sm">
            Nome
          </label>
          <div class="border-b border-[#545252] pb-1 flex items-center">
            <input type="text" placeholder="Insira o seu nome aqui" v-model="formData.nome" class="w-full bg-transparent outline-none text-[#545252] placeholder-[#a0a0a0] text-base"/>
            <img src="/pessoa.png" alt="Pessoa" class="w-6 h-6 ml-2" />
          </div>
        </div>

        <div class="w-full">
          <label class="block text-[#545252] font-medium mb-1 text-sm">
            Curso
          </label>
          <div class="border-b border-[#545252] pb-1 flex items-center">
            <select v-model="formData.curso" class="w-full bg-transparent outline-none text-[#545252] text-base">
              <option value="">Selecione seu curso</option>
              <option value="Engenharia de Software">Engenharia de Software</option>
              <option value="Ciências da Computação">Ciências da Computação</option>
            </select>
            <img src="/livro.png" alt="Livro" class="w-6 h-6 ml-2" />
          </div>
        </div>

        <div class="w-full">
          <label class="block text-[#545252] font-medium mb-1 text-sm">
            Turma
          </label>
          <div class="border-b border-[#545252] pb-1 flex items-center">
            <select v-model="formData.turma_id" class="w-full bg-transparent outline-none text-[#545252] text-base">
              <option value="">Selecione sua turma</option>
              <option v-for="turma in turmas" :key="turma.id" :value="turma.id">
                {{ turma.turmaRepresentante }}
              </option>
            </select>
            <img src="/livro.png" alt="Livro" class="w-6 h-6 ml-2" />
          </div>
          <div v-if="carregandoTurmas" class="text-xs text-blue-500">Carregando turmas...</div>
          <div v-if="erroTurmas" class="text-xs text-red-500">{{ erroTurmas }}</div>
        </div>

        <div class="w-full">
          <label class="block text-[#545252] font-medium mb-1 text-sm">Matrícula</label>
          <div class="border-b border-[#545252] pb-1 flex items-center">
            <input type="text" placeholder="Insira a sua matrícula aqui" v-model="formData.matricula" class="w-full bg-transparent outline-none text-[#545252] placeholder-[#a0a0a0] text-base"/>
            <img src="/lapis.png" alt="Lápis" class="w-5 h-5 ml-2" />
          </div>
        </div>

        <div class="w-full">
          <label class="block text-[#545252] font-medium mb-1 text-sm">
            Senha
          </label>
          <div class="border-b border-[#545252] pb-1 flex items-center">
            <input type="password" placeholder="Insira a sua senha aqui" v-model="formData.senhaHash" class="w-full bg-transparent outline-none text-[#545252] placeholder-[#a0a0a0] text-base"/>
            <img src="/olhosenha.png" alt="Mostrar senha" class="w-6 h-6 ml-2" />
          </div>
        </div>

        <div class="w-full">
          <label class="block text-[#545252] font-medium mb-1 text-sm">
            Confirmação de Senha
          </label>
          <div class="border-b border-[#545252] pb-1 flex items-center">
            <input type="password" placeholder="Insira a sua senha aqui" v-model="formData.senhaHash_confirmation" class="w-full bg-transparent outline-none text-[#545252] placeholder-[#a0a0a0] text-base"/>
            <img src="/olhosenha.png" alt="Mostrar senha" class="w-6 h-6 ml-2" />
          </div>
        </div>
      </div>

      <button 
        @click="submitForm"
        :disabled="loading"
        class="bg-[#ffac26] tracking-[7px] w-60 h-12 text-white font-semibold text-lg rounded-full py-2 px-10 shadow-md hover:shadow-lg transition-all mt-4 disabled:opacity-50"
      >
        {{ loading ? 'Cadastrando...' : 'Enviar' }}
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
import { ref, reactive, onMounted, inject } from "vue";
import { useRouter } from 'vue-router';

const router = useRouter();
const loading = ref(false);
const carregandoTurmas = ref(false);
const erroTurmas = ref('');
const turmas = ref([]);

const mensagem = ref({
  texto: '',
  tipo: '' // 'erro', 'sucesso'
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
      erroTurmas.value = `Erro ao carregar turmas`;
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