import {Link} from "react-router-dom";
import PropTypes from "prop-types";

export const Filter = ({filter}) => {
    const {id, name} = filter
    return (
        <Link to={`/Articles/category/${id}`} className="topic-btn">
            {name}
        </Link>
    )
}

Filter.propTypes = {
    filter: PropTypes.shape({
        id: PropTypes.number.isRequired,
        name: PropTypes.string.isRequired,
    }).isRequired,
};