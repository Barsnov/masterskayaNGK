import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    base: '/build/', // 👈 добавь эту строку

    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),

        vue()
    ],

    // server: {
    //     host: '0.0.0.0',  // Разрешаем доступ с сети
    //     port: 8001,       // Порт для Vite
    //     strictPort: true,
    //     hmr: {
    //         host: '192.168.100.28', // Укажи свой IP
    //     }
    // }
});
