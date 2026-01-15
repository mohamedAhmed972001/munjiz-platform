import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { authService } from '../../api/authService';

const ProjectManager = () => {
    const { id } = useParams();
    const navigate = useNavigate();
    const [project, setProject] = useState(null);
    const [bids, setBids] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchDetails = async () => {
            try {
                // نجيب المشروع
                const projRes = await authService.getProjectById(id);
                setProject(projRes.data ? projRes.data : projRes);
                
                // نجيب العروض (لأننا هنا متأكدين إنه كلاينت)
                const bidsRes = await authService.getBidsForProject(id);
                setBids(bidsRes.data || bidsRes);
            } catch (err) {
                console.error(err);
                // لو حصل إيرور (مثلاً هو مش صاحب المشروع)، السيرفر هيرد بـ 403
                // وقتها نرجعه للداشبورد
                navigate('/client/dashboard');
            } finally {
                setLoading(false);
            }
        };
        fetchDetails();
    }, [id, navigate]);

    // دالة حذف المشروع
    const handleDelete = async () => {
        if (window.confirm("Delete this project permanently?")) {
            await authService.deleteProject(id);
            navigate('/client/dashboard');
        }
    };

    // دالة قبول العرض (لسه هنظبطها في الباك-إند بعدين)
    const handleAcceptBid = (bidId) => {
        alert(`Accepting bid ${bidId}... logic coming soon!`);
    };

    if (loading) return <div>Loading Management Tools...</div>;

    return (
        <div className="p-8 max-w-5xl mx-auto">
            {/* Header */}
            <div className="flex justify-between items-center mb-8 border-b pb-4">
                <h1 className="text-3xl font-black">{project.title}</h1>
                <div className="space-x-4">
                    <button onClick={() => navigate(`/client/projects/edit/${id}`)} className="text-blue-600 font-bold">Edit</button>
                    <button onClick={handleDelete} className="text-red-600 font-bold border border-red-200 px-4 py-2 rounded-lg hover:bg-red-50">Delete Project</button>
                </div>
            </div>

            {/* Bids Section - دي المنطقة الخاصة بالكلاينت بس */}
            <div className="bg-white rounded-xl shadow-sm border p-6">
                <h2 className="text-xl font-bold mb-4">Received Proposals ({bids.length})</h2>
                {bids.length === 0 ? <p className="text-gray-400">No bids yet.</p> : (
                    <div className="space-y-4">
                        {bids.map(bid => (
                            <div key={bid.id} className="flex justify-between items-center p-4 bg-gray-50 rounded-lg border hover:border-blue-300 transition">
                                <div>
                                    <h4 className="font-bold">{bid.user.name}</h4>
                                    <p className="text-sm text-gray-600 mt-1">{bid.message}</p>
                                    <span className="text-xs text-gray-400 block mt-2">{new Date(bid.created_at).toDateString()}</span>
                                </div>
                                <div className="text-right">
                                    <div className="text-2xl font-black text-green-600">${bid.amount}</div>
                                    {bid.status === 'pending' && (
                                        <button 
                                            onClick={() => handleAcceptBid(bid.id)}
                                            className="mt-2 bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800"
                                        >
                                            Accept Offer
                                        </button>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
};

export default ProjectManager;