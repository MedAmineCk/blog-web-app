import React, {useEffect, useState} from 'react';
import {Outlet, useNavigate} from 'react-router-dom';
import authService from "./AuthService.js";

function PrivateRoute() {
    const [loading, setLoading] = useState(true); // Add loading state
    const navigate = useNavigate();

    useEffect(() => {
        authService.isLoggedIn()
            .then(valid => {
                // Redirect if not logged in
                setLoading(false)
                if (!valid) {
                    navigate('/auth', { replace: true });
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


export default PrivateRoute;
