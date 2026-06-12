<template>
  <div class="flex h-screen">
    <div class="hidden lg:flex lg:w-2/5 bg-gradient-to-br from-[#ff9421] to-[#c96a00] flex-col items-center justify-center p-12">
      <img src="/logo-unifil.png" alt="UniFil" class="w-full object-contain mb-10" />
      <h2 class="text-white text-2xl font-bold text-center mb-3">Organizador de Grade</h2>
      <p class="text-orange-100 text-center text-sm leading-relaxed max-w-xs">
        Gerencie seus horários acadêmicos de forma simples e eficiente
      </p>
    </div>

    <div class="flex-1 flex flex-col items-center justify-center bg-white px-8">
      <div class="w-full max-w-sm">
        <div class="lg:hidden mb-8 flex justify-center">
          <img src="/logo-unifil.png" alt="UniFil" class="w-64 object-contain mb-10" />
        </div>

        <h1 class="text-2xl font-bold text-stone-800 mb-1">Bem-vindo</h1>
        <p class="text-stone-500 text-sm mb-8">Selecione como deseja acessar o sistema</p>

        <div class="space-y-3">
          <button
            @click="irParaLogin('aluno')"
            class="w-full flex items-center gap-4 px-5 py-4 border-2 border-stone-200 rounded-xl hover:border-[#ff9421] hover:bg-orange-50 transition-all duration-200 text-left group"
          >
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center group-hover:bg-[#ff9421] transition-all duration-200">
              <svg class="w-5 h-5 text-[#ff9421] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <div>
              <p class="font-semibold text-stone-800 text-sm">Sou Aluno</p>
              <p class="text-xs text-stone-500">Acesse seus horários e perfil</p>
            </div>
            <svg class="w-4 h-4 text-stone-400 ml-auto group-hover:text-[#ff9421] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>

          <button
            @click="irParaLogin('professor')"
            class="w-full flex items-center gap-4 px-5 py-4 border-2 border-stone-200 rounded-xl hover:border-[#ff9421] hover:bg-orange-50 transition-all duration-200 text-left group"
          >
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center group-hover:bg-[#ff9421] transition-all duration-200">
              <svg class="w-5 h-5 text-[#ff9421] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div>
              <p class="font-semibold text-stone-800 text-sm">Sou Professor</p>
              <p class="text-xs text-stone-500">Gerencie sua grade de aulas</p>
            </div>
            <svg class="w-4 h-4 text-stone-400 ml-auto group-hover:text-[#ff9421] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>

          <button
            @click="irParaLogin('admin')"
            class="w-full flex items-center gap-4 px-5 py-4 border-2 border-stone-200 rounded-xl hover:border-[#ff9421] hover:bg-orange-50 transition-all duration-200 text-left group"
          >
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center group-hover:bg-[#ff9421] transition-all duration-200">
              <svg class="w-5 h-5 text-[#ff9421] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div>
              <p class="font-semibold text-stone-800 text-sm">Administrador</p>
              <p class="text-xs text-stone-500">Acesse o painel de controle</p>
            </div>
            <svg class="w-4 h-4 text-stone-400 ml-auto group-hover:text-[#ff9421] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>

        <div class="mt-6 pt-6 border-t border-stone-200">
          <button
            @click="irParaCadastro"
            class="w-full py-3 text-[#ff9421] font-semibold text-sm border-2 border-[#ff9421] rounded-xl hover:bg-[#ff9421] hover:text-white transition-all duration-200"
          >
            Criar conta de aluno
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";

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
