import {BookmarkIcon, LinkIcon, ShareIcon} from "./ui/IconContainer";
import {Link} from "react-router-dom";
import PropTypes from "prop-types";

export const ArticleCard = ({article}) => {
    const {id, thumbnail_url, subtitle,  title, is_public, created_at, tags, categories} = article;
    return (
        <div className="article-card card">
            <div className="article-thumbnail">
                <img src={`http://localhost/api/uploads/${thumbnail_url}`} alt={title}/>
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
                    <div className="time">{created_at}</div>
                    <Link to={`/article/${id}`} className="read-more">read more <LinkIcon/></Link>
                </div>
            </div>
        </div>
    )
}
