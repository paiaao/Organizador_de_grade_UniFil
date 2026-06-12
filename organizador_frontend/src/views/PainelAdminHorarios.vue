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
          <h1 class="text-lg font-bold text-stone-800">Horários</h1>
          <p class="text-xs text-stone-500">Visualização das grades horárias por turma</p>
        </div>
        <div class="flex items-center gap-3">
          <select
            v-model="selectedTurma"
            @change="carregarHorariosPorTurma"
            class="border border-stone-300 rounded-lg px-4 py-2 text-sm text-stone-700 focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white"
          >
            <option value="">Selecione uma turma</option>
            <option v-for="turma in turmas" :key="turma.id" :value="turma.id">
              {{ turma.nomeTurma }}
            </option>
          </select>
          <button
            @click="criarNovoHorario"
            class="flex items-center gap-2 bg-[#ff9421] hover:bg-[#e06800] text-white px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo horário
          </button>
        </div>
      </div>

      <div class="flex-1 overflow-auto p-8">
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6">
          <div class="grid grid-cols-5 gap-3 mb-3">
            <div
              v-for="dia in diasSemana"
              :key="dia"
              class="bg-[#ff9421] text-white py-2.5 rounded-lg text-center font-semibold text-xs tracking-wide"
            >
              {{ dia }}
            </div>
          </div>

          <div class="grid grid-cols-5 gap-3 mb-3">
            <div
              v-for="(slot, idx) in gradePrimeiroTurno"
              :key="`primeiro-${idx}`"
              class="bg-stone-50 border border-stone-200 rounded-lg h-28 flex items-center justify-center p-3"
            >
              <div v-if="slot.horario" class="text-xs text-center text-stone-700 font-medium leading-relaxed">
                <div>{{ slot.horario.nomeUc }}</div>
                <div class="text-stone-500">{{ slot.horario.professor }}</div>
                <div class="text-stone-500">{{ slot.horario.sala }}</div>
              </div>
              <span v-else class="text-stone-300 text-xs">—</span>
            </div>
          </div>

          <div class="grid grid-cols-5 gap-3 mb-6">
            <div
              v-for="(slot, idx) in gradeSegundoTurno"
              :key="`segundo-${idx}`"
              class="bg-stone-50 border border-stone-200 rounded-lg h-28 flex items-center justify-center p-3"
            >
              <div v-if="slot.horario" class="text-xs text-center text-stone-700 font-medium leading-relaxed">
                <div>{{ slot.horario.nomeUc }}</div>
                <div class="text-stone-500">{{ slot.horario.professor }}</div>
                <div class="text-stone-500">{{ slot.horario.sala }}</div>
              </div>
              <span v-else class="text-stone-300 text-xs">—</span>
            </div>
          </div>

          <div class="space-y-2">
            <div
              v-for="(ucd, index) in ucds"
              :key="`ucd-${index}`"
              class="bg-stone-50 border border-stone-200 rounded-lg px-5 py-4 cursor-pointer hover:bg-orange-50 hover:border-orange-300 transition-all duration-150 flex items-center gap-4"
            >
              <span class="font-semibold text-stone-700 text-sm">{{ ucd.label }}</span>
              <span v-if="ucd.conteudo" class="text-sm text-stone-500">{{ ucd.conteudo }}</span>
              <span v-else class="text-stone-300 text-sm">Clique para editar</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PainelAdminHorarios',
  data() {
    return {
      activeMenu: 'horarios',
      selectedTurma: '',
      turmas: [],
      gradePrimeiroTurno: [], 
      gradeSegundoTurno: [],  
      posicoesSemana: [],
      posicaoMap: new Map(),
      diasSemana: ['SEG', 'TER', 'QUA', 'QUI', 'SEX'],
      ucds: [
        { label: 'UCD', conteudo: '' },
        { label: 'UCD', conteudo: '' }
      ],
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
    await this.carregarTurmas();
    await this.carregarPosicoesSemana();
    this.inicializarGradeVazia();
  },
  methods: {
    async carregarTurmas() {
      try {
        const res = await this.api.get('/api/turmas');
        this.turmas = res.data;
      } catch (error) {
        console.error('Erro ao carregar turmas:', error);
      }
    },
    async carregarPosicoesSemana() {
      try {
        const res = await this.api.get('/api/posicaoSemana');
        this.posicoesSemana = res.data;
        this.posicaoMap.clear();
        this.posicoesSemana.forEach(pos => {
          const key = `${pos.diaSemana}|${pos.horario.substring(0, 8)}`;
          this.posicaoMap.set(key, pos.id);
        });
        console.log('Mapa de posições (admin visualização):', Array.from(this.posicaoMap.entries()));
      } catch (error) {
        console.error('Erro ao carregar posições da semana:', error);
      }
    },
    getPosicaoSemanaId(diaNomeCompleto, turno) {
      const horario = turno === 1 ? '19:00:00' : '20:45:00';
      const key = `${diaNomeCompleto}|${horario}`;
      return this.posicaoMap.get(key) || null;
    },
    inicializarGradeVazia() {
      const diasNomes = ['Segunda-Feira', 'Terca-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira'];
      this.gradePrimeiroTurno = diasNomes.map((dia, idx) => ({
        id: `primeiro-${idx}`,
        posicaoSemanaId: this.getPosicaoSemanaId(dia, 1),
        horario: null
      }));
      this.gradeSegundoTurno = diasNomes.map((dia, idx) => ({
        id: `segundo-${idx}`,
        posicaoSemanaId: this.getPosicaoSemanaId(dia, 2),
        horario: null
      }));
    },
    async carregarHorariosPorTurma() {
      if (!this.selectedTurma) {
        this.inicializarGradeVazia();
        return;
      }
      try {
        const [horariosRes, aulasRes, salasRes] = await Promise.all([
          this.api.get(`/api/horarios/por-turma/${this.selectedTurma}`),
          this.api.get('/api/aulas'),
          this.api.get('/api/salas')
        ]);
        const horarios = horariosRes.data;
        const aulas = aulasRes.data;
        const salas = salasRes.data;

        this.inicializarGradeVazia();

        for (const h of horarios) {
          if (h.posicaoSemana_id !== null) {
            const aula = aulas.find(a => a.id === h.aula_id);
            const sala = salas.find(s => s.id === h.sala_id);
            const horarioObj = {
              id: h.id,
              nomeUc: aula?.uc?.nomeUc || 'UC não encontrada',
              professor: aula?.professor?.nome || 'Professor não encontrado',
              sala: sala?.nomeSala || 'Sala não encontrada'
            };
            let encontrado = false;
            for (let i = 0; i < this.gradePrimeiroTurno.length; i++) {
              if (this.gradePrimeiroTurno[i].posicaoSemanaId === h.posicaoSemana_id) {
                this.gradePrimeiroTurno[i].horario = horarioObj;
                encontrado = true;
                break;
              }
            }
            if (!encontrado) {
              for (let i = 0; i < this.gradeSegundoTurno.length; i++) {
                if (this.gradeSegundoTurno[i].posicaoSemanaId === h.posicaoSemana_id) {
                  this.gradeSegundoTurno[i].horario = horarioObj;
                  encontrado = true;
                  break;
                }
              }
            }
            if (!encontrado) {
              console.warn(`Posição ${h.posicaoSemana_id} não corresponde a nenhum slot.`);
            }
          }
        }
      } catch (error) {
        console.error('Erro ao carregar horários:', error);
        this.inicializarGradeVazia();
      }
    },
    navegar(item) {
      this.activeMenu = item.id;
      if (item.rota) {
        this.$router.push(item.rota).catch(() => {});
      }
    },
    criarNovoHorario() {
      this.$router.push('/PainelCriaHorarios').catch(() => {});
    },
    sair() {
      localStorage.removeItem('usuario');
      this.$router.push('/');
    }
  }
};
</script>