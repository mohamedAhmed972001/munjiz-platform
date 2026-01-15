import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { authService } from '../../api/authService';

const ProjectView = () => {
    const { id } = useParams();
    const navigate = useNavigate();
    const [project, setProject] = useState(null);
    const [bidAmount, setBidAmount] = useState('');
    const [message, setMessage] = useState('');
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        // هنا بنجيب تفاصيل المشروع بس، مش محتاجين نجيب الـ Bids
        authService.getProjectById(id)
            .then(res => setProject(res.data ? res.data : res))
            .catch(() => navigate('/freelancer/dashboard'))
            .finally(() => setLoading(false));
    }, [id, navigate]);

    const handleApply = async (e) => {
        e.preventDefault();
        try {
            await authService.applyForProject({ project_id: id, amount: bidAmount, message });
            alert("Proposal Sent! 🚀");
            navigate('/freelancer/dashboard');
        } catch (err) {
            alert(err.response?.data?.message || "Error applying");
        }
    };

    if (loading) return <div>Loading Opportunity...</div>;

    return (
        <div className="p-8 max-w-3xl mx-auto">
            <div className="bg-white p-8 rounded-3xl shadow-lg border border-gray-100">
                <span className="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold mb-4 inline-block">New Opportunity</span>
                <h1 className="text-3xl font-black mb-2">{project.title}</h1>
                <div className="text-2xl font-bold text-green-600 mb-6">${project.budget}</div>
                <p className="text-gray-600 leading-relaxed text-lg mb-8 border-b pb-8">{project.description}</p>

                {/* Apply Form */}
                <form onSubmit={handleApply} className="space-y-4">
                    <h3 className="font-bold text-xl">Submit your proposal</h3>
                    <input 
                        type="number" 
                        placeholder="Your Bid Price ($)" 
                        className="w-full p-4 border rounded-xl"
                        value={bidAmount} onChange={e => setBidAmount(e.target.value)} required
                    />
                    <textarea 
                        placeholder="Why are you the best fit?" 
                        className="w-full p-4 border rounded-xl h-32"
                        value={message} onChange={e => setMessage(e.target.value)} required
                    ></textarea>
                    <button className="w-full bg-blue-600 text-white p-4 rounded-xl font-bold text-lg hover:bg-blue-700 transition">
                        Submit Proposal 🚀
                    </button>
                </form>
            </div>
        </div>
    );
};

export default ProjectView;