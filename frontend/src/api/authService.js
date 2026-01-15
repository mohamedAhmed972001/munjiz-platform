import axiosClient from "./axios";
import axios from "axios"; // استيراد axios العادي لو هتستخدم URL كامل

export const authService = {
  // 1. طلب تصريح الـ CSRF
  getCsrfToken: () => axiosClient.get("/sanctum/csrf-cookie"),

  // 2. تسجيل مستخدم جديد (التصحيح هنا: شلنا كلمة const)
  // authService.js
  // authService.js
  register: async (userData) => {
    // 1. اطلب الـ Cookie الأول
    await axiosClient.get("/sanctum/csrf-cookie");

    // 2. ابعت طلب التسجيل
    const response = await axiosClient.post("/api/register", userData);
    return response.data;
  },

  // 3. تسجيل الدخول
  login: async (data) => {
    await authService.getCsrfToken();
    const response = await axiosClient.post("/api/login", data);
    return response.data;
  },

  // 4. تسجيل الخروج
  logout: () => axiosClient.post("/api/logout"),

  // 5. جلب بيانات المستخدم الحالي
  getUser: () => axiosClient.get("/api/user"), // السيرفر هيرجع اليوزر بالـ Roles والـ Skills// 2. جلب كل المهارات المتاحة من الداتابيز

  getSkills: async () => {
    const response = await axiosClient.get("/api/skills");
    return response.data;
  },

  updateSkills: async (skillsData) => {
    // 1. طلب تصريح الأمان (ضروري جداً لحل 419)
    await axiosClient.get("/sanctum/csrf-cookie");

    // 2. إرسال المهارات للباك-إند
    const response = await axiosClient.post("/api/user/skills", skillsData);
    return response.data;
  },
  // إضافة مشروع جديد
  // إضافة مشروع جديد
  postProject: async (projectData) => {
    // 1. طلب تصريح الأمان (ضروري جداً لتجنب 419)
    await authService.getCsrfToken();

    // 2. استخدام axiosClient عشان يبعت الـ Cookies والـ Base URL صح
    const response = await axiosClient.post("/api/projects", projectData);
    return response.data;
  },
  async getMyProjects() {
    const response = await axiosClient.get("/api/my-projects");
    return response.data;
  },
  // جلب تفاصيل مشروع معين
  async getProjectDetails(id) {
    const response = await axiosClient.get(`/api/projects/${id}`);
    return response.data;
  },
  async updateProject(id, data) {
    const response = await axiosClient.put(`/api/projects/${id}`, data);
    return response.data;
  },
  async deleteProject(id) {
    const response = await axiosClient.delete(`/api/projects/${id}`);
    return response.data;
  },
  // دالة جلب كل مشاريع المنصة
  async getAllProjects() {
    const response = await axiosClient.get("/api/all-projects");
    return response.data;
  },
// جلب تفاصيل مشروع معين (وحدنا الاسم هنا عشان يطابق الـ Component)
async getProjectById(id) {
  const response = await axiosClient.get(`/api/projects/${id}`);
  // تأكد لو الباك-إند بيرجع الداتا جوه object اسمه data
  return response.data; 
},

// إرسال عرض (Bid) من الفريلانسر للمشروع
async applyForProject(bidData) {
  // طلب توكن الأمان قبل أي عملية POST
  await authService.getCsrfToken();
  const response = await axiosClient.post("/api/bids", bidData);
  return response.data;
},
};
