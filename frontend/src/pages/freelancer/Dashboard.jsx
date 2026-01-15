import React, { useEffect, useState } from 'react';
import { authService } from '../../api/authService';
import { Link } from 'react-router-dom';

const FreelancerDashboard = ({ user }) => {
    const [allProjects, setAllProjects] = useState([]);
    const [loading, setLoading] = useState(true);

    // التصحيح هنا: لازم نستخدم getAllProjects مش getProjectById
    useEffect(() => {
        authService.getAllProjects()
            .then(res => {
                // بنشيك لو الداتا جاية مباشرة أو جوه data object
                const projectsData = res.data ? res.data : res;
                setAllProjects(projectsData);
            })
            .catch(err => {
                console.error("Marketplace Error:", err);
                setAllProjects([]);
            })
            .finally(() => setLoading(false));
    }, []);

    if (loading) return <div className="p-10 text-center font-bold">Loading Marketplace... 🚀</div>;

    return (
        <div className="space-y-6 text-left" dir="ltr">
            <header className="bg-gradient-to-r from-blue-600 to-indigo-700 p-10 rounded-3xl shadow-lg text-white">
                <h1 className="text-3xl font-black">Project Marketplace 🚀</h1>
                <p className="opacity-90 mt-2 text-lg">Explore new opportunities and start bidding.</p>
            </header>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {allProjects.length > 0 ? (
                    allProjects.map(project => (
                        <div key={project.id} className="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all group">
                            <div className="flex justify-between items-start mb-4">
                                <h3 className="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition">{project.title}</h3>
                                <span className="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                    ${project.budget}
                                </span>
                            </div>
                            <p className="text-gray-500 text-sm line-clamp-2 mb-6">{project.description}</p>
                            <div className="flex justify-between items-center pt-4 border-t border-gray-50">
                                <div className="flex flex-col">
                                    <span className="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Client</span>
                                    <span className="text-sm font-semibold text-gray-700">{project.owner?.name || 'Anonymous'}</span>
                                </div>
                                <Link 
                                    to={`/projects/${project.id}`} 
                                    className="bg-blue-50 text-blue-600 px-5 py-2 rounded-xl font-bold text-sm hover:bg-blue-600 hover:text-white transition-colors"
                                >
                                    View & Apply →
                                </Link>
                            </div>
                        </div>
                    ))
                ) : (
                    <div className="col-span-full bg-gray-100 p-10 rounded-3xl text-center text-gray-500 font-bold">
                        No projects available in the market right now.
                    </div>
                )}
            </div>
        </div>
    );
};

export default FreelancerDashboard;