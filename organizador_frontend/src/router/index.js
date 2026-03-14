import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [

//! - - - - - telas de login/cadastro - - - - - - - - - - - -
    {
      path: '/',
      name: 'home',
      component: () => import('../views/Home.vue')
    },
    {
      path: '/loginAluno',
      name: 'loginAluno',
      component: () => import('../views/LoginAluno.vue')
    },
        {
      path: '/loginProfessor',
      name: 'loginProfessor',
      component: () => import('../views/LoginProfessor.vue')
    },
        {
      path: '/loginAdmin',
      name: 'loginAdmin',
      component: () => import('../views/LoginAdmin.vue')
    },
    {
      path: '/CadastroAluno',
      name: 'CadastroAluno',
      component: () => import('../views/CadastroAluno.vue')
    },
//! - - - - - telas de visualização para alunos - - - - - - -
    {
      path: '/PainelAlunosHorarios',
      name: 'PainelAlunosHorarios',
      component: () => import('../views/PainelAlunosHorarios.vue')
    },
    {
      path: '/PainelAlunosPerfil',
      name: 'PainelAlunosPerfil',
      component: () => import('../views/PainelAlunosPerfil.vue')
    },
//! - - - - - telas de visualização para professores - - - - - 
    {
      path: '/PainelProfessoresHorarios',
      name: 'PainelProfessoresHorarios',
      component: () => import('../views/PainelProfessoresHorarios.vue')
    },
    {
      path: '/PainelProfessoresPerfil',
      name: 'PainelProfessoresPerfil',
      component: () => import('../views/PainelProfessoresPerfil.vue')
    },
//! - - - - - telas de visualização para admin - - - - - - - -
    {
      path: '/PainelAdminHorarios',
      name: 'PainelAdminHorarios',
      component: () => import('../views/PainelAdminHorarios.vue')
    },
    {
      path: '/PainelAdminDisciplinas',
      name: 'PainelAdminDisciplinas',
      component: () => import('../views/PainelAdminDisciplinas.vue')
    },
    {
      path: '/PainelAdminSalas',
      name: 'PainelAdminSalas',
      component: () => import('../views/PainelAdminSalas.vue')
    },
    {
      path: '/PainelAdminTurmas',
      name: 'PainelAdminTurmas',
      component: () => import('../views/PainelAdminTurmas.vue')
    },
    {
      path: '/PainelAdminProfessores',
      name: 'PainelAdminProfessores',
      component: () => import('../views/PainelAdminProfessores.vue')
    },
    {
      path: '/PainelAdminAdmins',
      name: 'PainelAdminAdmins',
      component: () => import('../views/PainelAdminAdmins.vue')
    },
    {
      path: '/PainelAdminAlunos',
      name: 'PainelAdminAlunos',
      component: () => import('../views/PainelAdminAlunos.vue')
    },
    {
      path: '/PainelCriaHorarios',
      name: 'PainelCriaHorarios',
      component: () => import('../views/PainelCriaHorarios.vue')
    }
  ],
})
export default router