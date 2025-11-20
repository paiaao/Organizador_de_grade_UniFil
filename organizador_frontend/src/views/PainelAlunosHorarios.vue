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
        <button @click="sair" class="hover:opacity-80 transition" title="sair">
          <img src="/sair.png" alt="Sair" class="w-10 h-10" />
        </button>
      </div>
    </div>

    <div class="flex-1 p-8 overflow-auto">
      <div class="flex items-center justify-end mb-6">
        <div class="flex justify-end flex-1">
          <button @click="exportarPDF" class="tracking-[7px] bg-[#ffac26] text-white px-10 py-2.5 rounded-full font-bold hover:bg-orange-600 transition shadow-md">
            Exportar PDF
          </button>
        </div>
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
  name: 'PainelAlunosHorarios',
  data() {
    return {
      activeMenu: 'horarios',
      menuItems: [
        { id: 'horarios', label: 'Horários', rota: '/PainelAlunosHorarios' },
        { id: 'perfil', label: 'Perfil', rota: '/PainelAlunosPerfil' },
      ],
      diasSemana: ['SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB'],
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
    exportarPDF() {
      console.log('exportarpdf');
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