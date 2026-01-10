import React, { useState } from 'react';
import { authService } from '../api/authService';

const Login = () => {
    // حالة تخزين بيانات تسجيل الدخول
    const [formData, setFormData] = useState({
        email: '',
        password: '',
    });

    const [errors, setErrors] = useState({});

    // دالة معالجة تسجيل الدخول
    const handleSubmit = async (e) => {
        e.preventDefault();
        setErrors({});
        try {
            await authService.login(formData);
            // نجلب بيانات المستخدم بعد تسجيل الدخول للتأكد من الـ Session
            const user = await authService.getUser();
            console.log("Logged in user:", user.data);
            alert("Welcome back, " + user.data.name);
            
            // هنا مستقبلاً هنستخدم الـ Role عشان نوجهه للصفحة الصح
            // window.location.href = '/dashboard'; 
        } catch (err) {
            if (err.response && err.response.status === 422) {
                setErrors(err.response.data.errors);
            } else {
                alert("Login failed. Please check your credentials.");
            }
        }
    };

    return (
        <div className="min-h-screen bg-gray-100 flex items-center justify-center p-4">
            <div className="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
                <h2 className="text-2xl font-bold text-center text-gray-800 mb-6">Login to Munjiz</h2>
                
                <form onSubmit={handleSubmit} className="space-y-4 text-left" dir="ltr">
                    <div>
                        <label className="block text-sm font-medium text-gray-700">Email Address</label>
                        <input 
                            type="email" 
                            className="w-full p-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            onChange={(e) => setFormData({...formData, email: e.target.value})}
                        />
                        {errors.email && <p className="text-red-500 text-xs mt-1">{errors.email[0]}</p>}
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700">Password</label>
                        <input 
                            type="password" 
                            className="w-full p-2 border rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                            onChange={(e) => setFormData({...formData, password: e.target.value})}
                        />
                    </div>

                    <button type="submit" className="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition font-semibold">
                        Login
                    </button>
                </form>
            </div>
        </div>
    );
};

export default Login;