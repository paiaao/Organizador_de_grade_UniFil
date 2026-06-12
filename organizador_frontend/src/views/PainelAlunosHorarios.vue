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
      <div class="bg-white border-b border-stone-200 px-8 py-4 flex items-center justify-between">
        <div>
          <h1 class="text-lg font-bold text-stone-800">Meus Horários</h1>
          <p class="text-xs text-stone-500">Grade de aulas semanal</p>
        </div>
        <button
          @click="exportarPDF"
          class="flex items-center gap-2 bg-[#ff9421] hover:bg-[#e06800] text-white px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Exportar PDF
        </button>
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
              class="bg-stone-50 border border-stone-200 rounded-lg px-5 py-4 flex items-center gap-4"
            >
              <span class="font-semibold text-stone-700 text-sm">{{ ucd.label }}</span>
              <span v-if="ucd.conteudo" class="text-sm text-stone-500">{{ ucd.conteudo }}</span>
              <span v-else class="text-stone-300 text-sm">Nenhum conteúdo disponível</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PainelAlunosHorarios',
  data() {
    return {
      activeMenu: 'horarios',
      menuItems: [
        { id: 'horarios', label: 'Horários', rota: '/PainelAlunosHorarios' },
        { id: 'perfil', label: 'Perfil', rota: '/PainelAlunosPerfil' }
      ],
      diasSemana: ['SEG', 'TER', 'QUA', 'QUI', 'SEX'],
      gradePrimeiroTurno: [],
      gradeSegundoTurno: [],
      posicoesSemana: [],
      posicaoMap: new Map(),
      ucds: [
        { label: 'UCD', conteudo: '' },
        { label: 'UCD', conteudo: '' }
      ],
      api: null
    };
  },
  async mounted() {
    this.api = this.$root.$api || this.$api;
    const usuario = JSON.parse(localStorage.getItem('usuario'));

    if (!usuario || usuario.permissao !== 3) {
      this.$router.push('/');
      return;
    }

    const turmaId = usuario.turma_id;
    if (!turmaId) {
      console.warn('Aluno não possui turma associada.');
      this.inicializarGradeVazia();
      return;
    }

    await this.carregarPosicoesSemana();
    await this.carregarHorarios(turmaId);
  },
  methods: {
    async carregarPosicoesSemana() {
      try {
        const res = await this.api.get('/api/posicaoSemana');
        this.posicoesSemana = res.data;
        this.posicaoMap.clear();
        this.posicoesSemana.forEach(pos => {
          const key = `${pos.diaSemana}|${pos.horario.substring(0, 8)}`;
          this.posicaoMap.set(key, pos.id);
        });
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
    async carregarHorarios(turmaId) {
      try {
        const [horariosRes, aulasRes, salasRes] = await Promise.all([
          this.api.get(`/api/horarios/por-turma/${turmaId}`),
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
    async exportarPDF() {
      await this.$nextTick();

      const originalElement = this.$el.querySelector('.bg-white.rounded-2xl');
      if (!originalElement) {
        console.error('Elemento da grade não encontrado');
        return;
      }

      const cloneElement = originalElement.cloneNode(true);

      const getHexFromClass = (className) => {
        const colorMap = {
          'bg-stone-50': '#fafaf9',
          'bg-stone-100': '#f5f5f4',
          'border-stone-200': '#e7e5e4',
          'border-stone-300': '#d6d3d1',
          'text-stone-700': '#44403c',
          'text-stone-500': '#78716c',
          'text-stone-300': '#d6d3d1',
          'bg-[#ff9421]': '#ff9421',
          'text-[#ff9421]': '#ff9421',
          'bg-white': '#ffffff',
          'text-white': '#ffffff',
          'border-stone-100': '#f5f5f4',
        };
        return colorMap[className] || null;
      };

      const applyInlineStyles = (node) => {
        if (!node.style) return;

        const classNames = node.className ? node.className.split(' ') : [];
        let bgColor = null, textColor = null, borderColor = null;

        for (const cls of classNames) {
          const hex = getHexFromClass(cls);
          if (hex) {
            if (cls.startsWith('bg-')) bgColor = hex;
            if (cls.startsWith('text-')) textColor = hex;
            if (cls.startsWith('border-')) borderColor = hex;
          }
        }

        if (bgColor) node.style.backgroundColor = bgColor;
        if (textColor) node.style.color = textColor;
        if (borderColor) node.style.borderColor = borderColor;

        node.style.breakInside = 'avoid';
        node.style.pageBreakInside = 'avoid';

        for (const child of node.children) {
          applyInlineStyles(child);
        }
      };

      applyInlineStyles(cloneElement);

      cloneElement.style.padding = '1rem';
      cloneElement.style.margin = '0';
      cloneElement.style.maxHeight = 'none';
      cloneElement.style.overflow = 'visible';
      cloneElement.style.backgroundColor = '#ffffff';

      const html2pdf = (await import('html2pdf.js')).default;

      const opt = {
        margin: [0.2, 0.2, 0.2, 0.2],
        filename: `horarios_${new Date().toISOString().slice(0, 10)}.pdf`,
        image: { type: 'jpeg', quality: 0.95 },
        html2canvas: { scale: 2, letterRendering: true, useCORS: true },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' },
        pagebreak: { mode: 'avoid-all' }
      };

      try {
        await html2pdf().set(opt).from(cloneElement).save();
      } catch (error) {
        console.error('Erro ao gerar PDF:', error);
      }
    },
    sair() {
      localStorage.removeItem('usuario');
      this.$router.push('/');
    }
  }
};
</script>

<style scoped>
.pdf-export .grid {
  break-inside: avoid;
  page-break-inside: avoid;
}
.pdf-export .bg-stone-50,
.pdf-export .border-stone-200 {
  break-inside: avoid;
  page-break-inside: avoid;
}
</style>