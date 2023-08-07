import {Link} from "react-router-dom";

export const NavItem = ({target, icon, label, isActive, onClick}) => {
    return (
        <Link to={target} className={`nav-item ${isActive ? "active" : ""}`} onClick={onClick}>
            <div className="icon">
                {icon}
            </div>
            <span>{label}</span>
        </Link>
    )
}