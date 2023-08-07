import {AuthorIcon} from "./IconContainer.jsx";
import PropTypes from "prop-types";
import {Link} from "react-router-dom";

export const AuthorUi = ({author}) => {
    return (
        <Link to={"#"} className="author">
            <AuthorIcon/>
            {author}
        </Link>
    )
}

AuthorUi.propTypes = {
    author: PropTypes.string.isRequired
}