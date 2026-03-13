<template>
  <div class="flex h-screen bg-stone-50">
    <!-- Sidebar -->
    <div class="w-60 bg-[#ff9421] flex flex-col shadow-lg flex-shrink-0">
      <div class="h-20 flex items-center justify-center px-4 border-b border-white/20">
        <img src="/logo-unifil.png" alt="UniFil" class="h-10 w-auto object-contain" />
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

    <!-- Conteúdo -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <div class="bg-white border-b border-stone-200 px-8 py-4 flex items-center justify-between">
        <div>
          <h1 class="text-lg font-bold text-stone-800">Salas</h1>
          <p class="text-xs text-stone-500">Gerenciamento de salas</p>
        </div>
        <div class="flex items-center gap-3">
          <select
            v-model="selectedTurma"
            class="border border-stone-300 rounded-lg px-4 py-2 text-sm text-stone-700 focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white"
          >
            <option value="">Selecione uma turma</option>
            <option v-for="turma in turmas" :key="turma.id" :value="turma.id">
              {{ turma.nome }}
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

      <!-- Grade -->
      <div class="flex-1 overflow-auto p-8">
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6">
          <div class="grid grid-cols-6 gap-3 mb-3">
            <div
              v-for="dia in diasSemana"
              :key="dia"
              class="bg-[#ff9421] text-white py-2.5 rounded-lg text-center font-semibold text-xs tracking-wide"
            >
              {{ dia }}
            </div>
          </div>

          <div class="grid grid-cols-6 gap-3 mb-3">
            <div
              v-for="(slot, index) in 6"
              :key="`slot1-${index}`"
              @click="editarHorario(1, index)"
              class="bg-stone-50 border border-stone-200 rounded-lg h-28 cursor-pointer hover:bg-orange-50 hover:border-orange-300 transition-all duration-150 flex items-center justify-center p-3"
            >
              <span v-if="horarios[1]?.[index]" class="text-xs text-center text-stone-700 font-medium leading-relaxed">
                {{ horarios[1][index] }}
              </span>
              <span v-else class="text-stone-300 text-xs">—</span>
            </div>
          </div>

          <div class="grid grid-cols-6 gap-3 mb-6">
            <div
              v-for="(slot, index) in 6"
              :key="`slot2-${index}`"
              @click="editarHorario(2, index)"
              class="bg-stone-50 border border-stone-200 rounded-lg h-28 cursor-pointer hover:bg-orange-50 hover:border-orange-300 transition-all duration-150 flex items-center justify-center p-3"
            >
              <span v-if="horarios[2]?.[index]" class="text-xs text-center text-stone-700 font-medium leading-relaxed">
                {{ horarios[2][index] }}
              </span>
              <span v-else class="text-stone-300 text-xs">—</span>
            </div>
          </div>

          <div class="space-y-2">
            <div
              v-for="(ucd, index) in ucds"
              :key="`ucd-${index}`"
              @click="editarUCD(index)"
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
  name: 'PainelAdminSalas',
  data() {
    return {
      activeMenu: 'salas',
      selectedTurma: '',
      menuItems: [
        { id: 'horarios', label: 'Horários', rota: '/PainelAdminHorarios' },
        { id: 'disciplinas', label: 'Disciplinas', rota: '/PainelAdminDisciplinas' },
        { id: 'salas', label: 'Salas', rota: '/PainelAdminSalas' },
        { id: 'turma', label: 'Turma', rota: '/PainelAdminTurmas' },
        { id: 'professores', label: 'Professores', rota: '/PainelAdminProfessores' },
        { id: 'admins', label: 'Admins', rota: '/PainelAdminAdmins' },
        { id: 'alunos', label: 'Alunos', rota: '/PainelAdminAlunos' }
      ],
      diasSemana: ['SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB'],
      turmas: [
        { id: 1, nome: 'Turma A' },
        { id: 2, nome: 'Turma B' },
        { id: 3, nome: 'Turma C' }
      ],
      horarios: { 1: {}, 2: {} },
      ucds: [
        { label: 'UCD', conteudo: '' },
        { label: 'UCD', conteudo: '' }
      ]
    };
  },
  methods: {
    navegar(item) {
      this.activeMenu = item.id;
      if (item.rota) {
        this.$router.push(item.rota).catch(() => {});
      }
    },
    criarNovoHorario() { console.log('Criar novo horário'); },
    editarHorario(linha, dia) { console.log(`Editar horário - Linha: ${linha}, Dia: ${dia}`); },
    editarUCD(index) { console.log(`Editar UCD ${index}`); },
    sair() { this.$router.push({ path: '/' }).catch(() => {}); }
  }
};
</script>
