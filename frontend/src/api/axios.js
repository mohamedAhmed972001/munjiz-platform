import axios from 'axios';

const axiosClient = axios.create({
    // الرابط الأساسي للباك إند اللي عرفناه في الـ .env
    baseURL: "http://localhost:8000",
    // دي أهم نقطة عشان الـ Sessions والـ Cookies تشتغل بين الرياكت واللارافيل
    withCredentials: true,
    withXSRFToken: true,
});

// شرح: السطر ده بيخلي axios يبعت الـ Cookies تلقائياً مع كل طلب
// وبيدور على الـ CSRF token اللي لارافيل بيبعته ويحطه في الـ Headers

export default axiosClient;