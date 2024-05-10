import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/store-auth';

const routes = [
  {
    path: '/',
    name: 'login',
    component: () => import('../views/Auth/Login.vue'),
  },
  {
    path: '/admin',
    name: 'admin',
    component: () => import('@/views/Admin/Dashboard.vue'),
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const user = useAuthStore().getUser;

  if (!user) {
    if (to.name !== 'login') {
      next({ name: 'login' });
    } else {
      next();
    }
  } else {
    if (to.matched.some(record => record.name === user.userType || record.name.startsWith(user.userType + '-'))) {
      next();
      console.log("User type:", user.userType, "Navigating to:", to.name);
    } else {
      console.log("User type:", user.userType, "Navigating to:", to.name);
      next({ name: user.userType });
    }
  }
});

export default router;
