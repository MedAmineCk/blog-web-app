import {BookmarkIcon, ShareIcon} from "../components/ui/IconContainer.jsx";
import {useParams} from "react-router-dom";
import articles from "../data/articles.js";


export const Article = () => {
    const {id} = useParams();
    const article = articles.find((article) => article.id === parseInt(id));
    const {title, subtitle, date, imageUrl, content} = article;
    return (
        <div className="article">
            <div className="options-btn">
                <BookmarkIcon id={id}/>
                <ShareIcon id={id}/>
            </div>
            <div className="article-details">
                <h1 className="article-title">{title}</h1>
                <p className="article-subtitle">{subtitle}</p>
                <div className="date">{date}</div>
            </div>

            <div className="thumbnail">
                <img src={imageUrl} alt={title}/>
            </div>
            <div className="content" dangerouslySetInnerHTML={{__html: content}}></div>
        </div>
    )
}