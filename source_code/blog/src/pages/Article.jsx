import {BookmarkIcon, ShareIcon} from "../components/ui/IconContainer.jsx";
import {useParams} from "react-router-dom";
import {useEffect, useState} from "react";
import axios from "axios";

export const Article = () => {
    const { id } = useParams();
    const [article, setArticle] = useState(null);

    useEffect(() => {
        const fetchArticle = async () => {
            try {
                const response = await axios.get(`http://localhost/api/requests/article/get-article.php?id=${id}`);
                setArticle(response.data);
            } catch (error) {
                console.error("Error fetching article:", error);
            }
        };

        fetchArticle();

    }, [id]);

    const {thumbnail_url, subtitle,  title, is_public, created_at, tags, categories, content} = article ?? {};
    return (
        <div className="article">
            <div className="options-btn">
                <BookmarkIcon id={id}/>
                <ShareIcon id={id}/>
            </div>
            <div className="article-details">
                <h1 className="article-title">{title}</h1>
                <p className="article-subtitle">{subtitle}</p>
                <div className="date">{created_at}</div>
            </div>

            <div className="thumbnail">
                <img src={`http://localhost/api/uploads/${thumbnail_url}`} alt={title}/>
            </div>
            <div className="content" dangerouslySetInnerHTML={{__html: content}}></div>
        </div>
    )
}