import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { authService } from '../api/authService';

const ProjectDetails = ({ user }) => {
    const { id } = useParams();
    const navigate = useNavigate();
    const [project, setProject] = useState(null);
    const [bids, setBids] = useState([]); // مخزن العروض
    const [bidAmount, setBidAmount] = useState('');
    const [message, setMessage] = useState('');

    useEffect(() => {
        // 1. جلب بيانات المشروع
        authService.getProjectById(id)
            .then(res => {
                const data = res.data ? res.data : res;
                setProject(data);
                
                // 2. لو اليوزر هو صاحب المشروع، هات العروض اللي جت عليه
                if (user && (data.user_id === user.id)) {
                    authService.getBidsForProject(id).then(bidsRes => {
                        setBids(bidsRes.data || bidsRes);
                    });
                }
            })
            .catch(err => {
                console.error("Fetch error:", err);
                setProject(null);
            });
    }, [id, user]);

    const handleApply = async (e) => {
        e.preventDefault();
        try {
            await authService.applyForProject({
                project_id: id,
                amount: bidAmount,
                message: message
            });
            alert("Applied Successfully! 🚀");
            navigate('/dashboard');
        } catch (err) {
            const errorMsg = err.response?.data?.message || "Error applying for project.";
            alert(errorMsg);
        }
    };

    const handleDelete = async () => {
        if (window.confirm("Are you sure you want to delete this project?")) {
            try {
                await authService.deleteProject(id);
                alert("Deleted Successfully!");
                navigate('/dashboard');
            } catch (err) {
                alert("Error deleting project.");
            }
        }
    };

    if (!project) return <div className="p-20 text-center text-2xl font-bold">Loading Project details... ⏳</div>;

    return (
        <div className="max-w-4xl mx-auto p-8" dir="ltr">
            <div className="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                <div className="p-8 border-b border-gray-50 bg-gray-50/50">
                    <div className="flex justify-between items-center mb-4">
                        <h1 className="text-4xl font-black text-gray-900">{project.title}</h1>
                        <span className="text-2xl font-bold text-green-600">${project.budget}</span>
                    </div>
                    <p className="text-gray-600 leading-relaxed text-lg">{project.description}</p>
                </div>

                <div className="p-8">
                    {/* لو المستخدم فريلانسر: اظهر فورم التقديم */}
                    {user?.role_name === 'freelancer' ? (
                        <form onSubmit={handleApply} className="space-y-6">
                            <h2 className="text-2xl font-bold text-blue-600">Apply for this Project</h2>
                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-2">Your Bid Amount ($)</label>
                                <input 
                                    type="number" 
                                    value={bidAmount}
                                    onChange={(e) => setBidAmount(e.target.value)}
                                    className="w-full p-4 border-2 border-gray-100 rounded-2xl focus:border-blue-500 outline-none transition"
                                    placeholder="e.g. 100"
                                    required
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-2">Proposal Message</label>
                                <textarea 
                                    value={message}
                                    onChange={(e) => setMessage(e.target.value)}
                                    className="w-full p-4 border-2 border-gray-100 rounded-2xl focus:border-blue-500 outline-none transition h-32"
                                    placeholder="Why should the client hire you?"
                                    required
                                ></textarea>
                            </div>
                            <button type="submit" className="w-full bg-blue-600 text-white p-4 rounded-2xl font-bold text-xl hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                                Send Proposal ✈️
                            </button>
                        </form>
                    ) : (
                        <div className="space-y-8">
                            {/* زراير التحكم للكلاينت */}
                            <div className="flex gap-4">
                                <button onClick={() => navigate(`/projects/edit/${id}`)} className="flex-1 bg-blue-100 text-blue-700 p-4 rounded-2xl font-bold hover:bg-blue-200 transition">
                                    Edit Project ✏️
                                </button>
                                <button onClick={handleDelete} className="flex-1 bg-red-100 text-red-700 p-4 rounded-2xl font-bold hover:bg-red-200 transition">
                                    Delete Project 🗑️
                                </button>
                            </div>

                            {/* عرض العروض المستلمة للكلاينت */}
                            <div className="mt-10">
                                <h3 className="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-blue-600 pl-4">
                                    Received Proposals ({bids.length})
                                </h3>
                                <div className="space-y-4">
                                    {bids.length > 0 ? bids.map(bid => (
                                        <div key={bid.id} className="p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:border-blue-300 transition shadow-sm">
                                            <div className="flex justify-between items-start mb-3">
                                                <div>
                                                    <h4 className="font-bold text-lg text-gray-900">{bid.user?.name}</h4>
                                                    <span className="text-sm text-gray-500">{new Date(bid.created_at).toLocaleDateString()}</span>
                                                </div>
                                                <span className="text-xl font-black text-green-600">${bid.amount}</span>
                                            </div>
                                            <p className="text-gray-700 leading-relaxed italic">"{bid.message}"</p>
                                        </div>
                                    )) : (
                                        <p className="text-center text-gray-400 py-10">No proposals yet. Waiting for freelancers... 😴</p>
                                    )}
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default ProjectDetails;