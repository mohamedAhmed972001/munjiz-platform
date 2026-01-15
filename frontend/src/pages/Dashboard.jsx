import React, { useEffect, useState } from "react";
import { authService } from "../api/authService";
import { useNavigate } from "react-router-dom";
import ClientDashboard from "../pages/components/ClientDashboard";
import FreelancerDashboard from "../pages/components/FreelancerDashboard";

const Dashboard = () => {
  const [user, setUser] = useState(null);
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchData = async () => {
      try {
        const userRes = await authService.getUser();
        setUser(userRes.data);
        if (userRes.data.role_name === 'client') {
          const projectsRes = await authService.getMyProjects();
          setProjects(projectsRes);
        }
      } catch (error) { navigate("/login"); }
      finally { setLoading(false); }
    };
    fetchData();
  }, [navigate]);

  if (loading) return <div className="p-10 text-center font-bold">Loading Munjiz...</div>;

  return (
    <div className="min-h-screen bg-gray-50 p-6" dir="ltr">
      <div className="max-w-6xl mx-auto">
        {/* Shared Navbar for both */}
        <nav className="flex justify-between items-center mb-8 px-2">
          <span className="text-2xl font-black text-blue-700 italic">MUNJIZ.</span>
          <button onClick={() => authService.logout().then(() => navigate("/login"))} 
            className="text-gray-500 hover:text-red-600 font-semibold transition">
            Logout
          </button>
        </nav>

        {/* التوجيه بناءً على الـ Role */}
        {user?.role_name === 'client' ? (
          <ClientDashboard user={user} projects={projects} navigate={navigate} />
        ) : (
          <FreelancerDashboard user={user} />
        )}
      </div>
    </div>
  );
};

export default Dashboard;