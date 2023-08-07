import {AuthorUi} from "./AuthorUi";
import PropTypes from "prop-types";

export const TrendArticle = ({book}) => {
    const {title, author} = book
    return (
        <div className="trend-Article">
            <div className="title">{title}</div>
            <AuthorUi author={author}/>
        </div>
    )
}

TrendArticle.propTypes = {
    book: PropTypes.shape({
        title: PropTypes.string.isRequired,
        author: PropTypes.string.isRequired
    }).isRequired
}