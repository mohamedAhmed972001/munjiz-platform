import React, { useState, useEffect } from 'react';
import { authService } from '../api/authService';
import { useNavigate } from 'react-router-dom';

const CompleteProfile = () => {
    const [skills, setSkills] = useState([]); 
    const [selectedSkills, setSelectedSkills] = useState([]);
    const navigate = useNavigate();

    useEffect(() => {
        // جلب المهارات من الباك إند أول ما الصفحة تحمل
        const getSkillsData = async () => {
            try {
                const data = await authService.getSkills();
                setSkills(data);
            } catch (err) { console.log(err); }
        };
        getSkillsData();
    }, []);

    const handleSave = async () => {
        try {
            // حفظ المهارات المختارة
            await authService.updateSkills({ skills: selectedSkills });
            navigate('/dashboard'); 
        } catch (err) { alert("Error saving skills"); }
    };

    return (
        <div className="p-10" dir="rtl">
            <h2 className="text-xl font-bold mb-4">اختار مهاراتك يا هندسة:</h2>
            <div className="flex flex-wrap gap-2">
                {skills.map(skill => (
                    <button 
                        key={skill.id}
                        onClick={() => setSelectedSkills(prev => [...prev, skill.id])}
                        className={`p-2 border rounded ${selectedSkills.includes(skill.id) ? 'bg-blue-500 text-white' : 'bg-white'}`}
                    >
                        {skill.name}
                    </button>
                ))}
            </div>
            <button onClick={handleSave} className="mt-6 bg-green-600 text-white p-3 rounded">حفظ والمتابعة</button>
        </div>
    );
};

export default CompleteProfile;