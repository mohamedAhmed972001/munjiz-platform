import React from 'react';

const Dashboard = () => {
    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100">
            <div className="bg-white p-8 rounded-lg shadow-md text-center">
                <h1 className="text-3xl font-bold text-blue-600 mb-4">Welcome to Munjiz Dashboard 🎊</h1>
                <p className="text-gray-600">You have successfully logged in!</p>
            </div>
        </div>
    );
};

// السطر ده هو اللي بيخلي App.jsx يشوف الملف
export default Dashboard;