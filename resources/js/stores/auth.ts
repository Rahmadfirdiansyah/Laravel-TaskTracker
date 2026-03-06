import { defineStore } from 'pinia';
import { ref } from 'vue';
import api from '../src/services/api';
import type { User } from '../types';

export const useAuthStore = defineStore('auth', () => {
    const user = ref<User | null>(null);
    const token = ref<string>(localStorage.getItem('token') || '');

    const login = async (email: string, password: string) => {
        try {
            const response = await api.post('/login', { email, password });

            // PERBAIKAN DI SINI: Tambahkan ekstra .data
            token.value = response.data.data.token;
            user.value = response.data.data.user;

            localStorage.setItem('token', token.value);
        } catch (error) {
            throw error;
        }
    };

    const logout = () => {
        user.value = null;
        token.value = '';
        localStorage.removeItem('token');
    };

    return {
        user,
        token,
        login,
        logout,
    };
});