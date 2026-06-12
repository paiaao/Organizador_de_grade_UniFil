<template>
  <div class="flex h-screen bg-stone-50" @click="fecharModalFora">
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

    <div class="flex-1 flex flex-col overflow-hidden relative">
      <div class="bg-white border-b border-stone-200 px-8 py-4 flex items-center justify-between">
        <div>
          <h1 class="text-lg font-bold text-stone-800">Admins</h1>
          <p class="text-xs text-stone-500">Gerenciamento de administradores</p>
        </div>
        <div class="flex items-center gap-3">
          <select
            v-model="selectedAdmin"
            @change="carregarAdminSelecionado"
            class="border border-stone-300 rounded-lg px-4 py-2 text-sm text-stone-700 focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white"
          >
            <option value="">Selecione um admin</option>
            <option v-for="admin in admins" :key="admin.id" :value="admin.id">
              {{ admin.nome }}
            </option>
          </select>
          <button
            @click.stop="toggleModal"
            class="flex items-center gap-2 bg-[#ff9421] hover:bg-[#e06800] text-white px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo admin
          </button>
        </div>
      </div>

      <div class="flex-1 overflow-auto p-8 flex items-start justify-center">
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-8 w-full max-w-md">
          <div class="flex flex-col items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center mb-3">
              <svg class="w-8 h-8 text-[#ff9421]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <h2 class="text-base font-bold text-stone-800">{{ adminSelecionado.nome || 'Selecione um admin' }}</h2>
            <p class="text-xs text-stone-500">Administrador</p>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Nome</label>
              <div class="flex items-center gap-2">
                <input
                  v-if="editando.nome"
                  v-model="adminEditado.nome"
                  ref="nomeInput"
                  type="text"
                  class="flex-1 border border-stone-300 rounded-lg px-3 py-2 text-stone-800 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                  @blur="salvarCampo('nome')"
                  @keyup.enter="salvarCampo('nome')"
                  @keyup.esc="cancelarEdicao('nome')"
                />
                <span v-else class="flex-1 px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                  {{ adminSelecionado.nome || '—' }}
                </span>
                <button @click="toggleEdicao('nome')" :disabled="!selectedAdmin" class="p-2 rounded-lg hover:bg-orange-50 text-stone-400 hover:text-[#ff9421] transition-all disabled:opacity-30">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Senha</label>
              <div class="flex items-center gap-2">
                <input
                  v-if="editando.senha"
                  v-model="adminEditado.senhaHash"
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
                <button @click="toggleEdicao('senha')" :disabled="!selectedAdmin" class="p-2 rounded-lg hover:bg-orange-50 text-stone-400 hover:text-[#ff9421] transition-all disabled:opacity-30">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </div>
              <p v-if="editando.senha && adminEditado.senhaHash.length > 0 && adminEditado.senhaHash.length < 6"
                 class="text-red-500 text-xs mt-1">A senha deve ter pelo menos 6 caracteres</p>
            </div>

            <div>
              <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wide mb-1.5">Entrada</label>
              <div class="flex items-center gap-2">
                <input
                  v-if="editando.entrada"
                  v-model="adminEditado.entrada"
                  ref="entradaInput"
                  type="date"
                  class="flex-1 border border-stone-300 rounded-lg px-3 py-2 text-stone-800 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                  @blur="salvarCampo('entrada')"
                  @keyup.esc="cancelarEdicao('entrada')"
                />
                <span v-else class="flex-1 px-3 py-2 text-stone-800 text-sm bg-stone-50 rounded-lg border border-stone-200">
                  {{ formatarData(adminSelecionado.entrada) || '—' }}
                </span>
                <button @click="toggleEdicao('entrada')" :disabled="!selectedAdmin" class="p-2 rounded-lg hover:bg-orange-50 text-stone-400 hover:text-[#ff9421] transition-all disabled:opacity-30">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div class="mt-6 pt-4 border-t border-stone-100">
            <button
              @click="deletarAdmin"
              :disabled="!selectedAdmin"
              class="flex items-center gap-2 text-red-500 hover:text-red-700 text-sm font-medium transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              Deletar admin
            </button>
          </div>
        </div>
      </div>

      <div v-if="showModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 backdrop-blur-sm" @click="fecharModalFora">
        <div class="bg-white rounded-2xl p-8 w-96 shadow-2xl border border-stone-100" @click.stop>
          <h3 class="text-xl font-bold text-stone-800 mb-1">Novo administrador</h3>
          <p class="text-stone-500 text-sm mb-6">Preencha os dados para cadastrar</p>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-stone-700 mb-1.5">Nome <span class="text-red-500">*</span></label>
              <input
                v-model="novoAdmin.nome"
                type="text"
                placeholder="Nome do administrador"
                class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                @keyup.enter="criarNovoAdmin"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-stone-700 mb-1.5">Senha <span class="text-red-500">*</span></label>
              <input
                v-model="novoAdmin.senha"
                type="password"
                placeholder="Mínimo 6 caracteres"
                class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 placeholder-stone-400 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                @keyup.enter="criarNovoAdmin"
              />
              <p v-if="novoAdmin.senha && novoAdmin.senha.length < 6" class="text-red-500 text-xs mt-1">
                A senha deve ter pelo menos 6 caracteres
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-stone-700 mb-1.5">Data de entrada</label>
              <input
                v-model="novoAdmin.entrada"
                type="date"
                class="w-full border border-stone-300 rounded-lg px-4 py-3 text-stone-700 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
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
              @click="criarNovoAdmin"
              :disabled="!novoAdmin.nome || !novoAdmin.senha || novoAdmin.senha.length < 6"
              class="flex-1 py-3 bg-[#ff9421] hover:bg-[#e06800] text-white font-semibold rounded-lg transition text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Cadastrar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PainelAdminAdmins',
  data() {
    return {
      activeMenu: 'admins',
      selectedAdmin: '',
      admins: [],
      adminSelecionado: {},
      adminEditado: {
        nome: '',
        senhaHash: '',
        entrada: ''
      },
      editando: {
        nome: false,
        senha: false,
        entrada: false
      },
      salvando: false,
      showModal: false,
      novoAdmin: {
        nome: '',
        senha: '',
        entrada: new Date().toISOString().split('T')[0]
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
  async mounted() {
    this.api = this.$root.$api || this.$api;

    const usuario = JSON.parse(localStorage.getItem('usuario'));
    if (!usuario || usuario.permissao !== 1) {
      this.$router.push('/loginAdmin');
      return;
    }

    await this.carregarAdmins();
  },
  methods: {
    async carregarAdmins() {
      try {
        const response = await this.api.get('/api/usuarios');
        this.admins = response.data.filter(user => user.permissao === 1);
      } catch (error) {}
    },

    async carregarAdminSelecionado() {
      if (!this.selectedAdmin) {
        this.adminSelecionado = {};
        return;
      }
      try {
        const response = await this.api.get(`/api/usuarios/${this.selectedAdmin}`);
        this.adminSelecionado = response.data;
        this.adminEditado = {
          nome: response.data.nome,
          senhaHash: '',
          entrada: response.data.entrada.split('T')[0]
        };
      } catch (error) {}
    },

    toggleModal(event) {
      if (event) event.stopPropagation();
      this.showModal = !this.showModal;
      if (!this.showModal) this.limparNovoAdmin();
    },

    fecharModalFora() {
      if (this.showModal) {
        this.showModal = false;
        this.limparNovoAdmin();
      }
    },

    limparNovoAdmin() {
      this.novoAdmin = {
        nome: '',
        senha: '',
        entrada: new Date().toISOString().split('T')[0]
      };
    },

    async criarNovoAdmin() {
      if (!this.novoAdmin.nome || !this.novoAdmin.senha || this.novoAdmin.senha.length < 6) return;
      try {
        await this.api.post('/api/usuarios', {
          nome: this.novoAdmin.nome,
          senhaHash: this.novoAdmin.senha,
          permissao: 1,
          entrada: this.novoAdmin.entrada || new Date().toISOString().split('T')[0]
        });
        this.showModal = false;
        this.limparNovoAdmin();
        await this.carregarAdmins();
      } catch (error) {}
    },

    async deletarAdmin() {
      if (!this.selectedAdmin) return;
      const admin = this.admins.find(a => a.id === this.selectedAdmin);
      if (!admin) return;
      if (!confirm(`Tem certeza que deseja deletar o admin "${admin.nome}"? Esta ação não pode ser desfeita.`)) return;
      try {
        await this.api.delete(`/api/usuarios/${this.selectedAdmin}`);
        await this.carregarAdmins();
        this.selectedAdmin = '';
        this.adminSelecionado = {};
        this.adminEditado = { nome: '', senhaHash: '', entrada: '' };
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
      if (campo === 'nome') this.adminEditado.nome = this.adminSelecionado.nome;
      else if (campo === 'senha') this.adminEditado.senhaHash = '';
      else if (campo === 'entrada') this.adminEditado.entrada = this.adminSelecionado.entrada.split('T')[0];
    },

    async salvarCampo(campo) {
      if (this.salvando || !this.selectedAdmin) return;
      if (campo === 'senha' && this.adminEditado.senhaHash && this.adminEditado.senhaHash.length < 6) {
        this.$refs.senhaInput.focus();
        return;
      }
      this.salvando = true;
      try {
        const dados = {};
        if (campo === 'nome') dados.nome = this.adminEditado.nome;
        else if (campo === 'senha' && this.adminEditado.senhaHash) dados.senhaHash = this.adminEditado.senhaHash;
        else if (campo === 'entrada') dados.entrada = this.adminEditado.entrada;
        if (Object.keys(dados).length > 0) {
          const response = await this.api.put(`/api/usuarios/${this.selectedAdmin}`, dados);
          this.adminSelecionado = response.data;
        }
        this.editando[campo] = false;
        this.adminEditado.senhaHash = '';
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