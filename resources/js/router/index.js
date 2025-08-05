import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth'; 

const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import("../vueJs/views/Home.vue"),
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import("../vueJs/views/Login.vue"), 
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import("../vueJs/views/Register.vue"), 
    },
    {
        path: '/post-job',
        name: 'PostJob',
        component: () => import("../vueJs/views/PostJob.vue"), 
        meta: { requiresAuth: true },
    },
    {
        path: '/my-companies',
        name: 'MyCompanies',
        component: () => import("../vueJs/views/MyCompanies.vue"), 
        meta: { requiresAuth: true },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

//check for authentication
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'Login' });
    } else {
        next();
    }
});

export default router;
