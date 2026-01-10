import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Register from './pages/Register'; // استيراد صفحة التسجيل اللي عملناها

function App() {
  return (
    // الـ Router هو اللي بيراقب الـ URL في المتصفح
    <Router>
      <div className="App">
        {/* هنا بنحدد المسارات (Routes) بتاعة الموقع */}
        <Routes>
          {/* لما الرابط يكون /register اعرض مكون الـ Register */}
          <Route path="/register" element={<Register />} />
          
          {/* صفحة تجريبية للرئيسية */}
          <Route path="/" element={
            <div className="flex items-center justify-center h-screen font-bold text-3xl">
              مرحباً بك في منصة مُنجز 🚀
            </div>
          } />
        </Routes>
      </div>
    </Router>
  );
}

export default App;