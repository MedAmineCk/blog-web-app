import {useState} from "react";
import "../styles/login.scss";
import {FcGoogle} from "react-icons/fc";
import {useNavigate} from "react-router-dom";
import authService from "./AuthService.js";

export const Login = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');

    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        console.log('login btn clicked!')

        try {
            // Call the login function from AuthService
            const response = await authService.login(email, password);
            console.log(response)
            if (response.permission) {
                // Successfully logged in, navigate to dashboard
                console.log('Logged in successfully');
                // Store the token
                authService.setToken(response.token);
                // Use navigate to go to dashboard
                navigate('/dashboard');

            } else {
                setError('Invalid email or password');
            }
        } catch (error) {
            setError('An error occurred');
        }
        console.error(error);
    };

    return (
        <main className="login-page">
            <div className="main-container">
                <div className="container">
                    <h1>Hey, Hello</h1>
                    <p className="label">Enter the information you entered while registering</p>

                    <form onSubmit={handleSubmit}>
                        <div className="input-container">
                            <label htmlFor="username">user name</label><br/>
                            <input type="text" placeholder="User Name" name="username" id="username"
                                   value={email}
                                   onChange={(e)=> setEmail(e.target.value)}/>
                        </div>
                        <div className="input-container">
                            <label htmlFor="password">password</label><br/>
                            <input type="password" placeholder="password" name="password" id="password"
                                   value={password}
                                   onChange={(e)=> setPassword(e.target.value)}/>
                        </div>
                        <button className="submit" type="submit">log in</button>
                        <div className="or-container">
                            <span>or</span>
                            <hr/>
                        </div>
                        <button className="google-signIn">
                            <span className="google-logo">
                                <FcGoogle/>
                            </span>
                            <p>sign  in with Google</p>
                        </button>
                    </form>
                </div>
            </div>
            <div className="cover">

                <p>
                    Words
                    weave
                    worlds.
                    Blog on.
                </p>
            </div>
        </main>
    )
}