import logo from "../assets/logo.svg";
import {Link} from "react-router-dom";
import {BookmarkIcon} from "./ui/IconContainer";

export const Header = () => {
    return (
        <header>
            <div className="section-container">
                <Link to='/' className="logo">
                    <img src={logo} alt="valuable Articles logo"/>
                </Link>
                <div className="search-input card">
                    <input type="text" placeholder="search"/>
                </div>
                <div className="icon-container">
                    <BookmarkIcon/>
                </div>
            </div>
        </header>
    )
}