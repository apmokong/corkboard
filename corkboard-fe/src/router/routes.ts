import type { RouteRecordRaw } from 'vue-router';
import { api } from 'boot/axios';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: '',
        redirect: '/blogs',
      },
      {
        path: 'blogs',
        name: 'BlogIndex',
        component: () => import('pages/blog/IndexPage.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'blog/:id/view',
        name: 'BlogView',
        component: () => import('pages/blog/ViewPage.vue'),
        meta: { requiresAuth: true },
      },
    ],
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('pages/LoginPage.vue'),
  },
  {
    path: '/logout',
    name: 'Logout',
    redirect: '/login',
    beforeEnter: () => {
      localStorage.removeItem('token');
  
      delete api.defaults.headers.common.Authorization;
    },
  },

  // Always leave this as last one, but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
];

export default routes;