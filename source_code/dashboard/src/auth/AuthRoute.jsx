import {Outlet, useNavigate} from "react-router-dom";
import React, {useEffect, useState} from "react";
import authService from "./AuthService.js";

export const AuthRoute = () => {
    const [loading, setLoading] = useState(true);
    const navigate = useNavigate();

    useEffect(() => {
        authService.isLoggedIn()
            .then(valid => {
                // Redirect if not logged in
                setLoading(false)
                if (valid) {
                    navigate('/dashboard', { replace: true });
                }
            })
            .catch(error => {
                console.error('Token verification error:', error);
            });
    }, []);

    if(loading){
        return null
    }

    return <Outlet />;
}