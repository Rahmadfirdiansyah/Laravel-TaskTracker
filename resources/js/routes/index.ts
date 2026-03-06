import { RouteRecordRaw } from 'vue-router';
import { useAuthStore } from '../stores/auth';

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
    router.beforeEach((to: any, from: any, next: any) => {
        const authStore = useAuthStore();

        if (to.meta.requiresAuth && !authStore.token) {
            next('/login');
        } else if (to.meta.guestOnly && authStore.token) {
            next('/');
        } else {
            next();
        }
    });
};