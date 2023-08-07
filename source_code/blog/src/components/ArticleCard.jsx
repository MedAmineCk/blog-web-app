import {BookmarkIcon, LinkIcon, ShareIcon} from "./ui/IconContainer";
import {Link} from "react-router-dom";
import PropTypes from "prop-types";

export const ArticleCard = ({article}) => {
    const {id, title, subtitle, imageUrl, time} = article
    return (
        <div className="article-card card">
            <div className="article-thumbnail">
                <img src={imageUrl} alt={title}/>
            </div>
            <div className="article-content">
                <div className="options-btn">
                    <BookmarkIcon/>
                    <ShareIcon/>
                </div>
                <div className="article-details">
                    <h1 className="article-title">{title}</h1>
                    <p className="article-subtitle">{subtitle}</p>
                </div>
                <div className="article-footer">
                    <div className="time">{time}</div>
                    <Link to={`/article/${id}`} className="read-more">read more <LinkIcon/></Link>
                </div>
            </div>
        </div>
    )
}

ArticleCard.propTypes = {
    article: PropTypes.shape({
        id: PropTypes.number.isRequired,
        title: PropTypes.string.isRequired,
        subtitle: PropTypes.string.isRequired,
        imageUrl: PropTypes.string.isRequired,
        time: PropTypes.string.isRequired,
    }).isRequired,
};
