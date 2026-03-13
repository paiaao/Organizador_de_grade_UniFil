<template>
  <div class="flex h-screen bg-stone-50">
    <!-- Sidebar -->
    <div class="w-60 bg-[#ff9421] flex flex-col shadow-lg flex-shrink-0">
      <div class="h-20 flex items-center justify-center px-4 border-b border-white/20">
        <img src="/logo-unifil.png" alt="UniFil" class="h-10 w-auto object-contain" />
      </div>

      <nav class="flex-1 px-3 py-4 space-y-1">
        <button
          v-for="item in menuItems"
          :key="item.id"
          @click="navegar(item)"
          :class="['w-full px-4 py-2.5 text-left text-sm font-medium rounded-lg transition-all duration-150',
            activeMenu === item.id
              ? 'bg-white text-[#ff9421] shadow-sm'
              : 'text-white hover:bg-white/15']"
        >
          {{ item.label }}
        </button>
      </nav>

      <div class="px-3 py-4 border-t border-white/20">
        <button
          @click="sair"
          class="w-full flex items-center gap-3 px-4 py-2.5 text-white text-sm font-medium rounded-lg hover:bg-white/15 transition-all duration-150"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Sair
        </button>
      </div>
    </div>

    <!-- Conteúdo -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <div class="bg-white border-b border-stone-200 px-8 py-4">
        <h1 class="text-lg font-bold text-stone-800">Meu Perfil</h1>
        <p class="text-xs text-stone-500">Gerencie suas informações pessoais</p>
      </div>

      <div class="flex-1 overflow-auto p-8 flex items-start justify-center">
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-8 w-full max-w-md">
          <!-- Avatar -->
          <div class="flex flex-col items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center mb-3">
              <svg class="w-8 h-8 text-[#ff9421]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <h2 class="text-base font-bold text-stone-800">{{ aluno.nome || 'Carregando...' }}</h2>
            <p class="text-xs text-stone-500">Aluno</p>
          </div>

          <div class="space-y-4">
            <!-- Nome -->
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Nome</label>
              <div class="flex items-center gap-2">
                <input
                  v-if="editando.nome"
                  v-model="alunoEditado.nome"
                  ref="nomeInput"
                  type="text"
                  class="flex-1 border border-stone-300 rounded-lg px-3 py-2 text-stone-800 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                  @blur="salvarCampo('nome')"
                  @keyup.enter="salvarCampo('nome')"
                  @keyup.esc="cancelarEdicao('nome')"
                />
                <span v-else class="flex-1 px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                  {{ aluno.nome || '—' }}
                </span>
                <button @click="toggleEdicao('nome')" class="p-2 rounded-lg hover:bg-orange-50 text-stone-400 hover:text-[#ff9421] transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Matrícula -->
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Matrícula</label>
              <div class="px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                {{ aluno.matricula || '—' }}
              </div>
            </div>

            <!-- Turma -->
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Turma</label>
              <div class="px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                {{ aluno.turma?.turmaRepresentante || 'Nenhuma turma' }}
              </div>
            </div>

            <!-- Curso -->
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Curso</label>
              <div class="px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                {{ aluno.curso || '—' }}
              </div>
            </div>

            <!-- Senha -->
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Senha</label>
              <div class="flex items-center gap-2">
                <input
                  v-if="editando.senha"
                  v-model="alunoEditado.senhaHash"
                  ref="senhaInput"
                  type="password"
                  placeholder="Nova senha (mín. 6 caracteres)"
                  class="flex-1 border border-stone-300 rounded-lg px-3 py-2 text-stone-800 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                  @blur="salvarCampo('senha')"
                  @keyup.enter="salvarCampo('senha')"
                  @keyup.esc="cancelarEdicao('senha')"
                />
                <span v-else class="flex-1 px-3 py-2 text-stone-500 text-sm bg-stone-50 rounded-lg border border-stone-200">
                  ••••••••
                </span>
                <button @click="toggleEdicao('senha')" class="p-2 rounded-lg hover:bg-orange-50 text-stone-400 hover:text-[#ff9421] transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </div>
              <p v-if="editando.senha && alunoEditado.senhaHash.length > 0 && alunoEditado.senhaHash.length < 6"
                 class="text-red-500 text-xs mt-1">
                A senha deve ter pelo menos 6 caracteres
              </p>
            </div>

            <!-- Entrada -->
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Entrada</label>
              <div class="px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                {{ formatarData(aluno.entrada) || '—' }}
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
      } catch (error) {}
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
