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
          <h1 class="text-lg font-bold text-stone-800">Disciplinas</h1>
          <p class="text-xs text-stone-500">Gerenciamento de disciplinas (aulas)</p>
        </div>
        <div class="flex items-center gap-3">
          <button
            @click="abrirModalUC"
            class="flex items-center gap-2 bg-[#ff9421] hover:bg-[#e06800] text-white px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nova UC
          </button>
          <button
            @click="criarDisciplina"
            class="flex items-center gap-2 bg-[#ff9421] hover:bg-[#e06800] text-white px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nova disciplina
          </button>
        </div>
      </div>
      <div class="flex-1 overflow-auto p-8">
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b-2 border-stone-200">
                  <th class="text-left py-3 px-4 font-semibold text-stone-600 text-sm">Disciplina</th>
                  <th class="text-left py-3 px-4 font-semibold text-stone-600 text-sm">Tipo</th>
                  <th class="text-left py-3 px-4 font-semibold text-stone-600 text-sm">Carga Horária</th>
                  <th class="text-left py-3 px-4 font-semibold text-stone-600 text-sm">Docente</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="aulas.length === 0">
                  <td colspan="4" class="py-8 text-center text-stone-500">Nenhuma disciplina encontrada.</td>
                </tr>
                <tr 
                  v-for="aula in aulas" 
                  :key="aula.id"
                  @click="editarDisciplina(aula.id)"
                  class="border-t border-stone-100 cursor-pointer hover:bg-orange-50 transition-all duration-150"
                >
                  <td class="py-3 px-4 text-stone-700 text-sm font-medium">{{ aula.uc?.nomeUc || '—' }}</td>
                  <td class="py-3 px-4 text-stone-600 text-sm">{{ aula.uc?.tipo || '—' }}</td>
                  <td class="py-3 px-4 text-stone-500 text-sm">{{ aula.uc?.cargahoraria || '—' }} h</td>
                  <td class="py-3 px-4 text-stone-600 text-sm">{{ aula.professor?.nome || '—' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showModalAula" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 backdrop-blur-sm" @click="fecharModalAula">
      <div class="bg-white rounded-2xl p-8 w-[560px] shadow-2xl border border-stone-100" @click.stop>
        <h3 class="text-xl font-bold text-stone-800 mb-1">{{ modalTituloAula }}</h3>
        <p class="text-stone-500 text-sm mb-6">{{ modalSubtituloAula }}</p>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">UC (Unidade Curricular) <span class="text-red-500">*</span></label>
            <select
              v-model="formAula.uc_id"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
              <option :value="null" disabled>Selecione uma UC</option>
              <option v-for="uc in ucs" :key="uc.id" :value="uc.id">
                {{ uc.nomeUc }} ({{ uc.tipo }} - {{ uc.cargahoraria }}h)
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Professor <span class="text-red-500">*</span></label>
            <select
              v-model="formAula.professor_id"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
              <option :value="null" disabled>Selecione um professor</option>
              <option v-for="prof in professores" :key="prof.id" :value="prof.id">
                {{ prof.nome }}
              </option>
            </select>
          </div>
        </div>

        <div class="mt-6 flex gap-3">
          <button
            @click="fecharModalAula"
            class="flex-1 py-3 border border-stone-300 text-stone-700 font-medium rounded-lg hover:bg-stone-50 transition text-sm"
          >
            Cancelar
          </button>
          <button
            @click="salvarAula"
            :disabled="!formAula.uc_id || !formAula.professor_id"
            class="flex-1 py-3 bg-[#ff9421] hover:bg-[#e06800] text-white font-semibold rounded-lg transition text-sm disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ modalBotaoAula }}
          </button>
        </div>

        <div v-if="modoEdicaoAula" class="mt-4 pt-3 border-t border-stone-100">
          <button
            @click="deletarAula"
            class="flex items-center gap-2 text-red-500 hover:text-red-700 text-sm font-medium transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Deletar disciplina
          </button>
        </div>
      </div>
    </div>
    <div v-if="showModalUC" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 backdrop-blur-sm" @click="fecharModalUC">
      <div class="bg-white rounded-2xl p-8 w-[560px] shadow-2xl border border-stone-100" @click.stop>
        <h3 class="text-xl font-bold text-stone-800 mb-1">Nova UC</h3>
        <p class="text-stone-500 text-sm mb-6">Preencha os dados para cadastrar</p>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Nome da UC <span class="text-red-500">*</span></label>
            <input
              v-model="formUC.nomeUc"
              type="text"
              placeholder="Ex: Estruturas de Dados"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              @keyup.enter="salvarUC"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Tipo <span class="text-red-500">*</span></label>
            <input
              v-model="formUC.tipo"
              type="text"
              placeholder="Ex: Teórica, Prática, Laboratório"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              @keyup.enter="salvarUC"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Carga Horária (horas) <span class="text-red-500">*</span></label>
            <input
              v-model.number="formUC.cargahoraria"
              type="number"
              min="1"
              placeholder="Ex: 60"
              class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              @keyup.enter="salvarUC"
            />
          </div>
        </div>

        <div class="mt-6 flex gap-3">
          <button
            @click="fecharModalUC"
            class="flex-1 py-3 border border-stone-300 text-stone-700 font-medium rounded-lg hover:bg-stone-50 transition text-sm"
          >
            Cancelar
          </button>
          <button
            @click="salvarUC"
            :disabled="!formUC.nomeUc || !formUC.tipo || !formUC.cargahoraria || formUC.cargahoraria < 1"
            class="flex-1 py-3 bg-[#ff9421] hover:bg-[#e06800] text-white font-semibold rounded-lg transition text-sm disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Cadastrar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PainelAdminDisciplinas',
  data() {
    return {
      activeMenu: 'disciplinas',
      aulas: [],
      ucs: [],
      professores: [],
      showModalAula: false,
      modoEdicaoAula: false,
      aulaEditandoId: null,
      formAula: {
        uc_id: null,
        professor_id: null
      },
      showModalUC: false,
      formUC: {
        nomeUc: '',
        tipo: '',
        cargahoraria: null
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
    modalTituloAula() {
      return this.modoEdicaoAula ? 'Editar disciplina' : 'Nova disciplina';
    },
    modalSubtituloAula() {
      return this.modoEdicaoAula ? 'Altere o professor ou a UC' : 'Vincule uma UC a um professor';
    },
    modalBotaoAula() {
      return this.modoEdicaoAula ? 'Atualizar' : 'Cadastrar';
    }
  },
  async mounted() {
    this.api = this.$root.$api || this.$api;

    const usuario = JSON.parse(localStorage.getItem('usuario'));
    if (!usuario || usuario.permissao !== 1) {
      this.$router.push('/loginAdmin');
      return;
    }

    await this.carregarAulas();
    await this.carregarUcs();
    await this.carregarProfessores();
  },
  methods: {
    async carregarAulas() {
      try {
        const response = await this.api.get('/api/aulas');
        this.aulas = response.data;
      } catch (error) {
        console.error('Erro ao carregar aulas:', error);
      }
    },
    async carregarUcs() {
      try {
        const response = await this.api.get('/api/ucs');
        this.ucs = response.data;
      } catch (error) {
        console.error('Erro ao carregar UCs:', error);
      }
    },
    async carregarProfessores() {
      try {
        const response = await this.api.get('/api/usuarios');
        this.professores = response.data.filter(u => u.permissao === 2);
      } catch (error) {
        console.error('Erro ao carregar professores:', error);
      }
    },

    abrirModalUC() {
      this.formUC = { nomeUc: '', tipo: '', cargahoraria: null };
      this.showModalUC = true;
    },
    fecharModalUC() {
      this.showModalUC = false;
    },
    async salvarUC() {
      if (!this.formUC.nomeUc || !this.formUC.tipo || !this.formUC.cargahoraria || this.formUC.cargahoraria < 1) return;
      try {
        await this.api.post('/api/ucs', {
          nomeUc: this.formUC.nomeUc,
          tipo: this.formUC.tipo,
          cargahoraria: this.formUC.cargahoraria
        });
        await this.carregarUcs();
        this.fecharModalUC();
      } catch (error) {
        console.error('Erro ao salvar UC:', error);
      }
    },

    criarDisciplina() {
      this.modoEdicaoAula = false;
      this.aulaEditandoId = null;
      this.formAula = { uc_id: null, professor_id: null };
      this.showModalAula = true;
    },
    editarDisciplina(id) {
      const aula = this.aulas.find(a => a.id === id);
      if (!aula) return;
      this.modoEdicaoAula = true;
      this.aulaEditandoId = aula.id;
      this.formAula = {
        uc_id: aula.uc_id,
        professor_id: aula.professor_id
      };
      this.showModalAula = true;
    },
    fecharModalAula() {
      this.showModalAula = false;
    },
    async salvarAula() {
      if (!this.formAula.uc_id || !this.formAula.professor_id) return;

      try {
        if (this.modoEdicaoAula) {
          await this.api.put(`/api/aulas/${this.aulaEditandoId}`, {
            uc_id: this.formAula.uc_id,
            professor_id: this.formAula.professor_id
          });
        } else {
          await this.api.post('/api/aulas', {
            uc_id: this.formAula.uc_id,
            professor_id: this.formAula.professor_id
          });
        }
        await this.carregarAulas();
        this.fecharModalAula();
      } catch (error) {
        console.error('Erro ao salvar aula:', error);
      }
    },
    async deletarAula() {
      if (!this.aulaEditandoId) return;
      const aula = this.aulas.find(a => a.id === this.aulaEditandoId);
      if (!confirm(`Tem certeza que deseja deletar a disciplina "${aula.uc?.nomeUc}" com o professor ${aula.professor?.nome}?`)) return;

      try {
        await this.api.delete(`/api/aulas/${this.aulaEditandoId}`);
        await this.carregarAulas();
        this.fecharModalAula();
      } catch (error) {
        console.error('Erro ao deletar aula:', error);
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