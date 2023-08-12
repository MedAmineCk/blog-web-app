import axios from 'axios';

let authToken = localStorage.getItem('authToken'); // Get the token from localStorage

const authService = {
    login: async (email, password) => {
        try {
            const response = await axios.post('http://localhost/api/requests/auth/log-in.php', { email, password });
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    // Set the token to localStorage and the module's authToken variable
    setToken: (token) => {
        authToken = token;
        localStorage.setItem('authToken', token);
    },

    // Get the token
    getToken: () => {
        return authToken;
    },

    verifyToken: async (token) => {
        try {
            const response = await axios.get(`http://localhost/api/requests/auth/verify-token.php?token=${token}`);
            return response.data.valid === true; // Return a boolean value based on the response
        } catch (error) {
            throw error;
        }
    },

    // Check if the user is logged in based on the token
    isLoggedIn: async () => {
        if (authService.getToken()) {
            return await authService.verifyToken(authService.getToken()); // Verify the token
        }
        return false;
    },

    logout: () => {
        authService.removeToken();
    },

    // Remove the token from localStorage and the module's variable
    removeToken: () => {
        authToken = null;
        localStorage.removeItem('authToken');
    },
};

export default authService;
