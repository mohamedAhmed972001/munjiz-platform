import React from 'react';
import { Link } from 'react-router-dom'; // استيراد الـ Link للتنقل

const ClientDashboard = ({ user, projects, navigate }) => {
    return (
        <div className="bg-white p-6 rounded-2xl shadow-md text-left" dir="ltr">
            {/* الهيدر الجديد اللي فيه العنوان وزرار الإضافة */}
            <div className="flex justify-between items-center mb-6">
                <div>
                    <h2 className="text-2xl font-bold text-gray-800">My Projects</h2>
                    <p className="text-sm text-gray-500">Manage and track your posted jobs</p>
                </div>
                
                {/* زرار إضافة مشروع جديد يوجه لصفحة CreateProject */}
                <button 
                    onClick={() => navigate("/projects/create")}
                    className="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100 flex items-center gap-2"
                >
                    <span className="text-xl">+</span> Post a Project
                </button>
            </div>

            <div className="grid gap-4">
                {/* بنعمل Loop على المشاريع */}
                {projects.length > 0 ? (
                    projects.map(p => (
                        <Link 
                            to={`/projects/${p.id}`} 
                            key={p.id} 
                            className="p-5 border border-gray-100 rounded-2xl shadow-sm hover:border-blue-500 hover:bg-blue-50 transition block"
                        >
                            <div className="flex justify-between items-center">
                                <div>
                                    <h3 className="font-bold text-lg text-blue-600">{p.title}</h3>
                                    <p className="text-sm font-semibold text-green-600 mt-1">${p.budget}</p>
                                </div>
                                <div className="flex items-center gap-2 text-gray-400 font-bold text-sm">
                                    View Details <span className="text-lg">→</span>
                                </div>
                            </div>
                        </Link>
                    ))
                ) : (
                    <div className="text-center py-10 border-2 border-dashed rounded-2xl text-gray-400">
                        No projects posted yet. Click "Post a Project" to start!
                    </div>
                )}
            </div>
        </div>
    );
};

export default ClientDashboard;