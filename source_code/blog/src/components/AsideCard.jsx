import {UrlIcon} from "./ui/IconContainer.jsx";
import {Link} from "react-router-dom";
import PropTypes from "prop-types";

export const AsideCard = ({label, url, icon, children}) => {
    return (
        <div className="aside-card card">
            <div className="card-label">
                <h1 className="label">{label}</h1>
                {icon}
            </div>
            <div className="card-content">{children}</div>
            <div className="see-more">
                <Link to={url}>See more <UrlIcon/></Link>
            </div>
        </div>
    )
}

AsideCard.propTypes = {
    label: PropTypes.string.isRequired,
    url: PropTypes.string.isRequired,
    icon: PropTypes.element,
    children: PropTypes.node.isRequired
}