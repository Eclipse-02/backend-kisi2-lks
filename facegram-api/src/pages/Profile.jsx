import React, { useState, useEffect } from "react";
import Case from "../components/Case";
import axios from "axios";
import { useNavigate } from "react-router-dom";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

export default function Profile() {
    const [profile, setProfile] = useState([]);

    const navigate = useNavigate();
    useEffect(() => {
        axios.get('http://localhost:8000/api/auth/me', {
            headers: {
                "Authorization": "Bearer " + localStorage.getItem('user-token'),
            }
        })
        .then(res => {
            setProfile(res.data);
        })
        .catch(err => {
            console.log(err);
            if (err.response.status == 401) {
                navigate('/login?message=' + encodeURIComponent('Anda belum login!'));
            }
        })
    })

    const handleLogout = (event) => {
        event.preventDefault();
        axios.post('http://localhost:8000/api/auth/logout', [], {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('user-token'),
            }
        })
        .then(res => {
            localStorage.removeItem('user-token');
            navigate('/login');
        })
        .catch(err => {
            console.log(err);
        })
    }
    return (
        <Case>
            <div className="block m-auto mt-10 w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div className="flex flex-col items-center pb-10 pt-10">
                    <FontAwesomeIcon icon="fa-solid fa-user" className="w-20 h-20 mb-3 text-gray-500" />
                    <h5 className="mb-1 text-xl font-medium text-gray-900 dark:text-white">{profile.full_name}</h5>
                    <span className="text-sm text-gray-500 dark:text-gray-400">{profile.bio}</span>
                    <div className="flex mt-4 md:mt-6">
                        <a href="#" className="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Dashboard
                        </a>
                        <a href="#" onClick={handleLogout} className="py-2 px-4 ms-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </Case>
    )
}