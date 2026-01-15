import React, { useState } from 'react';
import { authService } from '../../api/authService';
import { useNavigate, Link } from 'react-router-dom'; // 1. ضفنا Link هنا

const Login = () => {
    const navigate = useNavigate();
    
    const [formData, setFormData] = useState({
        email: '',
        password: '',
    });

    const [errors, setErrors] = useState({});

    const handleSubmit = async (e) => {
      e.preventDefault();
      setErrors({});
      try {
          await authService.login(formData);
          const userResponse = await authService.getUser();
          const userData = userResponse.data;
  
          if (userData.role_name === 'admin') {
              navigate('/admin-panel'); 
          } else {
              navigate('/dashboard'); 
          }
  
          alert("Welcome back, " + userData.name);
          
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

                {/* 2. الجزء الخاص بالتحويل لصفحة التسجيل */}
                <div className="mt-6 text-center">
                    <p className="text-sm text-gray-600">
                        Don't have an account?{" "}
                        <Link to="/register" className="text-blue-600 hover:underline font-semibold">
                            Create a new account
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    );
};

export default Login;