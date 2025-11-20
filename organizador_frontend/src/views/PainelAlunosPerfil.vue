<template>
  <div class="flex h-screen bg-gray-100">
    <div class="w-64 bg-[#ff9421] flex flex-col">
      <div class="h-32 flex items-center justify-center overflow-hidden relative">
        <img src="/logo-unifil.png" alt="UniFil" class="min-w-[1000px] h-auto scale-125" />
        <hr class="absolute bottom-2 left-4 right-4 border-white border-t-2">
      </div>

      <nav class="flex-1 px-4 space-y-4 mt-2">
        <button v-for="item in menuItems" :key="item.id" @click="navegar(item)" :class="['w-full px-4 py-3 text-left text-white font-medium rounded-lg text-sm transition-all', activeMenu === item.id ? 'bg-[#5a5a5a]' : 'bg-[#757575] hover:bg-[#858585]']">
          {{ item.label }}
        </button>
      </nav>

      <div class="p-5 pb-6">
        <button @click="sair" class="hover:opacity-80 transition" title="Sair">
          <img src="/sair.png" alt="Sair" class="w-10 h-10" />
        </button>
      </div>
    </div>

    <div class="flex-1 flex flex-col">
      <div class="flex-1 flex items-center justify-center p-8">
        <div class="bg-[#ffeccf] rounded-xl p-8 shadow-lg w-80 min-h-[500px] flex flex-col justify-between">
          <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">
              Meu Perfil
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
                      {{ aluno.nome || 'Carregando...' }}
                    </span>
                    <button 
                      @click="toggleEdicao('nome')" 
                      class="ml-2 hover:opacity-70 transition"
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
                    <span class="w-full bg-transparent text-gray-800 text-base">
                      {{ aluno.matricula || 'Carregando...' }}
                    </span>
                  </div>
                </div>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <label class="block text-gray-700 font-medium mb-2 text-sm">
                    Turma
                  </label>
                  <div class="border-b border-gray-700 pb-1 flex items-center">
                    <span class="w-full bg-transparent text-gray-800 text-base">
                      {{ aluno.turma?.turmaRepresentante || 'Nenhuma turma' }}
                    </span>
                  </div>
                </div>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <label class="block text-gray-700 font-medium mb-2 text-sm">
                    Curso
                  </label>
                  <div class="border-b border-gray-700 pb-1 flex items-center">
                    <span class="w-full bg-transparent text-gray-800 text-base">
                      {{ aluno.curso || 'Carregando...' }}
                    </span>
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
                    <span class="w-full bg-transparent text-gray-800 text-base">
                      {{ formatarData(aluno.entrada) || 'Carregando...' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PainelAlunosPerfil',
  data() {
    return {
      activeMenu: 'Perfil',
      aluno: {},
      alunoEditado: {
        nome: '',
        senhaHash: ''
      },
      editando: {
        nome: false,
        senha: false
      },
      salvando: false,
      menuItems: [
        { id: 'horarios', label: 'Horários', rota: '/PainelAlunosHorarios' },
        { id: 'Perfil', label: 'Perfil', rota: '/PainelAlunosPerfil' },
      ],
      api: null
    };
  },
  async mounted() {
    this.api = this.$root.$api || this.$api;
    
    const usuario = JSON.parse(localStorage.getItem('usuario'));
    if (!usuario || usuario.permissao !== 3) {
      this.$router.push('/loginAluno');
      return;
    }

    await this.carregarDadosAluno();
  },
  methods: {
    async carregarDadosAluno() {
      try {
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        const response = await this.api.get(`/api/usuarios/${usuario.id}`);
        this.aluno = response.data;
        
        this.alunoEditado = {
          nome: response.data.nome,
          senhaHash: ''
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
        this.alunoEditado.nome = this.aluno.nome;
      } else if (campo === 'senha') {
        this.alunoEditado.senhaHash = '';
      }
    },

    async salvarCampo(campo) {
      if (this.salvando) return;

      if (campo === 'senha' && this.alunoEditado.senhaHash && this.alunoEditado.senhaHash.length < 6) {
        this.$refs.senhaInput.focus();
        return;
      }

      this.salvando = true;

      try {
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        const dadosAtualizacao = {};

        if (campo === 'nome') {
          dadosAtualizacao.nome = this.alunoEditado.nome;
        } else if (campo === 'senha' && this.alunoEditado.senhaHash) {
          dadosAtualizacao.senhaHash = this.alunoEditado.senhaHash;
        }

        if (Object.keys(dadosAtualizacao).length > 0) {
          const response = await this.api.put(`/api/usuarios/${usuario.id}`, dadosAtualizacao);
          
          this.aluno = response.data;
          localStorage.setItem('usuario', JSON.stringify(response.data));
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