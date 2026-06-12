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
        <button @click="sair" class="w-full flex items-center gap-3 px-4 py-2.5 text-white text-sm font-medium rounded-lg hover:bg-white/15 transition-all duration-150">
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
          <h1 class="text-lg font-bold text-stone-800">Criar Horários</h1>
          <p class="text-xs text-stone-500">Configure a grade horária da turma</p>
        </div>
        <div class="flex items-center gap-3">
          <select
            v-model="selectedTurma"
            @change="carregarHorariosDisponiveis"
            class="border border-stone-300 rounded-lg px-4 py-2 text-sm text-stone-700 focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white"
          >
            <option value="">Selecione uma turma</option>
            <option v-for="turma in turmas" :key="turma.id" :value="turma.id">{{ turma.nomeTurma }}</option>
          </select>
          <button @click="abrirModalCriarHorario" class="flex items-center gap-2 bg-[#ff9421] hover:bg-[#e06800] text-white px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Criar novo horário
          </button>
          <button @click="enviarGrade" :disabled="!selectedTurma" class="flex items-center gap-2 bg-[#ff9421] hover:bg-[#e06800] text-white px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Enviar
          </button>
        </div>
      </div>

      <div class="flex-1 overflow-auto p-8">
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 mb-6">
          <h3 class="text-sm font-semibold text-stone-700 mb-4">Horários Disponíveis</h3>
          <draggable
            v-model="horariosDisponiveis"
            :group="{ name: 'horarios', pull: 'clone', put: false }"
            item-key="id"
            class="grid grid-cols-5 gap-3 disponiveis-list"
            :sort="true"
            @start="onDragStart"
            @end="onEnd"
            :clone="cloneHorario"
          >
            <template #item="{ element: horario }">
              <div class="bg-orange-100 border border-orange-200 rounded-lg h-28 shadow-sm cursor-move hover:bg-orange-200 transition-all duration-150 flex flex-col items-center justify-center p-2">
                <span class="text-xs font-bold text-stone-800">{{ horario.nomeUc }}</span>
                <span class="text-xs text-stone-600">{{ horario.professor }}</span>
                <span class="text-xs text-stone-600">{{ horario.sala }}</span>
              </div>
            </template>
          </draggable>
          <div v-if="horariosDisponiveis.length === 0" class="text-center text-stone-500 py-8">
            Nenhum horário disponível. Crie um novo horário.
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6">
          <h3 class="text-sm font-semibold text-stone-700 mb-4">Grade de Horários</h3>

          <div class="grid grid-cols-5 gap-3 mb-3">
            <div v-for="dia in diasSemana" :key="dia" class="bg-[#ff9421] text-white py-2.5 rounded-lg text-center font-semibold text-xs tracking-wide">
              {{ dia }}
            </div>
          </div>

          <draggable
            v-model="gradeSlots"
            :group="{ name: 'horarios', pull: true, put: true }"
            item-key="id"
            class="grid grid-cols-5 gap-y-3 gap-x-3 grade-grid"
            :sort="false"
            @start="onDragStart"
            @end="onEnd"
          >
            <template #item="{ element: slot }">
              <div class="bg-stone-50 border border-stone-200 rounded-lg h-28 transition-all duration-150 flex items-center justify-center p-3">
                <div v-if="slot.horario" class="text-xs text-center text-stone-700 font-medium leading-relaxed">
                  <div>{{ slot.horario.nomeUc }}</div>
                  <div class="text-stone-500">{{ slot.horario.professor }}</div>
                  <div class="text-stone-500">{{ slot.horario.sala }}</div>
                </div>
                <span v-else class="text-stone-300 text-xs">Arraste um horário</span>
              </div>
            </template>
          </draggable>

          <div class="space-y-2 mt-4">
            <div v-for="(ucd, index) in ucds" :key="`ucd-${index}`" class="bg-stone-50 border border-stone-200 rounded-lg px-5 py-4 cursor-pointer hover:bg-orange-50 hover:border-orange-300 transition-all duration-150 flex items-center gap-4">
              <span class="font-semibold text-stone-700 text-sm">{{ ucd.label }}</span>
              <span v-if="ucd.conteudo" class="text-sm text-stone-500">{{ ucd.conteudo }}</span>
              <span v-else class="text-stone-300 text-sm">Clique para editar</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showModalHorario" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 backdrop-blur-sm" @click="fecharModalHorario">
      <div class="bg-white rounded-2xl p-8 w-[560px] shadow-2xl border border-stone-100" @click.stop>
        <h3 class="text-xl font-bold text-stone-800 mb-1">Novo Horário</h3>
        <p class="text-stone-500 text-sm mb-6">Selecione os dados para criar um horário disponível</p>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Turma <span class="text-red-500">*</span></label>
            <select v-model="novoHorario.turma_id" class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
              <option :value="null" disabled>Selecione a turma</option>
              <option v-for="turma in turmas" :key="turma.id" :value="turma.id">{{ turma.nomeTurma }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Aula (UC + Professor) <span class="text-red-500">*</span></label>
            <select v-model="novoHorario.aula_id" @change="carregarDadosAula" class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
              <option :value="null" disabled>Selecione a aula</option>
              <option v-for="aula in aulas" :key="aula.id" :value="aula.id">{{ aula.uc?.nomeUc }} - {{ aula.professor?.nome }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">Sala <span class="text-red-500">*</span></label>
            <select v-model="novoHorario.sala_id" class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
              <option :value="null" disabled>Selecione a sala</option>
              <option v-for="sala in salas" :key="sala.id" :value="sala.id">{{ sala.nomeSala }} (Cap.: {{ sala.tamanho }})</option>
            </select>
          </div>
        </div>
        <div class="mt-6 flex gap-3">
          <button @click="fecharModalHorario" class="flex-1 py-3 border border-stone-300 text-stone-700 font-medium rounded-lg hover:bg-stone-50 transition text-sm">Cancelar</button>
          <button @click="salvarNovoHorario" :disabled="!novoHorario.turma_id || !novoHorario.aula_id || !novoHorario.sala_id" class="flex-1 py-3 bg-[#ff9421] hover:bg-[#e06800] text-white font-semibold rounded-lg transition text-sm disabled:opacity-50 disabled:cursor-not-allowed">Criar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import draggable from 'vuedraggable'

export default {
  name: 'PainelCriaHorarios',
  components: { draggable },
  data() {
    return {
      activeMenu: 'horarios',
      selectedTurma: '',
      turmas: [],
      aulas: [],
      salas: [],
      posicoesSemana: [],
      posicaoMap: new Map(),
      horariosDisponiveis: [],
      gradeSlots: [],
      draggedContext: null,
      showModalHorario: false,
      novoHorario: {
        turma_id: null,
        aula_id: null,
        sala_id: null,
        professor_id: null
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
      diasSemana: ['SEG', 'TER', 'QUA', 'QUI', 'SEX'],
      ucds: [
        { label: 'UCD', conteudo: '' },
        { label: 'UCD', conteudo: '' }
      ],
      api: null
    }
  },
  async mounted() {
    this.api = this.$root.$api || this.$api
    const usuario = JSON.parse(localStorage.getItem('usuario'))
    if (!usuario || usuario.permissao !== 1) {
      this.$router.push('/loginAdmin')
      return
    }
    await Promise.all([
      this.carregarTurmas(),
      this.carregarAulas(),
      this.carregarSalas(),
      this.carregarPosicoesSemana()
    ])
    this.initGradeSlots()
  },
  methods: {
    async carregarTurmas() {
      try {
        const res = await this.api.get('/api/turmas')
        this.turmas = res.data
      } catch (error) { console.error(error) }
    },
    async carregarAulas() {
      try {
        const res = await this.api.get('/api/aulas')
        this.aulas = res.data
      } catch (error) { console.error(error) }
    },
    async carregarSalas() {
      try {
        const res = await this.api.get('/api/salas')
        this.salas = res.data
      } catch (error) { console.error(error) }
    },
    async carregarPosicoesSemana() {
      try {
        const res = await this.api.get('/api/posicaoSemana')
        this.posicoesSemana = res.data
        this.posicaoMap.clear()
        this.posicoesSemana.forEach(pos => {
          const key = `${pos.diaSemana}|${pos.horario.substring(0, 8)}`
          this.posicaoMap.set(key, pos.id)
        })
        console.log('Mapa de posições:', Array.from(this.posicaoMap.entries()))
      } catch (error) { console.error(error) }
    },
    getPosicaoSemanaId(diaNomeCompleto, turno) {
      const horario = turno === 1 ? '19:00:00' : '20:45:00'
      const key = `${diaNomeCompleto}|${horario}`
      return this.posicaoMap.get(key) || null
    },
    initGradeSlots() {
      const diasNomes = ['Segunda-Feira', 'Terca-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira']
      const slots = []
      for (let i = 0; i < diasNomes.length; i++) {
        slots.push({
          id: `slot-1-${i}`,
          posicaoSemanaId: this.getPosicaoSemanaId(diasNomes[i], 1),
          horario: null
        })
      }
      for (let i = 0; i < diasNomes.length; i++) {
        slots.push({
          id: `slot-2-${i}`,
          posicaoSemanaId: this.getPosicaoSemanaId(diasNomes[i], 2),
          horario: null
        })
      }
      this.gradeSlots = slots
    },
    async carregarHorariosDisponiveis() {
      if (!this.selectedTurma) {
        this.horariosDisponiveis = []
        this.initGradeSlots()
        return
      }
      try {
        const res = await this.api.get(`/api/horarios/por-turma/${this.selectedTurma}`)
        const todos = res.data
        console.log('Horários carregados:', todos)

        const slots = [...this.gradeSlots]
        for (let i = 0; i < slots.length; i++) {
          slots[i].horario = null
        }

        const disponiveis = []
        for (const h of todos) {
          const aula = this.aulas.find(a => a.id === h.aula_id)
          const sala = this.salas.find(s => s.id === h.sala_id)
          const horario = {
            id: h.id,
            turma_id: h.turma_id,
            aula_id: h.aula_id,
            sala_id: h.sala_id,
            professor_id: h.professor_id,
            posicaoSemana_id: h.posicaoSemana_id,
            nomeUc: aula?.uc?.nomeUc || 'Sem UC cadastrada',
            professor: aula?.professor?.nome || 'Sem professor cadastrado',
            sala: sala?.nomeSala || 'Sem sala cadastrada'
          }

          if (h.posicaoSemana_id === null) {
            disponiveis.push(horario)
          } else {
            const slotIndex = slots.findIndex(s => s.posicaoSemanaId === h.posicaoSemana_id)
            if (slotIndex !== -1) {
              slots[slotIndex].horario = horario
            } else {
              console.warn(`Posição ${h.posicaoSemana_id} não encontrada`)
              disponiveis.push(horario)
            }
          }
        }
        this.horariosDisponiveis = disponiveis
        this.gradeSlots = slots
      } catch (error) {
        console.error('Erro ao carregar horários:', error)
        this.horariosDisponiveis = []
        this.initGradeSlots()
      }
    },
    carregarDadosAula() {
      const aula = this.aulas.find(a => a.id === this.novoHorario.aula_id)
      if (aula) this.novoHorario.professor_id = aula.professor_id
    },
    abrirModalCriarHorario() {
      if (!this.selectedTurma) {
        alert('Selecione uma turma antes de criar um horário.')
        return
      }
      this.novoHorario = {
        turma_id: this.selectedTurma,
        aula_id: null,
        sala_id: null,
        professor_id: null
      }
      this.showModalHorario = true
    },
    fecharModalHorario() {
      this.showModalHorario = false
    },
    async salvarNovoHorario() {
      if (!this.novoHorario.turma_id || !this.novoHorario.aula_id || !this.novoHorario.sala_id) return
      const payload = {
        turma_id: this.novoHorario.turma_id,
        aula_id: this.novoHorario.aula_id,
        sala_id: this.novoHorario.sala_id,
        professor_id: this.novoHorario.professor_id,
        posicaoSemana_id: null
      }
      try {
        await this.api.post('/api/horarios', payload)
        await this.carregarHorariosDisponiveis()
        this.fecharModalHorario()
      } catch (error) {
        console.error(error)
        alert('Erro ao criar horário')
      }
    },
    cloneHorario(horario) {
      return JSON.parse(JSON.stringify(horario))
    },
    onDragStart(event) {
      const context = event.item.__draggable_context
      if (!context) return
      this.draggedContext = {
        element: context.element,
        index: context.index
      }
    },
    onEnd(event) {                            
      if (!this.draggedContext) return

      const corrigirGradeSlots = () => {
        const slotsOrdenados = []
        for (let i = 0; i < 5; i++) {
          const slotId = `slot-1-${i}`
          let slotExistente = this.gradeSlots.find(s => s.id === slotId)
          if (!slotExistente) {
            const diasNomes = ['Segunda-Feira', 'Terca-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira']
            slotExistente = {
              id: slotId,
              posicaoSemanaId: this.getPosicaoSemanaId(diasNomes[i], 1),
              horario: null
            }
          }
          slotsOrdenados.push(slotExistente)
        }
        for (let i = 0; i < 5; i++) {
          const slotId = `slot-2-${i}`
          let slotExistente = this.gradeSlots.find(s => s.id === slotId)
          if (!slotExistente) {
            const diasNomes = ['Segunda-Feira', 'Terca-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira']
            slotExistente = {
              id: slotId,
              posicaoSemanaId: this.getPosicaoSemanaId(diasNomes[i], 2),
              horario: null
            }
          }
          slotsOrdenados.push(slotExistente)
        }
        this.gradeSlots = slotsOrdenados
      }

      corrigirGradeSlots()

      const { element: draggedElement, index: fromIndex } = this.draggedContext
      const toElement = event.to
      let toType = null
      let toIndex = event.newIndex

      if (toElement.classList.contains('disponiveis-list')) {
        toType = 'disponiveis'
      } else if (toElement.classList.contains('grade-grid')) {
        toType = 'grade'
      }

      if (!toType || toIndex === undefined) {
        this.draggedContext = null
        return
      }

      const isFromDisponiveis = draggedElement && draggedElement.id && this.horariosDisponiveis.some(h => h.id === draggedElement.id)
      const isFromGrade = !isFromDisponiveis && draggedElement && draggedElement.horario && draggedElement.horario.id

      let newGrade = [...this.gradeSlots]
      let newDisponiveis = [...this.horariosDisponiveis]

      if (isFromDisponiveis && toType === 'grade') {
        const targetSlot = newGrade[toIndex]
        if (!targetSlot) return

        if (targetSlot.horario && targetSlot.horario.id) {
          const horarioAntigo = targetSlot.horario
          horarioAntigo.posicaoSemana_id = null
          newDisponiveis.push(horarioAntigo)
        }

        const novoHorario = this.cloneHorario(draggedElement)
        novoHorario.posicaoSemana_id = targetSlot.posicaoSemanaId
        newGrade[toIndex] = { ...targetSlot, horario: novoHorario }

        const originalIndex = newDisponiveis.findIndex(h => h.id === draggedElement.id)
        if (originalIndex !== -1) {
          newDisponiveis.splice(originalIndex, 1)
        }
      }
      else if (isFromGrade && toType === 'grade') {
        const fromSlot = newGrade[fromIndex]
        const toSlot = newGrade[toIndex]
        if (!fromSlot || !fromSlot.horario) return

        const horarioOrigem = this.cloneHorario(fromSlot.horario)

        if (toSlot.horario && toSlot.horario.id) {
          const horarioDestinoAntigo = toSlot.horario
          horarioDestinoAntigo.posicaoSemana_id = null
          newDisponiveis.push(horarioDestinoAntigo)
        }

        horarioOrigem.posicaoSemana_id = toSlot.posicaoSemanaId
        newGrade[toIndex] = { ...toSlot, horario: horarioOrigem }
        newGrade[fromIndex] = { ...fromSlot, horario: null }
      }
      else if (isFromGrade && toType === 'disponiveis') {
        const fromSlot = newGrade[fromIndex]
        if (!fromSlot || !fromSlot.horario) return

        const horarioMovido = this.cloneHorario(fromSlot.horario)
        horarioMovido.posicaoSemana_id = null
        newDisponiveis.splice(toIndex, 0, horarioMovido)
        newGrade[fromIndex] = { ...fromSlot, horario: null }
      }

      this.gradeSlots = newGrade
      this.horariosDisponiveis = newDisponiveis

      this.$nextTick(() => {
        corrigirGradeSlots()
      })

      this.draggedContext = null
    },
    async enviarGrade() {
      if (!this.selectedTurma) {
        alert('Selecione uma turma')
        return
      }

      const updates = []

      for (const slot of this.gradeSlots) {
        if (slot.horario && slot.horario.id) {
          updates.push({
            id: slot.horario.id,
            posicaoSemana_id: slot.posicaoSemanaId
          })
        }
      }

      for (const h of this.horariosDisponiveis) {
        if (h.id) {
          updates.push({
            id: h.id,
            posicaoSemana_id: null
          })
        }
      }

      if (updates.length === 0) {
        alert('Nenhum horário para salvar')
        return
      }

      console.log('Enviando todos os horários:', updates)

      try {
        for (const upd of updates) {
          await this.api.put(`/api/horarios/${upd.id}`, { posicaoSemana_id: upd.posicaoSemana_id })
        }
        alert('Horários salvos com sucesso!')
        await this.carregarHorariosDisponiveis()
      } catch (error) {
        console.error('Erro ao salvar:', error)
        if (error.response) {
          const msg = error.response.data.message || JSON.stringify(error.response.data.errors)
          alert(`Erro ao salvar: ${msg}`)
        } else {
          alert('Erro de conexão com o servidor')
        }
      }
    },
    navegar(item) {
      this.activeMenu = item.id
      if (item.rota) this.$router.push(item.rota).catch(() => {})
    },
    sair() {
      localStorage.removeItem('usuario')
      this.$router.push('/').catch(() => {})
    }
  }
}
</script>

<style scoped>
.disponiveis-list {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 0.75rem;
}
.grade-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 0.75rem;
}
</style>

<style>
.sortable-ghost,
.drag-ghost-hidden {
  display: none !important;
  opacity: 0 !important;
  visibility: hidden !important;
  width: 0 !important;
  height: 0 !important;
}
</style>