import React, { useEffect, useState } from "react";
import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";
import { authService } from "./api/authService";
import ProtectedRoute from "./routes/ProtectedRoute";

// Pages
import Login from "./pages/auth/Login";
import Register from "./pages/auth/Register";
import ClientDashboard from "./pages/client/Dashboard";
import CreateProject from "./pages/client/CreateProject";
import ProjectManager from "./pages/client/ProjectManager"; // صفحة الكلاينت
import FreelancerDashboard from "./pages/freelancer/Dashboard";
import ProjectView from "./pages/freelancer/ProjectView";   // صفحة الفريلانسر

function App() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // تشيك السيشن مرة واحدة أول ما الموقع يفتح
    authService.getUser()
      .then(res => setUser(res.data))
      .catch(() => setUser(null))
      .finally(() => setLoading(false));
  }, []);

  if (loading) return <div className="h-screen flex items-center justify-center font-bold">Checking Munjiz Security... 🔒</div>;

  return (
    <Router>
      <Routes>
        <Route path="/login" element={!user ? <Login /> : <Navigate to="/" />} />
        
        {/* === منطقة الكلاينت === */}
        <Route element={<ProtectedRoute user={user} allowedRoles={['client']} />}>
            <Route path="/client/dashboard" element={<ClientDashboard user={user} />} />
            <Route path="/client/projects/create" element={<CreateProject />} />
            {/* لاحظ المسار هنا: خاص بإدارة المشروع */}
            <Route path="/client/projects/:id/manage" element={<ProjectManager />} />
        </Route>

        {/* === منطقة الفريلانسر === */}
        <Route element={<ProtectedRoute user={user} allowedRoles={['freelancer']} />}>
            <Route path="/freelancer/dashboard" element={<FreelancerDashboard user={user} />} />
            {/* لاحظ المسار هنا: خاص برؤية المشروع والتقديم */}
            <Route path="/projects/:id" element={<ProjectView />} />
        </Route>

        {/* التوجيه الرئيسي */}
        <Route path="/" element={
            user ? (
                user.role_name === 'client' ? <Navigate to="/client/dashboard" /> : <Navigate to="/freelancer/dashboard" />
            ) : <Navigate to="/login" />
        } />
      </Routes>
    </Router>
  );
}

export default App;