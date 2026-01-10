import React, { useState } from 'react';
import { authService } from '../api/authService';

const Register = () => {
    // 1. تعريف الحالة لتخزين بيانات النموذج
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'client' // القيمة الافتراضية عند التحميل
    });

    // حالة لتخزين أخطاء التحقق القادمة من الباك-إند
    const [errors, setErrors] = useState({}); 

    // 2. دالة معالجة إرسال البيانات
    const handleSubmit = async (e) => {
        e.preventDefault();
        setErrors({}); // نصفر الأخطاء السابقة قبل المحاولة الجديدة
        
        try {
            // طلب التسجيل عبر الخدمة التي أنشأناها
            const response = await authService.register(formData);
            console.log("Registration success:", response.data);
            alert("Account created successfully!");
        } catch (err) {
            // التعامل مع أخطاء التحقق (مثل إيميل مكرر أو كلمة سر ضعيفة)
            if (err.response && err.response.status === 422) {
                setErrors(err.response.data.errors); 
            } else {
                console.error("Technical Error:", err);
            }
        }
    };

    return (
        <div className="min-h-screen bg-gray-100 flex items-center justify-center p-4">
            <div className="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
                <h2 className="text-2xl font-bold text-center text-gray-800 mb-6">Create an Account</h2>
                
                <form onSubmit={handleSubmit} className="space-y-4 text-left" dir="ltr">
                    {/* حقل الاسم الكامل */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700">Full Name</label>
                        <input 
                            type="text" 
                            placeholder="John Doe"
                            className="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none"
                            onChange={(e) => setFormData({...formData, name: e.target.value})}
                        />
                        {/* عرض خطأ الاسم إن وجد */}
                        {errors.name && <p className="text-red-500 text-xs mt-1">{errors.name[0]}</p>}
                    </div>

                    {/* حقل البريد الإلكتروني */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700">Email Address</label>
                        <input 
                            type="email" 
                            placeholder="example@mail.com"
                            className="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none"
                            onChange={(e) => setFormData({...formData, email: e.target.value})}
                        />
                        {errors.email && <p className="text-red-500 text-xs mt-1">{errors.email[0]}</p>}
                    </div>

                    {/* اختيار نوع الحساب (مستقل أم صاحب مشروع) */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700">Account Type</label>
                        <select 
                            className="w-full p-2 border rounded-md bg-white focus:ring-2 focus:ring-blue-500 outline-none"
                            onChange={(e) => setFormData({...formData, role: e.target.value})}
                        >
                            <option value="client">Client (I want to hire)</option>
                            <option value="freelancer">Freelancer (I'm looking for work)</option>
                        </select>
                    </div>

                    {/* حقول كلمة المرور وتأكيدها */}
                    <div className="flex gap-2">
                        <div className="w-1/2">
                            <label className="block text-sm font-medium text-gray-700">Password</label>
                            <input 
                                type="password" 
                                className="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none"
                                onChange={(e) => setFormData({...formData, password: e.target.value})}
                            />
                        </div>
                        <div className="w-1/2">
                            <label className="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input 
                                type="password" 
                                className="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none"
                                onChange={(e) => setFormData({...formData, password_confirmation: e.target.value})}
                            />
                        </div>
                    </div>
                    {/* عرض أخطاء كلمة المرور */}
                    {errors.password && <p className="text-red-500 text-xs">{errors.password[0]}</p>}

                    <button type="submit" className="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition font-semibold">
                        Sign Up
                    </button>
                    
                    <p className="text-center text-sm text-gray-600 mt-4">
                        Already have an account? <span className="text-blue-600 cursor-pointer">Login</span>
                    </p>
                </form>
            </div>
        </div>
    );
};

export default Register;