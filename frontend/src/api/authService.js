import axiosClient from './axios';

export const authService = {
    // 1. طلب تصريح الـ CSRF
    getCsrfToken: () => axiosClient.get('/sanctum/csrf-cookie'),

    // 2. تسجيل مستخدم جديد
    register: async (userData) => {
        await authService.getCsrfToken(); 
        return axiosClient.post('/api/register', userData);
    },

    // 3. تسجيل الدخول (تم التعديل هنا)
    login: async (data) => {
        // نستخدم axiosClient لضمان وصول الـ Cookies والـ BaseURL الصح
        await authService.getCsrfToken(); 
        return axiosClient.post('/api/login', data); // تأكد من وجود /api/ لو المسار في api.php
    },

    // 4. تسجيل الخروج
    logout: () => axiosClient.post('/api/logout'),

    // 5. جلب بيانات المستخدم الحالي
    getUser: () => axiosClient.get('/api/user'),
};