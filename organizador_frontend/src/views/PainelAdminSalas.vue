<template>
  <div class="flex h-screen bg-gray-100">
    <div class="w-64 bg-[#ff9421] flex flex-col">
      <div class="h-32 flex items-center justify-center overflow-hidden relative">
        <img src="/logo-unifil.png" alt="UniFil" class="min-w-[1000px] h-auto scale-125" />
        <hr class="absolute bottom-2 left-4 right-4 border-white border-t-2">
      </div>

      <nav class="flex-1 px-4 space-y-4 mt-2">
        <button v-for="item in menuItems":key="item.id"@click="navegar(item)":class="['w-full px-4 py-3 text-left text-white font-medium rounded-lg text-sm transition-all',
            activeMenu === item.id ? 'bg-[#5a5a5a]' : 'bg-[#757575] hover:bg-[#858585]']">
          
          {{ item.label }}
        </button>
      </nav>

      <div class="p-5 pb-6">
        <button @click="sair" class="hover:opacity-80 transition" title="Sair">
          <img src="/sair.png" alt="Sair" class="w-10 h-10" />
        </button>
      </div>
    </div>

    <div class="flex-1 p-8 overflow-auto">
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
          <select v-model="selectedTurma" class="bg-[#ffac26] text-white px-8 py-2.5 rounded text-sm font-medium cursor-pointer border-none outline-none">
            <option value="">
              Turma -
            </option>
            <option v-for="turma in turmas" :key="turma.id" :value="turma.id">
              {{ turma.nome }}
            </option>
          </select>
        </div>
        <button @click="criarNovoHorario" class="bg-[#ffac26] text-white px-10 py-2.5 rounded-full text-sm font-medium hover:bg-orange-600 transition shadow-md">
          Criar novo horário
        </button>
      </div>

      <div class="bg-[#ffeccf] rounded-xl p-8 shadow-sm">
        <div class="grid grid-cols-6 gap-5 mb-5">
          <div v-for="dia in diasSemana" :key="dia" class="bg-[#ffdead] p-3 rounded-lg text-center font-bold text-sm text-gray-800">
            {{ dia }}
          </div>
        </div>

        <div class="grid grid-cols-6 gap-5 mb-5">
          <div v-for="(slot, index) in 6" :key="`slot1-${index}`" @click="editarHorario(1, index)" class="bg-gray-300 rounded-lg h-36 cursor-pointer hover:bg-gray-400 transition flex items-center justify-center p-3">
            <span v-if="horarios[1]?.[index]" class="text-sm text-center text-gray-700 font-medium">
              {{ horarios[1][index] }}
            </span>
          </div>
        </div>

        <div class="grid grid-cols-6 gap-5 mb-8">
          <div v-for="(slot, index) in 6" :key="`slot2-${index}`" @click="editarHorario(2, index)" class="bg-gray-300 rounded-lg h-36 cursor-pointer hover:bg-gray-400 transition flex items-center justify-center p-3">
            <span v-if="horarios[2]?.[index]" class="text-sm text-center text-gray-700 font-medium">
              {{ horarios[2][index] }}
            </span>
          </div>
        </div>

        <div class="space-y-4">
          <div v-for="(ucd, index) in ucds" :key="`ucd-${index}`" @click="editarUCD(index)" class="bg-gray-300 rounded-lg p-5 cursor-pointer hover:bg-gray-400 transition">
            <span class="font-bold text-gray-800 text-sm">{{ ucd.label }}</span>
            <span v-if="ucd.conteudo" class="ml-4 text-sm text-gray-700">{{ ucd.conteudo }}</span>
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
      horarios: {
        1: {},
        2: {}
      },
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
    criarNovoHorario() {
      console.log('Criar novo horário');
    },
    editarHorario(linha, dia) {
      console.log(`Editar horário - Linha: ${linha}, Dia: ${dia}`);
    },
    editarUCD(index) {
      console.log(`Editar UCD ${index}`);
    },
    sair() {
      console.log('Sair do sistema');
      this.$router.push({ path: '/' }).catch(() => {});
    }
  }
};
</script>