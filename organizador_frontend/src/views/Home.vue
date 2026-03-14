<template>
  <div class="flex flex-col items-center justify-center h-screen bg-gradient-to-b from-[#ff9421] to-[#ffbe78]">
    <div class="absolute top-1 w-full flex justify-center">
      <img src="/logo-unifil.png" alt="UniFil" class="w-80 md:w-96 lg:w-[800px] mx-auto" />
    </div>

    <div class="bg-[#ffeccf] rounded-2xl shadow-md p-10 flex flex-col items-center relative transition-all duration-300">
      <p class="text-center text-[#545252] font-medium tracking-widest leading-relaxed mb-6">
        Escolha a forma de <br />
        Login ou realize o <br />
        seu cadastro!
      </p>

      <div class="relative flex flex-col items-center"
        @mouseenter="mostrarPerfis = true"
        @mouseleave="mostrarPerfis = false">

        <transition name="fade">
          <div v-if="mostrarPerfis" class="absolute -top-44 flex flex-col items-center gap-3 bg-[#d1d1d1] p-4 rounded-2xl shadow-lg w-48">
            <button class="bg-[#616161] text-white font-bold rounded-full py-2 px-4 w-full hover:bg-gray-800 transition"
              @click="irParaLogin('aluno')">
                Sou Aluno
            </button>

            <button class="bg-[#616161] text-white font-bold rounded-full py-2 px-4 w-full hover:bg-gray-800 transition"
              @click="irParaLogin('professor')">
                Sou Professor
            </button>
            
            <button class="bg-[#616161] text-white font-bold rounded-full py-2 px-4 w-full hover:bg-gray-800 transition"
              @click="irParaLogin('admin')">
                Sou Admin
            </button>
          </div>
        </transition>

        <button class="bg-[#ffac26] text-white font-semibold text-lg rounded-full py-2 px-10 mt-2 shadow-md hover:shadow-lg transition-all">
          Login
        </button>
      </div>

      <button class="bg-[#ffac26] text-white font-semibold text-lg rounded-full py-2 px-10 mt-6 shadow-md hover:shadow-lg transition-all"
        @click="irParaCadastro">
          Cadastro
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

  const mostrarPerfis = ref(false);
  const router = useRouter();

  const irParaLogin = (tipo) => {
    if (tipo === "aluno") router.push("/loginAluno");
    if (tipo === "professor") router.push("/loginProfessor");
    if (tipo === "admin") router.push("/loginAdmin");
  };

  const irParaCadastro = () => {
    router.push("/CadastroAluno");
  };
</script>

<style scoped>
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.3s ease;
  }
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
</style>
