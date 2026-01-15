import React from 'react';
import { Navigate, Outlet, useLocation } from 'react-router-dom';

const ProtectedRoute = ({ user, allowedRoles }) => {
    const location = useLocation();

    // 1. لو مفيش يوزر (السيشن خلصت أو مش عامل لوجين) -> على صفحة اللوجين
    if (!user) {
        return <Navigate to="/login" state={{ from: location }} replace />;
    }

    // 2. لو اليوزر موجود، بس بيحاول يدخل منطقة مش بتاعته
    // (مثلاً فريلانسر بيحاول يفتح /client/dashboard)
    if (allowedRoles && !allowedRoles.includes(user.role_name)) {
        // نرجعه للمسار الصحيح حسب رتبته
        const redirectPath = user.role_name === 'client' ? '/client/dashboard' : '/freelancer/dashboard';
        return <Navigate to={redirectPath} replace />;
    }

    // 3. كله تمام -> افتح الصفحة
    return <Outlet />;
};

export default ProtectedRoute;