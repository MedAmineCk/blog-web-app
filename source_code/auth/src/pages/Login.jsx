import {useState} from "react";
import "../styles/style.scss";
import {FcGoogle} from "react-icons/fc";

export const Login = () => {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [isRemember, setIsRemember] = useState(false);
    const handleSubmit = (event) => {
        event.preventDefault()
    }
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
                                   value={username}
                                   onChange={(e)=> setUsername(e.target.value)}/>
                        </div>
                        <div className="input-container">
                            <label htmlFor="password">password</label><br/>
                            <input type="password" placeholder="password" name="password" id="password"
                                   value={password}
                                   onChange={(e)=> setPassword(e.target.value)}/>
                        </div>
                        <div className="remember">
                            <input type="checkbox"
                                   checked={isRemember}
                                   onChange={(e)=> setIsRemember(e.target.checked)}/>
                            <span>Remember me</span>
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