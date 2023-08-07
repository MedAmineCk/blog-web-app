import {ArticleCard} from "../components/ArticleCard";
import articles from "../data/articles.js"
import filters from "../data/filters.js";
import {Filter} from "../components/ui/Filter";

export const Home = () => {
    return (
        <div className="home-page">
            <div className="main-section futures-section">
                {articles.filter((article) => article.isPined === true).map((article, index) =>
                    <ArticleCard article={article} key={index}/>
                )}
            </div>
            <div className="main-section filters-sections">
                {filters.map((filter, index) =>
                    <Filter filter={filter} key={index}/>
                )}
            </div>
            <div className="main-section articles-section">
                {articles.map((article, index) =>
                    <ArticleCard article={article} key={index}/>
                )}
            </div>
        </div>
    )
}