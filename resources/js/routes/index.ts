import { RouteRecordRaw } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { createRouter, createWebHistory, NavigationGuardNext, RouteLocationNormalized } from 'vue-router';

const routes: RouteRecordRaw[] = [
    {
        path: '/login',
        name: 'Login',
        component: () => import('../views/LoginView.vue'),
        meta: { guestOnly: true },
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: () => import('../views/DashboardView.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/projects',
        name: 'Projects',
        component: () => import('../views/ProjectListView.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/projects/:id',
        name: 'ProjectDetail',
        component: () => import('../views/ProjectDetailView.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/task',
        name: 'Task',
        component: () => import('../views/TaskListView.vue'),
        meta: { requiresAuth: true },
    },

];

export default routes;

// Navigation guard
export const setupRouterGuard = (router: any) => {
    router.beforeEach((to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => {
        const token = localStorage.getItem('token');

        // PENTING: Cek Meta requiresAuth
        if (to.matched.some(record => record.meta.requiresAuth)) {
            if (!token) {
                // Gunakan 'Login' (Sesuai dengan name di atas)
                next({ name: 'Login' });
            } else {
                next();
            }
        }
        // Cek Meta guestOnly (Untuk halaman Login)
        else if (to.matched.some(record => record.meta.guestOnly)) {
            if (token) {
                next({ name: 'Projects' });
            } else {
                next();
            }
        }
        else {
            next();
        }
    });
};