<template>
  <div class="flex h-screen bg-gray-100">
    <div class="w-64 bg-[#ff9421] flex flex-col">
      <div class="h-32 flex items-center justify-center overflow-hidden relative">
        <img src="/logo-unifil.png" alt="UniFil" class="min-w-[1000px] h-auto scale-125" />
        <hr class="absolute bottom-2 left-4 right-4 border-white border-t-2">
      </div>

      <nav class="flex-1 px-4 space-y-4 mt-2">
        <button 
          v-for="item in menuItems" 
          :key="item.id"
          @click="navegar(item)"
          :class="[
            'w-full px-4 py-3 text-left text-white font-medium rounded-lg text-sm transition-all',
            activeMenu === item.id ? 'bg-[#5a5a5a]' : 'bg-[#757575] hover:bg-[#858585]'
          ]"
        >
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
      <div class="flex justify-end mb-6">
        <button @click="criarSala" class="bg-[#ffac26] text-white px-10 py-2.5 rounded-full text-sm font-medium hover:bg-orange-600 transition shadow-md">
          Criar nova Sala
        </button>
      </div>

      <div class="bg-[#ffeccf] rounded-xl p-8 shadow-sm">
        <div class="overflow-x-auto">
          <table class="w-full" style="table-layout: fixed;">
            <thead class="border-b-2 border-black">
              <tr>
                <th class="text-left py-3 px-4 font-bold text-gray-800 w-32 "></th>
                <th class="text-left py-3 px-4 font-bold text-gray-800 ">Docente</th>
                <th class="text-left py-3 px-4 font-bold text-gray-800">Carga Horaria</th>
                <th class="text-left py-3 px-4 font-bold text-gray-800">Codigo</th>
                <th class="text-left py-3 px-4 font-bold text-gray-800">Tipo</th>
              </tr>
            </thead>
            <tbody>
              <tr 
                v-for="(sala, index) in salas" 
                :key="index"
                @click="editarSala(index)"
                class="border-t border-gray-300 cursor-pointer hover:bg-[#ffdead] transition"
              >
                <td class="py-3 px-4 text-gray-700">{{ sala.nome }}</td>
                <td class="py-3 px-4 text-gray-700 border-l-2 border-black">{{ sala.docente }}</td>
                <td class="py-3 px-4 text-gray-500">{{ sala.cargaHoraria }}</td>
                <td class="py-3 px-4 text-gray-500">{{ sala.codigo }}</td>
                <td class="py-3 px-4 text-gray-500">{{ sala.tipo }}</td>
              </tr>
            </tbody>
          </table>
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
      menuItems: [
        { id: 'horarios', label: 'Horários', rota: '/PainelAdminHorarios' },
        { id: 'disciplinas', label: 'Disciplinas', rota: '/PainelAdminDisciplinas' },
        { id: 'salas', label: 'Salas', rota: '/PainelAdminSalas' },
        { id: 'turma', label: 'Turma', rota: '/PainelAdminTurmas' },
        { id: 'professores', label: 'Professores', rota: '/PainelAdminProfessores' },
        { id: 'admins', label: 'Admins', rota: '/PainelAdminAdmins' },
        { id: 'alunos', label: 'Alunos', rota: '/PainelAdminAlunos' }
      ],
      salas: [
        { nome: 'Sala 1', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 2', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 3', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 4', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 5', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 6', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 7', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 8', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 9', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 10', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 11', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 12', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 13', docente: '', cargaHoraria: '', codigo: '', tipo: '' },
        { nome: 'Sala 14', docente: '', cargaHoraria: '', codigo: '', tipo: '' }
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
    criarSala() {
      console.log('Criar nova Sala');

    },
    editarSala(index) {
      console.log(`Editar Sala ${index}`);

    },
    sair() {
      console.log('Sair do sistema');
      this.$router.push({ path: '/' }).catch(() => {});
    }
  }
};
</script>