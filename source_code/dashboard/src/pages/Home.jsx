import {Card} from "../components/Shared/Card.jsx";
import {ChartComponent} from "../components/Home/ChartUi.jsx";
import {CategoryData} from "../components/Home/CategoryData.jsx";
import {ArticleItem} from "../components/Home/ArticleItem.jsx";
import {DataItem} from "../components/ui/DataItem.jsx";

import articles from "../data/articles.js";
import ads from "../data/ads.js";

export const Home = () => {
    return (
        <main className="home-page">
            <Card label="Chart" className="chart">
                <ChartComponent/>
            </Card>
            <Card label="Data" className="data">
                <DataItem label="views"/>
                <DataItem label="reads"/>
                <DataItem label="comments"/>
                <DataItem label="Ads Clicks"/>
            </Card>
            <Card label="Categories" className="categories">
                <CategoryData label="non Fiction" data="968" percentage="50"/>
                <CategoryData label="Fiction" data="402" percentage="35"/>
                <CategoryData label="Science" data="84" percentage="20"/>
                <CategoryData label="Others" data="26" percentage="5"/>
            </Card>
            <Card label="Top Articles" className="articles">
                <table>
                    <tbody>
                    {articles.map((article, index) => (
                        <ArticleItem
                            index={index} key={index}
                            article={article.articleObj}
                            data={article.dataObj}/>
                    ))}
                    </tbody>
                </table>
            </Card>
            <Card label="Top Ads" className="ads">
                <table>
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Type</th>
                        <th>reach</th>
                        <th>clicks</th>
                    </tr>
                    </thead>
                    <tbody>
                    {ads.map((adObj, index) => (
                        <tr key={index}>
                            <td>#{adObj.id}</td>
                            <td>{adObj.type}</td>
                            <td>{adObj.reach}</td>
                            <td>{adObj.clicks}</td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            </Card>
        </main>
    )
}