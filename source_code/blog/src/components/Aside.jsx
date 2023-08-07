import {AsideCard} from "./AsideCard";
import {AuthorIcon, TrendIcon} from "./ui/IconContainer";
import {TrendArticle} from "./ui/TrendArticle";
import trending_books from "../data/trending_books.js";
import {AuthorUi} from "./ui/AuthorUi";
import topics from "../data/topics.js";
import {Topic} from "./ui/Topic.jsx";

export const Aside = () => {
    return (
        <aside>
            <AsideCard
                label="Today’s top trend"
                url="#"
                icon={<TrendIcon/>}
            >
                <div className="container trends">
                    {
                        trending_books.map((book, index) => <TrendArticle book={book} key={index}/>)
                    }
                </div>
            </AsideCard>
            <AsideCard
                label="Authors"
                url="#"
                icon={<AuthorIcon/>}
            >
                <div className="container">
                    {
                        trending_books.map((book, index) => <AuthorUi author={book.author} key={index}/>)
                    }
                </div>
            </AsideCard>
            <AsideCard label="Topics for you" url="#">
                <div className="container topics">
                    {
                        topics.map((topic, index) => <Topic topic={topic} key={index}/>)
                    }
                </div>
            </AsideCard>
        </aside>
    )
}