import {Link} from "react-router-dom";
import PropTypes from "prop-types";
import {AddIcon} from "./IconContainer.jsx";

export const Topic = ({topic}) => {
    const {id, name} = topic
    return (
        <Link to={`/Articles/category/${id}`} className="topic-btn">
            {name}
            <AddIcon/>
        </Link>
    )
}


Topic.propTypes = {
    topic: PropTypes.shape({
        id: PropTypes.number.isRequired,
        name: PropTypes.string.isRequired,
    }).isRequired,
};