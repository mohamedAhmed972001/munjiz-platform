import axiosClient from './axios';
import axios from 'axios'; // استيراد axios العادي لو هتستخدم URL كامل

export const authService = {
    // 1. طلب تصريح الـ CSRF
    getCsrfToken: () => axiosClient.get('/sanctum/csrf-cookie'),

    // 2. تسجيل مستخدم جديد (التصحيح هنا: شلنا كلمة const)
    // authService.js
// authService.js
register: async (userData) => {
  // 1. اطلب الـ Cookie الأول
  await axiosClient.get('/sanctum/csrf-cookie');

  // 2. ابعت طلب التسجيل
  const response = await axiosClient.post('/api/register', userData);
  return response.data;
},

    // 3. تسجيل الدخول
    login: async (data) => {
        await authService.getCsrfToken(); 
        const response = await axiosClient.post('/api/login', data);
        return response.data;
    },

    // 4. تسجيل الخروج
    logout: () => axiosClient.post('/api/logout'),

    // 5. جلب بيانات المستخدم الحالي
    getUser: () => axiosClient.get('/api/user'),
// 2. جلب كل المهارات المتاحة من الداتابيز
getSkills: async () => {
  const response = await axiosClient.get('/api/skills');
  return response.data;
},

updateSkills: async (skillsData) => {
  // 1. طلب تصريح الأمان (ضروري جداً لحل 419)
  await axiosClient.get('/sanctum/csrf-cookie'); 
  
  // 2. إرسال المهارات للباك-إند
  const response = await axiosClient.post('/api/user/skills', skillsData);
  return response.data;
},
};
