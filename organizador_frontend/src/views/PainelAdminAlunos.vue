<template>
  <div class="flex h-screen bg-gray-100">
    <div class="w-64 bg-[#ff9421] flex flex-col">
      <div class="h-32 flex items-center justify-center overflow-hidden relative">
        <img src="/logo-unifil.png" alt="UniFil" class="min-w-[1000px] h-auto scale-125" />
        <hr class="absolute bottom-2 left-4 right-4 border-white border-t-2">
      </div>

      <nav class="flex-1 px-4 space-y-4 mt-2">
        <button v-for="item in menuItems" :key="item.id"
          @click="navegar(item)":class="[
            'w-full px-4 py-3 text-left text-white font-medium rounded-lg text-sm transition-all',
            activeMenu === item.id ? 'bg-[#5a5a5a]' : 'bg-[#757575] hover:bg-[#858585]'
          ]">

          {{ item.label }}
        </button>
      </nav>

      <div class="p-5 pb-6">
        <button @click="sair" class="hover:opacity-80 transition" title="Sair">
          <img src="/sair.png" alt="Sair" class="w-10 h-10" />
        </button>
      </div>
    </div>

    <div class="flex-1 flex flex-col relative">
      <div class="p-8">
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center gap-4">
            <select v-model="selectedAluno" @change="carregarAlunoSelecionado" class="w-304 bg-[#ffac26] text-white px-8 py-2.5 rounded text-sm font-medium cursor-pointer border-none outline-none">
              <option value="">
                Selecione um aluno
              </option>
              <option v-for="aluno in alunos" :key="aluno.id" :value="aluno.id">
                {{ aluno.nome }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <div class="flex-1 flex items-center justify-center p-8">
        <div class="bg-[#ffeccf] rounded-xl p-8 shadow-lg w-80 min-h-[500px] flex flex-col justify-between">
          <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">
              Alunos
            </h2>
            
            <hr class="border-gray-300 mb-6">
            
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <label class="block text-gray-700 font-medium mb-2 text-sm">
                    Nome
                  </label>
                  <div class="border-b border-gray-700 pb-1 flex items-center">
                    <input 
                      v-if="editando.nome" 
                      v-model="alunoEditado.nome"
                      ref="nomeInput"
                      type="text" 
                      class="w-full bg-transparent text-gray-800 text-base outline-none"
                      @blur="salvarCampo('nome')"
                      @keyup.enter="salvarCampo('nome')"
                      @keyup.esc="cancelarEdicao('nome')"
                    >
                    <span v-else class="w-full bg-transparent text-gray-800 text-base">
                      {{ alunoSelecionado.nome || 'Selecione um aluno' }}
                    </span>
                    <button 
                      @click="toggleEdicao('nome')" 
                      class="ml-2 hover:opacity-70 transition"
                      :disabled="!selectedAluno"
                    >
                      <img src="/lapis.png" alt="Editar" class="w-5 h-5" />
                    </button>
                  </div>
                </div>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <label class="block text-gray-700 font-medium mb-2 text-sm">
                    Matrícula
                  </label>
                  <div class="border-b border-gray-700 pb-1 flex items-center">
                    <input 
                      v-if="editando.matricula" 
                      v-model="alunoEditado.matricula"
                      ref="matriculaInput"
                      type="text" 
                      class="w-full bg-transparent text-gray-800 text-base outline-none"
                      @blur="salvarCampo('matricula')"
                      @keyup.enter="salvarCampo('matricula')"
                      @keyup.esc="cancelarEdicao('matricula')"
                    >
                    <span v-else class="w-full bg-transparent text-gray-800 text-base">
                      {{ alunoSelecionado.matricula || 'Selecione um aluno' }}
                    </span>
                    <button 
                      @click="toggleEdicao('matricula')" 
                      class="ml-2 hover:opacity-70 transition"
                      :disabled="!selectedAluno"
                    >
                      <img src="/lapis.png" alt="Editar" class="w-5 h-5" />
                    </button>
                  </div>
                </div>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <label class="block text-gray-700 font-medium mb-2 text-sm">
                    Turma
                  </label>
                  <div class="border-b border-gray-700 pb-1 flex items-center">
                    <select 
                      v-if="editando.turma" 
                      v-model="alunoEditado.turma_id"
                      ref="turmaInput"
                      class="w-full bg-transparent text-gray-800 text-base outline-none border-none"
                      @blur="salvarCampo('turma')"
                      @keyup.esc="cancelarEdicao('turma')"
                    >
                      <option value="">Selecione uma turma</option>
                      <option v-for="turma in turmas" :key="turma.id" :value="turma.id">
                        {{ turma.turmaRepresentante }}
                      </option>
                    </select>
                    <span v-else class="w-full bg-transparent text-gray-800 text-base">
                      {{ alunoSelecionado.turma?.turmaRepresentante || 'Nenhuma turma' }}
                    </span>
                    <button 
                      @click="toggleEdicao('turma')" 
                      class="ml-2 hover:opacity-70 transition"
                      :disabled="!selectedAluno"
                    >
                      <img src="/lapis.png" alt="Editar" class="w-5 h-5" />
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <label class="block text-gray-700 font-medium mb-2 text-sm">
                    Senha
                  </label>
                  <div class="border-b border-gray-700 pb-1 flex items-center">
                    <input 
                      v-if="editando.senha" 
                      v-model="alunoEditado.senhaHash"
                      ref="senhaInput"
                      type="password" 
                      placeholder="Nova senha (mín. 6 caracteres)"
                      class="w-full bg-transparent text-gray-800 text-base outline-none"
                      @blur="salvarCampo('senha')"
                      @keyup.enter="salvarCampo('senha')"
                      @keyup.esc="cancelarEdicao('senha')"
                    >
                    <span v-else class="w-full bg-transparent text-gray-500 text-base">
                      ••••••••
                    </span>
                    <button 
                      @click="toggleEdicao('senha')" 
                      class="ml-2 hover:opacity-70 transition"
                      :disabled="!selectedAluno"
                    >
                      <img src="/lapis.png" alt="Editar" class="w-5 h-5" />
                    </button>
                  </div>
                  <div v-if="editando.senha && alunoEditado.senhaHash.length > 0 && alunoEditado.senhaHash.length < 6" 
                       class="text-red-500 text-xs mt-1">
                    A senha deve ter pelo menos 6 caracteres
                  </div>
                </div>
              </div>
              
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <label class="block text-gray-700 font-medium mb-2 text-sm">
                    Entrada
                  </label>
                  <div class="border-b border-gray-700 pb-1 flex items-center">
                    <input 
                      v-if="editando.entrada" 
                      v-model="alunoEditado.entrada"
                      ref="entradaInput"
                      type="date" 
                      class="w-full bg-transparent text-gray-800 text-base outline-none"
                      @blur="salvarCampo('entrada')"
                      @keyup.esc="cancelarEdicao('entrada')"
                    >
                    <span v-else class="w-full bg-transparent text-gray-800 text-base">
                      {{ formatarData(alunoSelecionado.entrada) || 'Selecione um aluno' }}
                    </span>
                    <button 
                      @click="toggleEdicao('entrada')" 
                      class="ml-2 hover:opacity-70 transition"
                      :disabled="!selectedAluno"
                    >
                      <img src="/lapis.png" alt="Editar" class="w-5 h-5" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <button 
        @click="deletarAluno" 
        :disabled="!selectedAluno"
        class="absolute bottom-8 right-8 p-3 transition hover:opacity-70"
        :class="selectedAluno ? 'cursor-pointer' : 'cursor-not-allowed opacity-40'"
        title="Deletar aluno selecionado"
      >
        <img src="/lixo.png" alt="Deletar" class="w-8 h-8" />
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PainelAdminAlunos',
  data() {
    return {
      activeMenu: 'alunos',
      selectedAluno: '',
      alunos: [],
      turmas: [],
      alunoSelecionado: {},
      alunoEditado: {
        nome: '',
        matricula: '',
        turma_id: '',
        senhaHash: '',
        entrada: ''
      },
      editando: {
        nome: false,
        matricula: false,
        turma: false,
        senha: false,
        entrada: false
      },
      salvando: false,
      menuItems: [
        { id: 'horarios', label: 'Horários', rota: '/PainelAdminHorarios' },
        { id: 'disciplinas', label: 'Disciplinas', rota: '/PainelAdminDisciplinas' },
        { id: 'salas', label: 'Salas', rota: '/PainelAdminSalas' },
        { id: 'turma', label: 'Turma', rota: '/PainelAdminTurmas' },
        { id: 'professores', label: 'Professores', rota: '/PainelAdminProfessores' },
        { id: 'admins', label: 'Admins', rota: '/PainelAdminAdmins' },
        { id: 'alunos', label: 'Alunos', rota: '/PainelAdminAlunos' }
      ],
      api: null
    };
  },
  async mounted() {
    this.api = this.$root.$api || this.$api;
    
    const usuario = JSON.parse(localStorage.getItem('usuario'));
    if (!usuario || usuario.permissao !== 1) {
      this.$router.push('/loginAdmin');
      return;
    }

    await this.carregarAlunos();
    await this.carregarTurmas();
  },
  methods: {
    async carregarAlunos() {
      try {
        const response = await this.api.get('/api/usuarios');
        this.alunos = response.data.filter(user => user.permissao === 3);
      } catch (error) {
      }
    },

    async carregarTurmas() {
      try {
        const response = await this.api.get('/api/turmas');
        this.turmas = response.data;
      } catch (error) {
      }
    },

    async carregarAlunoSelecionado() {
      if (!this.selectedAluno) {
        this.alunoSelecionado = {};
        return;
      }

      try {
        const response = await this.api.get(`/api/usuarios/${this.selectedAluno}`);
        this.alunoSelecionado = response.data;
        
        this.alunoEditado = {
          nome: response.data.nome,
          matricula: response.data.matricula || '',
          turma_id: response.data.turma_id || '',
          senhaHash: '',
          entrada: response.data.entrada.split('T')[0]
        };
      } catch (error) {
      }
    },

    async deletarAluno() {
      if (!this.selectedAluno) {
        return;
      }

      const alunoParaDeletar = this.alunos.find(aluno => aluno.id === this.selectedAluno);
      
      if (!alunoParaDeletar) {
        return;
      }

      const confirmacao = confirm(`Tem certeza que deseja deletar o aluno "${alunoParaDeletar.nome}"? Esta ação não pode ser desfeita.`);
      
      if (!confirmacao) {
        return;
      }

      try {
        await this.api.delete(`/api/usuarios/${this.selectedAluno}`);
        
        await this.carregarAlunos();
        
        this.selectedAluno = '';
        this.alunoSelecionado = {};
        this.alunoEditado = {
          nome: '',
          matricula: '',
          turma_id: '',
          senhaHash: '',
          entrada: ''
        };
        
      } catch (error) {
      }
    },

    toggleEdicao(campo) {
      if (this.salvando) return;
      
      Object.keys(this.editando).forEach(key => {
        this.editando[key] = false;
      });
      
      this.editando[campo] = true;
      
      this.$nextTick(() => {
        const refName = `${campo}Input`;
        if (this.$refs[refName]) {
          this.$refs[refName].focus();
        }
      });
    },

    cancelarEdicao(campo) {
      this.editando[campo] = false;
      if (campo === 'nome') {
        this.alunoEditado.nome = this.alunoSelecionado.nome;
      } else if (campo === 'matricula') {
        this.alunoEditado.matricula = this.alunoSelecionado.matricula;
      } else if (campo === 'turma') {
        this.alunoEditado.turma_id = this.alunoSelecionado.turma_id;
      } else if (campo === 'senha') {
        this.alunoEditado.senhaHash = '';
      } else if (campo === 'entrada') {
        this.alunoEditado.entrada = this.alunoSelecionado.entrada.split('T')[0];
      }
    },

    async salvarCampo(campo) {
      if (this.salvando) return;
      
      if (!this.selectedAluno) return;

      if (campo === 'senha' && this.alunoEditado.senhaHash && this.alunoEditado.senhaHash.length < 6) {
        this.$refs.senhaInput.focus();
        return;
      }

      this.salvando = true;

      try {
        const dadosAtualizacao = {};

        if (campo === 'nome') {
          dadosAtualizacao.nome = this.alunoEditado.nome;
        } else if (campo === 'matricula') {
          dadosAtualizacao.matricula = this.alunoEditado.matricula;
        } else if (campo === 'turma') {
          dadosAtualizacao.turma_id = this.alunoEditado.turma_id || null;
        } else if (campo === 'senha' && this.alunoEditado.senhaHash) {
          dadosAtualizacao.senhaHash = this.alunoEditado.senhaHash;
        } else if (campo === 'entrada') {
          dadosAtualizacao.entrada = this.alunoEditado.entrada;
        }

        if (Object.keys(dadosAtualizacao).length > 0) {
          const response = await this.api.put(`/api/usuarios/${this.selectedAluno}`, dadosAtualizacao);
          this.alunoSelecionado = response.data;
        }

        this.editando[campo] = false;
        this.alunoEditado.senhaHash = '';
      } catch (error) {
        this.cancelarEdicao(campo);
      } finally {
        this.salvando = false;
      }
    },

    formatarData(data) {
      if (!data) return '';
      const date = new Date(data);
      return date.toLocaleDateString('pt-BR');
    },

    navegar(item) {
      this.activeMenu = item.id;
      if (item.rota) {
        this.$router.push(item.rota).catch(() => {});
      }
    },

    sair() {
      localStorage.removeItem('usuario');
      this.$router.push('/').catch(() => {});
    }
  }
};
</script>