import React, { useState } from 'react';
import { authService } from '../../api/authService';
import { useNavigate } from 'react-router-dom'; // 1. استيراد الأداة

const Register = () => {
    const navigate = useNavigate(); // 2. تعريف الدالة عشان نستخدمها تحت

    const [formData, setFormData] = useState({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'client' 
    });

    const [errors, setErrors] = useState({}); 

    const handleSubmit = async (e) => {
      e.preventDefault();
      setErrors({}); // تصفير الأخطاء قبل المحاولة الجديدة
      try {
          const response = await authService.register(formData);
          
          // 3. التحويل الذكي بناءً على الـ Role
          if (formData.role === 'freelancer') {
              navigate('/complete-profile'); // هيروح لصفحة المهارات اللي ضفناها في App.jsx
          } else {
              navigate('/dashboard'); // صاحب المشروع يروح للداشبورد
          }
      } catch (err) {
          // التعامل مع أخطاء التحقق (Validation Errors) من لارافيل
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
                    <div>
                        <label className="block text-sm font-medium text-gray-700">Full Name</label>
                        <input 
                            type="text" 
                            placeholder="John Doe"
                            className="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none"
                            onChange={(e) => setFormData({...formData, name: e.target.value})}
                        />
                        {errors.name && <p className="text-red-500 text-xs mt-1">{errors.name[0]}</p>}
                    </div>

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
                    {errors.password && <p className="text-red-500 text-xs">{errors.password[0]}</p>}

                    <button type="submit" className="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition font-semibold">
                        Sign Up
                    </button>
                    
                    <p className="text-center text-sm text-gray-600 mt-4">
                        Already have an account? <span onClick={() => navigate('/login')} className="text-blue-600 cursor-pointer underline">Login</span>
                    </p>
                </form>
            </div>
        </div>
    );
};

export default Register;