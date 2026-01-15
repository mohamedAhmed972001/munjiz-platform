import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { authService } from '../../api/authService';

const EditProject = () => {
    const { id } = useParams();
    const navigate = useNavigate();
    const [formData, setFormData] = useState({ title: '', description: '', budget: '' });
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        authService.getProjectDetails(id).then(data => {
            setFormData({ title: data.title, description: data.description, budget: data.budget });
            setLoading(false);
        }).catch(() => navigate('/dashboard'));
    }, [id]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await authService.updateProject(id, formData);
            alert("Updated Successfully! ✅");
            navigate(`/projects/${id}`);
        } catch (error) { alert("Update failed!"); }
    };

    if (loading) return <p className="text-center p-10">Loading Project Data...</p>;

    return (
        <div className="min-h-screen bg-gray-50 p-8 text-left" dir="ltr">
            <form onSubmit={handleSubmit} className="max-w-2xl mx-auto bg-white p-8 rounded-3xl shadow-sm">
                <h2 className="text-2xl font-bold mb-6">Edit Project</h2>
                <div className="space-y-4">
                    <input type="text" value={formData.title} 
                        onChange={(e) => setFormData({...formData, title: e.target.value})}
                        className="w-full p-4 border rounded-2xl" placeholder="Project Title" required />
                    
                    <textarea value={formData.description} rows="5"
                        onChange={(e) => setFormData({...formData, description: e.target.value})}
                        className="w-full p-4 border rounded-2xl" placeholder="Description" required />
                    
                    <input type="number" value={formData.budget}
                        onChange={(e) => setFormData({...formData, budget: e.target.value})}
                        className="w-full p-4 border rounded-2xl" placeholder="Budget ($)" required />
                    
                    <button type="submit" className="w-full bg-blue-600 text-white p-4 rounded-2xl font-bold hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    );
};

export default EditProject;