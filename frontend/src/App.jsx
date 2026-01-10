import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Register from './pages/Register';
import Login from './pages/Login'; // 1. استيراد صفحة اللوجن الجديدة

function App() {
  return (
    <Router>
      <div className="App">
        <Routes>
          {/* مسار صفحة التسجيل */}
          <Route path="/register" element={<Register />} />
          
          {/* 2. إضافة مسار صفحة تسجيل الدخول */}
          <Route path="/login" element={<Login />} />
          
          {/* الصفحة الرئيسية المؤقتة */}
          <Route path="/" element={
            <div className="flex flex-col items-center justify-center h-screen font-bold">
              <h1 className="text-3xl mb-4">Welcome to Munjiz Platform 🚀</h1>
              <div className="space-x-4">
                <a href="/login" className="text-blue-600 underline">Login</a>
                <a href="/register" className="text-blue-600 underline">Register</a>
              </div>
            </div>
          } />
        </Routes>
      </div>
    </Router>
  );
}

export default App;