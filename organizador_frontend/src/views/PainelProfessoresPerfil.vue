<template>
  <div class="flex h-screen bg-stone-50">
    <div class="w-60 bg-[#ff9421] flex flex-col shadow-lg flex-shrink-0">
      <div class="h-28 flex items-center justify-center overflow-hidden relative">
        <img src="/logo-unifil.png" alt="UniFil" class="min-w-[800px] h-auto scale-110" />
        <hr class="absolute bottom-2 left-4 right-4 border-white border-t-2">
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

    <div class="flex-1 flex flex-col overflow-hidden">
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
            <h2 class="text-base font-bold text-stone-800">{{ professor.nome || 'Carregando...' }}</h2>
            <p class="text-xs text-stone-500">Professor</p>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Nome</label>
              <div class="flex items-center gap-2">
                <input
                  v-if="editando.nome"
                  v-model="professorEditado.nome"
                  ref="nomeInput"
                  type="text"
                  class="flex-1 border border-stone-300 rounded-lg px-3 py-2 text-stone-800 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                  @blur="salvarCampo('nome')"
                  @keyup.enter="salvarCampo('nome')"
                  @keyup.esc="cancelarEdicao('nome')"
                />
                <span v-else class="flex-1 px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                  {{ professor.nome || '—' }}
                </span>
                <button @click="toggleEdicao('nome')" class="p-2 rounded-lg hover:bg-orange-50 text-stone-400 hover:text-[#ff9421] transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Senha</label>
              <div class="flex items-center gap-2">
                <input
                  v-if="editando.senha"
                  v-model="professorEditado.senhaHash"
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
              <p v-if="editando.senha && professorEditado.senhaHash.length > 0 && professorEditado.senhaHash.length < 6"
                 class="text-red-500 text-xs mt-1">
                A senha deve ter pelo menos 6 caracteres
              </p>
            </div>

            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Entrada</label>
              <div class="px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                {{ formatarData(professor.entrada) || '—' }}
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
  name: 'PainelProfessoresPerfil',
  data() {
    return {
      activeMenu: 'Perfil',
      professor: {},
      professorEditado: {
        nome: '',
        senhaHash: ''
      },
      editando: {
        nome: false,
        senha: false
      },
      salvando: false,
      menuItems: [
        { id: 'horarios', label: 'Horários', rota: '/PainelProfessoresHorarios' },
        { id: 'Perfil', label: 'Perfil', rota: '/PainelProfessoresPerfil' },
      ],
      api: null
    };
  },
  async mounted() {
    this.api = this.$root.$api || this.$api;

    const usuario = JSON.parse(localStorage.getItem('usuario'));
    if (!usuario || usuario.permissao !== 2) {
      this.$router.push('/loginProfessor');
      return;
    }

    await this.carregarDadosProfessor();
  },
  methods: {
    async carregarDadosProfessor() {
      try {
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        const response = await this.api.get(`/api/usuarios/${usuario.id}`);
        this.professor = response.data;

        this.professorEditado = {
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
        this.professorEditado.nome = this.professor.nome;
      } else if (campo === 'senha') {
        this.professorEditado.senhaHash = '';
      }
    },

    async salvarCampo(campo) {
      if (this.salvando) return;

      if (campo === 'senha' && this.professorEditado.senhaHash && this.professorEditado.senhaHash.length < 6) {
        this.$refs.senhaInput.focus();
        return;
      }

      this.salvando = true;

      try {
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        const dadosAtualizacao = {};

        if (campo === 'nome') {
          dadosAtualizacao.nome = this.professorEditado.nome;
        } else if (campo === 'senha' && this.professorEditado.senhaHash) {
          dadosAtualizacao.senhaHash = this.professorEditado.senhaHash;
        }

        if (Object.keys(dadosAtualizacao).length > 0) {
          const response = await this.api.put(`/api/usuarios/${usuario.id}`, dadosAtualizacao);

          this.professor = response.data;
          localStorage.setItem('usuario', JSON.stringify(response.data));
        }

        this.editando[campo] = false;
        this.professorEditado.senhaHash = '';
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