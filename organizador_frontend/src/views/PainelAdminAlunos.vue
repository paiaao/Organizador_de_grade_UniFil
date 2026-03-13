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
          <h1 class="text-lg font-bold text-stone-800">Alunos</h1>
          <p class="text-xs text-stone-500">Gerenciamento de alunos</p>
        </div>
        <select
          v-model="selectedAluno"
          @change="carregarAlunoSelecionado"
          class="border border-stone-300 rounded-lg px-4 py-2 text-sm text-stone-700 focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white"
        >
          <option value="">Selecione um aluno</option>
          <option v-for="aluno in alunos" :key="aluno.id" :value="aluno.id">
            {{ aluno.nome }}
          </option>
        </select>
      </div>

      <!-- Card do aluno -->
      <div class="flex-1 overflow-auto p-8 flex items-start justify-center">
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-8 w-full max-w-md">
          <!-- Avatar -->
          <div class="flex flex-col items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center mb-3">
              <svg class="w-8 h-8 text-[#ff9421]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <h2 class="text-base font-bold text-stone-800">{{ alunoSelecionado.nome || 'Selecione um aluno' }}</h2>
            <p class="text-xs text-stone-500">Aluno</p>
          </div>

          <div class="space-y-4">
            <!-- Nome -->
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Nome</label>
              <div class="flex items-center gap-2">
                <input
                  v-if="editando.nome"
                  v-model="alunoEditado.nome"
                  ref="nomeInput"
                  type="text"
                  class="flex-1 border border-stone-300 rounded-lg px-3 py-2 text-stone-800 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                  @blur="salvarCampo('nome')"
                  @keyup.enter="salvarCampo('nome')"
                  @keyup.esc="cancelarEdicao('nome')"
                />
                <span v-else class="flex-1 px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                  {{ alunoSelecionado.nome || '—' }}
                </span>
                <button @click="toggleEdicao('nome')" :disabled="!selectedAluno" class="p-2 rounded-lg hover:bg-orange-50 text-stone-400 hover:text-[#ff9421] transition-all disabled:opacity-30">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Matrícula -->
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Matrícula</label>
              <div class="flex items-center gap-2">
                <input
                  v-if="editando.matricula"
                  v-model="alunoEditado.matricula"
                  ref="matriculaInput"
                  type="text"
                  class="flex-1 border border-stone-300 rounded-lg px-3 py-2 text-stone-800 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                  @blur="salvarCampo('matricula')"
                  @keyup.enter="salvarCampo('matricula')"
                  @keyup.esc="cancelarEdicao('matricula')"
                />
                <span v-else class="flex-1 px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                  {{ alunoSelecionado.matricula || '—' }}
                </span>
                <button @click="toggleEdicao('matricula')" :disabled="!selectedAluno" class="p-2 rounded-lg hover:bg-orange-50 text-stone-400 hover:text-[#ff9421] transition-all disabled:opacity-30">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Turma -->
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Turma</label>
              <div class="flex items-center gap-2">
                <select
                  v-if="editando.turma"
                  v-model="alunoEditado.turma_id"
                  ref="turmaInput"
                  class="flex-1 border border-stone-300 rounded-lg px-3 py-2 text-stone-800 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent bg-white"
                  @blur="salvarCampo('turma')"
                  @keyup.esc="cancelarEdicao('turma')"
                >
                  <option value="">Selecione uma turma</option>
                  <option v-for="turma in turmas" :key="turma.id" :value="turma.id">
                    {{ turma.turmaRepresentante }}
                  </option>
                </select>
                <span v-else class="flex-1 px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                  {{ alunoSelecionado.turma?.turmaRepresentante || 'Nenhuma turma' }}
                </span>
                <button @click="toggleEdicao('turma')" :disabled="!selectedAluno" class="p-2 rounded-lg hover:bg-orange-50 text-stone-400 hover:text-[#ff9421] transition-all disabled:opacity-30">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Senha -->
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Senha</label>
              <div class="flex items-center gap-2">
                <input
                  v-if="editando.senha"
                  v-model="alunoEditado.senhaHash"
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
                <button @click="toggleEdicao('senha')" :disabled="!selectedAluno" class="p-2 rounded-lg hover:bg-orange-50 text-stone-400 hover:text-[#ff9421] transition-all disabled:opacity-30">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </div>
              <p v-if="editando.senha && alunoEditado.senhaHash.length > 0 && alunoEditado.senhaHash.length < 6"
                 class="text-red-500 text-xs mt-1">A senha deve ter pelo menos 6 caracteres</p>
            </div>

            <!-- Entrada -->
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Entrada</label>
              <div class="flex items-center gap-2">
                <input
                  v-if="editando.entrada"
                  v-model="alunoEditado.entrada"
                  ref="entradaInput"
                  type="date"
                  class="flex-1 border border-stone-300 rounded-lg px-3 py-2 text-stone-800 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                  @blur="salvarCampo('entrada')"
                  @keyup.esc="cancelarEdicao('entrada')"
                />
                <span v-else class="flex-1 px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                  {{ formatarData(alunoSelecionado.entrada) || '—' }}
                </span>
                <button @click="toggleEdicao('entrada')" :disabled="!selectedAluno" class="p-2 rounded-lg hover:bg-orange-50 text-stone-400 hover:text-[#ff9421] transition-all disabled:opacity-30">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Ação de deletar -->
          <div class="mt-6 pt-4 border-t border-stone-100">
            <button
              @click="deletarAluno"
              :disabled="!selectedAluno"
              class="flex items-center gap-2 text-red-500 hover:text-red-700 text-sm font-medium transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              Deletar aluno
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PainelAdminAlunos',
  data() {
    return {
      activeMenu: 'alunos',
      selectedAluno: '',
      alunos: [],
      turmas: [],
      alunoSelecionado: {},
      alunoEditado: {
        nome: '',
        matricula: '',
        turma_id: '',
        senhaHash: '',
        entrada: ''
      },
      editando: {
        nome: false,
        matricula: false,
        turma: false,
        senha: false,
        entrada: false
      },
      salvando: false,
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

    await this.carregarAlunos();
    await this.carregarTurmas();
  },
  methods: {
    async carregarAlunos() {
      try {
        const response = await this.api.get('/api/usuarios');
        this.alunos = response.data.filter(user => user.permissao === 3);
      } catch (error) {}
    },

    async carregarTurmas() {
      try {
        const response = await this.api.get('/api/turmas');
        this.turmas = response.data;
      } catch (error) {}
    },

    async carregarAlunoSelecionado() {
      if (!this.selectedAluno) {
        this.alunoSelecionado = {};
        return;
      }
      try {
        const response = await this.api.get(`/api/usuarios/${this.selectedAluno}`);
        this.alunoSelecionado = response.data;
        this.alunoEditado = {
          nome: response.data.nome,
          matricula: response.data.matricula || '',
          turma_id: response.data.turma_id || '',
          senhaHash: '',
          entrada: response.data.entrada.split('T')[0]
        };
      } catch (error) {}
    },

    async deletarAluno() {
      if (!this.selectedAluno) return;
      const aluno = this.alunos.find(a => a.id === this.selectedAluno);
      if (!aluno) return;
      if (!confirm(`Tem certeza que deseja deletar o aluno "${aluno.nome}"? Esta ação não pode ser desfeita.`)) return;
      try {
        await this.api.delete(`/api/usuarios/${this.selectedAluno}`);
        await this.carregarAlunos();
        this.selectedAluno = '';
        this.alunoSelecionado = {};
        this.alunoEditado = { nome: '', matricula: '', turma_id: '', senhaHash: '', entrada: '' };
      } catch (error) {}
    },

    toggleEdicao(campo) {
      if (this.salvando) return;
      Object.keys(this.editando).forEach(key => { this.editando[key] = false; });
      this.editando[campo] = true;
      this.$nextTick(() => {
        const ref = this.$refs[`${campo}Input`];
        if (ref) ref.focus();
      });
    },

    cancelarEdicao(campo) {
      this.editando[campo] = false;
      if (campo === 'nome') this.alunoEditado.nome = this.alunoSelecionado.nome;
      else if (campo === 'matricula') this.alunoEditado.matricula = this.alunoSelecionado.matricula;
      else if (campo === 'turma') this.alunoEditado.turma_id = this.alunoSelecionado.turma_id;
      else if (campo === 'senha') this.alunoEditado.senhaHash = '';
      else if (campo === 'entrada') this.alunoEditado.entrada = this.alunoSelecionado.entrada.split('T')[0];
    },

    async salvarCampo(campo) {
      if (this.salvando || !this.selectedAluno) return;
      if (campo === 'senha' && this.alunoEditado.senhaHash && this.alunoEditado.senhaHash.length < 6) {
        this.$refs.senhaInput.focus();
        return;
      }
      this.salvando = true;
      try {
        const dados = {};
        if (campo === 'nome') dados.nome = this.alunoEditado.nome;
        else if (campo === 'matricula') dados.matricula = this.alunoEditado.matricula;
        else if (campo === 'turma') dados.turma_id = this.alunoEditado.turma_id || null;
        else if (campo === 'senha' && this.alunoEditado.senhaHash) dados.senhaHash = this.alunoEditado.senhaHash;
        else if (campo === 'entrada') dados.entrada = this.alunoEditado.entrada;
        if (Object.keys(dados).length > 0) {
          const response = await this.api.put(`/api/usuarios/${this.selectedAluno}`, dados);
          this.alunoSelecionado = response.data;
        }
        this.editando[campo] = false;
        this.alunoEditado.senhaHash = '';
      } catch (error) {
        this.cancelarEdicao(campo);
      } finally {
        this.salvando = false;
      }
    },

    formatarData(data) {
      if (!data) return '';
      return new Date(data).toLocaleDateString('pt-BR');
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
