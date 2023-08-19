import {Card} from "../components/Shared/Card.jsx";
import {FiSearch} from "react-icons/fi";
import {BiSort} from "react-icons/bi";
import {RiArrowDropDownLine} from "react-icons/ri";
import articles from "../data/articles.js";
import {ArticleItem} from "../components/Articles/ArticleItem.jsx";
import {useEffect, useRef, useState} from "react";
import {DropdownPopup} from "../components/Shared/DropdownPopup";
import axios from "axios";
import Swal from "sweetalert2";

export const Articles = () => {
    const filters = ["All", "Articles", "Reviews"];
    const [activeFilter, setActiveFilter] = useState("All");

    const handelFilterClick = (filter) => {
        setActiveFilter(filter)
    }

    const [isOptionsOpen, setIsOptionsOpen] = useState(false);
    const handleOptionsOpen = () => {
        setIsOptionsOpen(!isOptionsOpen)
    }
    const optionsWrapper = useRef(null);
    const handleClickOutside = (event) => {
        if (optionsWrapper.current && !optionsWrapper.current.contains(event.target)) {
            setIsOptionsOpen(false)
        }
    }

    useEffect(() => {
        document.addEventListener('mousedown', handleClickOutside)
        return () => {
            document.removeEventListener('mousedown', handleClickOutside)
        };
    }, []);

    const [isTotalCheckboxChecked, setIsTotalCheckboxChecked] = useState(false);
    const handleTotalCheckboxChange = (event) => {
        setIsTotalCheckboxChecked(event.target.checked)
    }

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

    const handleDeleteArticle =  (articleId) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await axios.delete(`http://localhost/api/requests/article/delete-article.php?id=${articleId}`);
                    if (response.status === 200) {
                        // Article was successfully deleted
                        setArticles(articles.filter(article => article.id !== articleId));
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    } else {
                        console.error("Error deleting article:", response.statusText);
                    }
                } catch (error) {
                    console.error("Error deleting article:", error);
                }
            }
        })

    };


    return (
        <main className="articles-page">
            <Card label="Filters" className="filters">
                <div className="types flex-container">
                    {filters.map((filter, index) => (
                        <div
                            key={index}
                            className={`type-item ${filter === activeFilter ? 'active' : ''}`}
                            onClick={() => handelFilterClick(filter)}
                        >{filter}</div>
                    ))}
                </div>
                <div className="flex-between">
                    <div className="search-input flex-container">
                        <div className="icon flex-center">
                            <FiSearch/>
                        </div>
                        <input type="text" placeholder="search"/>
                    </div>
                    <select name="categories" id="categories">
                        <option value="cat-1">cat-1</option>
                        <option value="cat-2">cat-2</option>
                        <option value="cat-3">cat-3</option>
                    </select>
                    <div className="sort flex-container">
                        <span>sort</span>
                        <div className="icon">
                            <BiSort/>
                        </div>
                    </div>
                </div>
            </Card>
            <Card label="Articles" className="articles">
                <table>
                    <thead>
                    <tr>
                        <th>
                            <div className="checklist flex-container" ref={optionsWrapper}>
                                <input type="checkbox" checked={isTotalCheckboxChecked}
                                       onChange={handleTotalCheckboxChange}/>
                                <div className="icon flex-center" onClick={handleOptionsOpen}>
                                    <RiArrowDropDownLine/>
                                </div>
                                <DropdownPopup isOpen={isOptionsOpen}>
                                    <button className="published">published</button>
                                    <button className="unpublished">unPublished</button>
                                    <button className="delete">Delete</button>
                                </DropdownPopup>
                            </div>
                        </th>
                        <th>article</th>
                        <th></th>
                        <th style={{textAlign: "start"}}>category</th>
                        <th style={{textAlign: "start"}}>status</th>
                        <th>data</th>
                        <th>control</th>
                    </tr>
                    </thead>
                    <tbody>
                    {articles.map((article, index) => (
                        <ArticleItem
                            key={index}
                            article={article}
                            totalChecked={isTotalCheckboxChecked}
                            onDeleting={(id)=>handleDeleteArticle(id)}
                        />
                    ))}
                    </tbody>
                </table>
            </Card>
        </main>
    )
}