<template>
  <div class="flex h-screen bg-gray-100" @click="fecharModalFora">
    <div class="w-64 bg-[#ff9421] flex flex-col">
      <div class="h-32 flex items-center justify-center overflow-hidden relative">
        <img src="/logo-unifil.png" alt="UniFil" class="min-w-[1000px] h-auto scale-125" />
        <hr class="absolute bottom-2 left-4 right-4 border-white border-t-2">
      </div>

      <nav class="flex-1 px-4 space-y-4 mt-2">
        <button v-for="item in menuItems" :key="item.id"@click="navegar(item)":class="['w-full px-4 py-3 text-left text-white font-medium rounded-lg text-sm transition-all', activeMenu === item.id ? 'bg-[#5a5a5a]' : 'bg-[#757575] hover:bg-[#858585]']">
          {{ item.label }}
        </button>
      </nav>

      <div class="p-5 pb-6">
        <button @click="sair" class="hover:opacity-80 transition" title="Sair">
          <img src="/sair.png" alt="Sair" class="w-10 h-10" />
        </button>
      </div>
    </div>

    <div class="flex-1 flex flex-col relative">
      <div class="p-8">
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center gap-4">
            <select v-model="selectedAdmin" @change="carregarAdminSelecionado" class="w-370 bg-[#ffac26] text-white px-8 py-2.5 rounded text-sm font-medium cursor-pointer border-none outline-none">
              <option value="">
                Selecione um admin
              </option>
              <option v-for="admin in admins" :key="admin.id" :value="admin.id">
                {{ admin.nome }}
              </option>
            </select>
          </div>
          <button @click="toggleModal" class="bg-[#ffac26] text-white px-10 py-2.5 rounded-full text-sm font-medium hover:bg-orange-600 transition shadow-md">
            +
          </button>
        </div>
      </div>

      <div class="flex-1 flex items-center justify-center p-8">
        <div class="bg-[#ffeccf] rounded-xl p-8 shadow-lg w-80 min-h-[500px] flex flex-col justify-between">
          <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">
              Admins
            </h2>
            
            <hr class="border-gray-300 mb-6">
            
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <label class="block text-gray-700 font-medium mb-2 text-sm">
                    Nome
                  </label>
                  <div class="border-b border-gray-700 pb-1 flex items-center">
                    <input 
                      v-if="editando.nome" 
                      v-model="adminEditado.nome"
                      ref="nomeInput"
                      type="text" 
                      class="w-full bg-transparent text-gray-800 text-base outline-none"
                      @blur="salvarCampo('nome')"
                      @keyup.enter="salvarCampo('nome')"
                      @keyup.esc="cancelarEdicao('nome')"
                    >
                    <span v-else class="w-full bg-transparent text-gray-800 text-base">
                      {{ adminSelecionado.nome || 'Selecione um admin' }}
                    </span>
                    <button 
                      @click="toggleEdicao('nome')" 
                      class="ml-2 hover:opacity-70 transition"
                      :disabled="!selectedAdmin"
                    >
                      <img src="/lapis.png" alt="Editar" class="w-5 h-5" />
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <label class="block text-gray-700 font-medium mb-2 text-sm">
                    Senha
                  </label>
                  <div class="border-b border-gray-700 pb-1 flex items-center">
                    <input 
                      v-if="editando.senha" 
                      v-model="adminEditado.senhaHash"
                      ref="senhaInput"
                      type="password" 
                      placeholder="Nova senha (mín. 6 caracteres)"
                      class="w-full bg-transparent text-gray-800 text-base outline-none"
                      @blur="salvarCampo('senha')"
                      @keyup.enter="salvarCampo('senha')"
                      @keyup.esc="cancelarEdicao('senha')"
                    >
                    <span v-else class="w-full bg-transparent text-gray-500 text-base">
                      ••••••••
                    </span>
                    <button 
                      @click="toggleEdicao('senha')" 
                      class="ml-2 hover:opacity-70 transition"
                      :disabled="!selectedAdmin"
                    >
                      <img src="/lapis.png" alt="Editar" class="w-5 h-5" />
                    </button>
                  </div>
                  <div v-if="editando.senha && adminEditado.senhaHash.length > 0 && adminEditado.senhaHash.length < 6" 
                       class="text-red-500 text-xs mt-1">
                    A senha deve ter pelo menos 6 caracteres
                  </div>
                </div>
              </div>
              
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <label class="block text-gray-700 font-medium mb-2 text-sm">
                    Entrada
                  </label>
                  <div class="border-b border-gray-700 pb-1 flex items-center">
                    <input 
                      v-if="editando.entrada" 
                      v-model="adminEditado.entrada"
                      ref="entradaInput"
                      type="date" 
                      class="w-full bg-transparent text-gray-800 text-base outline-none"
                      @blur="salvarCampo('entrada')"
                      @keyup.esc="cancelarEdicao('entrada')"
                    >
                    <span v-else class="w-full bg-transparent text-gray-800 text-base">
                      {{ formatarData(adminSelecionado.entrada) || 'Selecione um admin' }}
                    </span>
                    <button 
                      @click="toggleEdicao('entrada')" 
                      class="ml-2 hover:opacity-70 transition"
                      :disabled="!selectedAdmin"
                    >
                      <img src="/lapis.png" alt="Editar" class="w-5 h-5" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <button @click="deletarAdmin" :disabled="!selectedAdmin"class="absolute bottom-8 right-8 p-3 transition hover:opacity-70":class="selectedAdmin ? 'cursor-pointer' : 'cursor-not-allowed opacity-40'"title="Deletar admin selecionado">
        <img src="/lixo.png" alt="Deletar" class="w-8 h-8" />
      </button>

      <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50" @click="fecharModalFora">
        <div class="bg-gray-700 rounded-lg p-6 w-80 shadow-2xl border border-gray-600 transform transition-all" @click.stop>
          <h3 class="text-xl font-bold text-white mb-4 text-center">
            Cadastro de novo admin
          </h3>
          
          <div class="space-y-4">
            <div>
              <label class="block text-white font-medium mb-2 text-sm">
                *Nome:
              </label>
              <input 
                v-model="novoAdmin.nome"
                type="text" 
                class="w-full bg-gray-600 text-white px-3 py-2 rounded border border-gray-500 outline-none focus:border-[#ffac26]"
                placeholder="Digite o nome"
                @keyup.enter="criarNovoAdmin"
              >
            </div>
            
            <div>
              <label class="block text-white font-medium mb-2 text-sm">
                *Senha:
              </label>
              <input v-model="novoAdmin.senha"type="password" class="w-full bg-gray-600 text-white px-3 py-2 rounded border border-gray-500 outline-none focus:border-[#ffac26]"placeholder="Digite a senha"@keyup.enter="criarNovoAdmin">
              <div v-if="novoAdmin.senha && novoAdmin.senha.length < 6" class="text-red-400 text-xs mt-1">
                A senha deve ter pelo menos 6 caracteres
              </div>
            </div>

            <div>
              <label class="block text-white font-medium mb-2 text-sm">
                Entrada:
              </label>
              <input v-model="novoAdmin.entrada"type="date" class="w-full bg-gray-600 text-white px-3 py-2 rounded border border-gray-500 outline-none focus:border-[#ffac26]">
            </div>
            
            <hr class="border-gray-500 my-4">
            
            <button @click="criarNovoAdmin":disabled="!novoAdmin.nome || !novoAdmin.senha || novoAdmin.senha.length < 6" :class="['w-full py-3 rounded font-medium transition', novoAdmin.nome && novoAdmin.senha && novoAdmin.senha.length >= 6 ? 'bg-[#ffac26] text-white hover:bg-orange-600 cursor-pointer' : 'bg-gray-500 text-gray-300 cursor-not-allowed']">
              Gerar admin
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
      } catch (error) {
      }
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
      if (event) {
        event.stopPropagation();
      }
      this.showModal = !this.showModal;
      if (!this.showModal) {
        this.limparNovoAdmin();
      }
    },

    fecharModalFora(event) {
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
      if (!this.novoAdmin.nome || !this.novoAdmin.senha || this.novoAdmin.senha.length < 6) {
        return;
      }

      try {
        const dadosAdmin = {
          nome: this.novoAdmin.nome,
          senhaHash: this.novoAdmin.senha,
          permissao: 1,
          entrada: this.novoAdmin.entrada || new Date().toISOString().split('T')[0]
        };

        await this.api.post('/api/usuarios', dadosAdmin);
        
        this.showModal = false;
        this.limparNovoAdmin();
        await this.carregarAdmins();
        
      } catch (error) {}
    },

    async deletarAdmin() {
      if (!this.selectedAdmin) {
        return;
      }

      const adminParaDeletar = this.admins.find(admin => admin.id === this.selectedAdmin);
      
      if (!adminParaDeletar) {
        return;
      }

      const confirmacao = confirm(`Tem certeza que deseja deletar o admin "${adminParaDeletar.nome}"? Esta ação não pode ser desfeita.`);
      
      if (!confirmacao) {
        return;
      }

      try {
        await this.api.delete(`/api/usuarios/${this.selectedAdmin}`);
        
        await this.carregarAdmins();
        
        this.selectedAdmin = '';
        this.adminSelecionado = {};
        this.adminEditado = {
          nome: '',
          senhaHash: '',
          entrada: ''
        };
        
      } catch (error) {
      }
    },

    toggleEdicao(campo) {
      if (this.salvando) return;
      
      Object.keys(this.editando).forEach(key => {
        this.editando[key] = false;
      });
      
      this.editando[campo] = true;
      
      this.$nextTick(() => {
        const refName = `${campo}Input`;
        if (this.$refs[refName]) {
          this.$refs[refName].focus();
        }
      });
    },

    cancelarEdicao(campo) {
      this.editando[campo] = false;
      if (campo === 'nome') {
        this.adminEditado.nome = this.adminSelecionado.nome;
      } else if (campo === 'senha') {
        this.adminEditado.senhaHash = '';
      } else if (campo === 'entrada') {
        this.adminEditado.entrada = this.adminSelecionado.entrada.split('T')[0];
      }
    },

    async salvarCampo(campo) {
      if (this.salvando) return;
      
      if (!this.selectedAdmin) return;

      if (campo === 'senha' && this.adminEditado.senhaHash && this.adminEditado.senhaHash.length < 6) {
        this.$refs.senhaInput.focus();
        return;
      }

      this.salvando = true;

      try {
        const dadosAtualizacao = {};

        if (campo === 'nome') {
          dadosAtualizacao.nome = this.adminEditado.nome;
        } else if (campo === 'senha' && this.adminEditado.senhaHash) {
          dadosAtualizacao.senhaHash = this.adminEditado.senhaHash;
        } else if (campo === 'entrada') {
          dadosAtualizacao.entrada = this.adminEditado.entrada;
        }

        if (Object.keys(dadosAtualizacao).length > 0) {
          const response = await this.api.put(`/api/usuarios/${this.selectedAdmin}`, dadosAtualizacao);
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
      const date = new Date(data);
      return date.toLocaleDateString('pt-BR');
    },

    navegar(item) {
      this.activeMenu = item.id;
      if (item.rota) {
        this.$router.push(item.rota).catch(() => {});
      }
    },

    sair() {
      localStorage.removeItem('usuario');
      this.$router.push('/').catch(() => {});
    }
  }
};
</script>