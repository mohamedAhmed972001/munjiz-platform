import React, { useState } from 'react';
import { authService } from '../../api/authService';
import { useNavigate } from 'react-router-dom';

const CreateProject = () => {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        title: '',
        description: '',
        budget: ''
    });
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        try {
            await authService.postProject(formData);
            alert("Project posted successfully! 🚀");
            navigate('/dashboard'); // نرجعه للداشبورد بعد النجاح
        } catch (error) {
            console.error("Error posting project:", error);
            alert(error.response?.data?.message || "Failed to post project");
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-gray-50 p-8">
            <div className="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-md">
                <h2 className="text-2xl font-bold mb-6 text-gray-800">Post a New Project</h2>
                
                <form onSubmit={handleSubmit} className="space-y-6">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Project Title</label>
                        <input 
                            required
                            type="text"
                            placeholder="e.g. Build a Landing Page for Clinic"
                            className="w-full p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                            onChange={(e) => setFormData({...formData, title: e.target.value})}
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea 
                            required
                            rows="5"
                            placeholder="Describe what you need in detail..."
                            className="w-full p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                            onChange={(e) => setFormData({...formData, description: e.target.value})}
                        ></textarea>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Budget ($)</label>
                        <input 
                            required
                            type="number"
                            placeholder="50"
                            className="w-full p-3 border rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
                            onChange={(e) => setFormData({...formData, budget: e.target.value})}
                        />
                    </div>

                    <button 
                        disabled={loading}
                        type="submit"
                        className="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition duration-300 disabled:bg-gray-400"
                    >
                        {loading ? "Posting..." : "Post Project"}
                    </button>
                </form>
            </div>
        </div>
    );
};

export default CreateProject;