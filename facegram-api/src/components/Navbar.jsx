import axios from "axios";
import React, { useEffect, useState } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";

export default function Navbar() {
    const [isLogin, setIsLogin] = useState(false);

    const navigate = useNavigate();
    const location = useLocation();

    useEffect(() => {
        axios.get('http://localhost:8000/api/auth/me', {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('user-token'),
            }
        })
        .then(res => {
            setIsLogin(true);
            if (location.pathname === "/login") {
                navigate('/profile');
            }
        })
        .catch(err => {
            setIsLogin(false);
            if (err.response == 401 && location.pathname != '/login') {
                navigate('/login?message=' + encodeURIComponent('Anda belum login!'));
            }
        })
    }, [navigate]);
    return (
        <div className="bg-blue-600 py-2">
            <div className="grid grid-cols-12">
                <section className="col-span-10 col-start-2">
                    <div className="flex items-center justify-between">
                        <div>
                        <Link className="mr-2 text-sm font-semibold uppercase text-white" to="/">
                            Inventaris App
                        </Link>
                        <Link to="/login">
                            <small className="text-white">
                                Login
                            </small>
                        </Link>
                        </div>
                        {
                            isLogin ? (<Link to="/profile"><small className="text-white">Profile</small></Link>) : ''
                        }
                    </div>
                </section>
            </div>
        </div>
    )
}