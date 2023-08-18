import {ArticleCard} from "../components/ArticleCard";
import filters from "../data/filters.js";
import {Filter} from "../components/ui/Filter";
import {useEffect, useState} from "react";
import axios from "axios";

export const Home = () => {
    const [articles, setArticles] = useState([]);

    useEffect(() => {
        fetchArticles();
    }, []);

    const fetchArticles = async () => {
        try {
            const response = await axios.get("http://localhost/api/requests/article/get-articles.php");
            setArticles(response.data);
        } catch (error) {
            console.error("Error fetching articles:", error);
        }
    }

    console.log(articles)
    return (
        <div className="home-page">
            <div className="main-section futures-section">
                {articles.filter((article) => article.is_pinned === 1).map((article, index) =>
                    <ArticleCard article={article} key={index}/>
                )}
            </div>
            <div className="main-section filters-sections">
                {filters.map((filter, index) =>
                    <Filter filter={filter} key={index}/>
                )}
            </div>
            <div className="main-section articles-section">
                {articles.filter((article) => article.is_pinned === 0)?.map((article, index) =>
                    <ArticleCard article={article} key={index}/>
                )}
            </div>
        </div>
    )
}