import axiosClient from './axios';

export const authService = {
    // 1. طلب تصريح الـ CSRF (لازم يتنادى قبل الـ Login/Register)
    getCsrfToken: () => axiosClient.get('/sanctum/csrf-cookie'),

    // 2. تسجيل مستخدم جديد
    register: async (userData) => {
        await authService.getCsrfToken(); // طلب التصريح أولاً
        return axiosClient.post('/api/register', userData);
    },

    // 3. تسجيل الدخول
    login: async (credentials) => {
        await authService.getCsrfToken(); // طلب التصريح أولاً
        return axiosClient.post('/api/login', credentials);
    },

    // 4. تسجيل الخروج
    logout: () => axiosClient.post('/api/logout'),

    // 5. جلب بيانات المستخدم الحالي (للـ Session)
    getUser: () => axiosClient.get('/api/user'),
};