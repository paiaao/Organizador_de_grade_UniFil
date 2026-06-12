<template>
  <div class="flex h-screen bg-stone-50">
    <div class="w-60 bg-[#ff9421] flex flex-col shadow-lg flex-shrink-0">
      <div class="h-28 flex items-center justify-center overflow-hidden relative">
        <img src="/logo-unifil.png" alt="UniFil" class="min-w-[800px] h-auto scale-110" />
        <hr class="absolute bottom-2 left-4 right-4 border-white border-t-2">
      </div>

      <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
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
      <div class="bg-white border-b border-stone-200 px-8 py-4 flex items-center justify-between">
        <div>
          <h1 class="text-lg font-bold text-stone-800">Turmas</h1>
          <p class="text-xs text-stone-500">Gerenciamento de turmas</p>
        </div>
        <div class="flex items-center gap-3">
          <button
            @click="criarDisciplina"
            class="flex items-center gap-2 bg-[#ff9421] hover:bg-[#e06800] text-white px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nova turma
          </button>
        </div>
      </div>

      <div class="flex-1 overflow-auto p-8">
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b-2 border-stone-200">
                  <th class="text-left py-3 px-4 font-semibold text-stone-600 text-sm">Turma</th>
                  <th class="text-left py-3 px-4 font-semibold text-stone-600 text-sm">Quantidade de Alunos</th>
                </tr>
              </thead>
              <tbody>
                <tr 
                  v-for="turma in turmas" 
                  :key="turma.id"
                  @click="editarDisciplina(turma.id)"
                  class="border-t border-stone-100 cursor-pointer hover:bg-orange-50 transition-all duration-150"
                >
                  <td class="py-3 px-4 text-stone-700 text-sm font-medium">{{ turma.nomeTurma }}</td>
                  <td class="py-3 px-4 text-stone-600 text-sm">{{ turma.qtdAlunos }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 backdrop-blur-sm" @click="fecharModalFora">
      <div class="bg-white rounded-2xl p-8 w-[560px] shadow-2xl border border-stone-100" @click.stop>
        <h3 class="text-xl font-bold text-stone-800 mb-1">{{ modalTitulo }}</h3>
        <p class="text-stone-500 text-sm mb-6">{{ modalSubtitulo }}</p>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Nome da Turma <span class="text-red-500">*</span></label>
            <input
              v-model="formTurma.nomeTurma"
              type="text"
              placeholder="Ex: Turma A, 3º Ano B"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              @keyup.enter="salvarTurma"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Quantidade de Alunos <span class="text-red-500">*</span></label>
            <input
              v-model.number="formTurma.qtdAlunos"
              type="number"
              min="1"
              placeholder="Número de alunos"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              @keyup.enter="salvarTurma"
            />
          </div>
        </div>

        <div class="mt-6 flex gap-3">
          <button
            @click="fecharModalFora"
            class="flex-1 py-3 border border-stone-300 text-stone-700 font-medium rounded-lg hover:bg-stone-50 transition text-sm"
          >
            Cancelar
          </button>
          <button
            @click="salvarTurma"
            :disabled="!formTurma.nomeTurma || !formTurma.qtdAlunos || formTurma.qtdAlunos < 1"
            class="flex-1 py-3 bg-[#ff9421] hover:bg-[#e06800] text-white font-semibold rounded-lg transition text-sm disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ modalBotao }}
          </button>
        </div>

        <div v-if="modoEdicao" class="mt-4 pt-3 border-t border-stone-100">
          <button
            @click="deletarTurma"
            class="flex items-center gap-2 text-red-500 hover:text-red-700 text-sm font-medium transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Deletar turma
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PainelAdminTurmas',
  data() {
    return {
      activeMenu: 'turma',
      selectedTurma: '',
      turmas: [],
      showModal: false,
      modoEdicao: false,
      turmaEditandoId: null,
      formTurma: {
        nomeTurma: '',
        qtdAlunos: null
      },
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
  computed: {
    modalTitulo() {
      return this.modoEdicao ? 'Editar turma' : 'Nova turma';
    },
    modalSubtitulo() {
      return this.modoEdicao ? 'Altere os dados da turma' : 'Preencha os dados para cadastrar';
    },
    modalBotao() {
      return this.modoEdicao ? 'Atualizar' : 'Cadastrar';
    }
  },
  async mounted() {
    this.api = this.$root.$api || this.$api;

    const usuario = JSON.parse(localStorage.getItem('usuario'));
    if (!usuario || usuario.permissao !== 1) {
      this.$router.push('/loginAdmin');
      return;
    }

    await this.carregarTurmas();
  },
  methods: {
    async carregarTurmas() {
      try {
        const response = await this.api.get('/api/turmas');
        this.turmas = response.data;
      } catch (error) {
        console.error('Erro ao carregar turmas:', error);
      }
    },
    criarDisciplina() {
      this.modoEdicao = false;
      this.turmaEditandoId = null;
      this.formTurma = { nomeTurma: '', qtdAlunos: null };
      this.showModal = true;
    },
    editarDisciplina(id) {
      const turma = this.turmas.find(t => t.id === id);
      if (!turma) return;
      this.modoEdicao = true;
      this.turmaEditandoId = turma.id;
      this.formTurma = {
        nomeTurma: turma.nomeTurma,
        qtdAlunos: turma.qtdAlunos
      };
      this.showModal = true;
    },
    fecharModalFora() {
      this.showModal = false;
    },
    async salvarTurma() {
      if (!this.formTurma.nomeTurma || !this.formTurma.qtdAlunos || this.formTurma.qtdAlunos < 1) return;

      try {
        if (this.modoEdicao) {
          await this.api.put(`/api/turmas/${this.turmaEditandoId}`, {
            nomeTurma: this.formTurma.nomeTurma,
            qtdAlunos: this.formTurma.qtdAlunos
          });
        } else {
          await this.api.post('/api/turmas', {
            nomeTurma: this.formTurma.nomeTurma,
            qtdAlunos: this.formTurma.qtdAlunos
          });
        }
        await this.carregarTurmas();
        this.fecharModalFora();
      } catch (error) {
        console.error('Erro ao salvar turma:', error);
      }
    },
    async deletarTurma() {
      if (!this.turmaEditandoId) return;
      const turma = this.turmas.find(t => t.id === this.turmaEditandoId);
      if (!confirm(`Tem certeza que deseja deletar a turma "${turma.nomeTurma}"?`)) return;

      try {
        await this.api.delete(`/api/turmas/${this.turmaEditandoId}`);
        await this.carregarTurmas();
        this.fecharModalFora();
      } catch (error) {
        console.error('Erro ao deletar turma:', error);
      }
    },
    navegar(item) {
      this.activeMenu = item.id;
      if (item.rota) this.$router.push(item.rota).catch(() => {});
    },
    sair() {
      localStorage.removeItem('usuario');
      this.$router.push('/').catch(() => {});
    }
  }
};
</script>