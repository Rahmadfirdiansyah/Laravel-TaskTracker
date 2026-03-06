import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import routes, { setupRouterGuard } from './routes/index';

const app = createApp(App);
const pinia = createPinia();
const router = createRouter({
    history: createWebHistory(),
    routes,
});

app.use(pinia);
app.use(router);

// Setup router guard
setupRouterGuard(router);

app.mount('#app');