import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Register from './pages/Register';
import Login from './pages/Login';
import CompleteProfile from './pages/CompleteProfile'; // التأكد من الاسم هنا
import Dashboard from './pages/Dashboard'; 

function App() {
  return (
    <Router>
      <div className="App">
        <Routes>
          <Route path="/register" element={<Register />} />
          <Route path="/login" element={<Login />} />
          
          {/* ربط المسار بالكومبوننت اللي اسمه CompleteProfile */}
          <Route path="/complete-profile" element={<CompleteProfile />} />
          
          <Route path="/dashboard" element={<Dashboard />} />

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