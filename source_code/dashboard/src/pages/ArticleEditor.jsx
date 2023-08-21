import RichTextEditor from "../components/Articles/RichTextEditor.jsx";
import {useEffect, useState} from "react";
import DataList from "../components/Articles/DataList.jsx";
import TagInput from "../components/Articles/TagInput.jsx";
import ThumbnailUpload from "../components/Articles/ThumbnailUpload.jsx";
import axios from "axios";
import {SaveLoading} from "../components/Shared/SaveLoading";
import Swal from "sweetalert2";
import {useNavigate, useParams} from "react-router-dom";

export const ArticleEditor = () => {
    const navigate = useNavigate();
    const [isPublic, setIsPublic] = useState(false);
    const [thumbnailUrl, setThumbnailUrl] = useState('');
    const [selectedCategories, setSelectedCategories] = useState([]);
    const [selectedTags, setSelectedTags] = useState([]);
    const [title, setTitle] = useState('');
    const [subTitle, setSubTitle] = useState('');
    const [content, setContent] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [isPinned, setIsPinned] = useState(false);

    const { id: articleId } = useParams();
    const [dataLoaded, setDataLoaded] = useState(false);

    useEffect(() => {
        if (articleId) {
            // Fetch existing article data and set the state
            fetchArticleData();
        } else {
            // Set the state for new article creation
            setDataLoaded(true);
        }
    }, [articleId]);

    const fetchArticleData = async () => {
        try {
            const response = await axios.get(`http://localhost/api/requests/article/get-article.php?id=${articleId}`);
            const existingArticleData = response.data;
            // Set the state with existing article data
            setIsPublic(existingArticleData.is_public);
            setThumbnailUrl(existingArticleData.thumbnail_url);
            setSelectedCategories(existingArticleData.categories);
            setSelectedTags(existingArticleData.tags.split(', '));
            setTitle(existingArticleData.title);
            setSubTitle(existingArticleData.subtitle);
            setContent(existingArticleData.content);
            setIsPinned(existingArticleData.is_pinned);
            setDataLoaded(true);
        } catch (error) {
            console.error("Error fetching article:", error);
        }
    };

    const handleSubmit = async () => {
        let article = {
            isPublic,
            isPinned,
            thumbnailUrl,
            selectedCategoriesIds: selectedCategories.map((item) => item.id),
            selectedTags: selectedTags.join(', '),
            title,
            subTitle,
            content}
        setIsLoading(true);

        if (articleId) {
            article = {...article, id: articleId};
            console.log({article})
            // Update existing article logic
            try {
                const response = await axios.put(`http://localhost/api/requests/article/update-article.php`, article);
                console.log(response)
                if (response.status === 200) {
                    console.log("Article updated:", response.data);
                    setIsLoading(false);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Your changes have been saved',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Navigate back to the articles page or the updated article's page
                        navigate("../articles"); // Adjust the route based on your project structure
                    });
                } else {
                    console.error("Unable to update article.");
                }
            } catch (error) {
                console.error("Error updating article:", error);
            }
        } else {
            // Create new article logic
            try {
                const response = await axios.post("http://localhost/api/requests/article/create-article.php", article);
                if(response.status === 201) {
                    console.log("Article created:", response.data);
                    setIsLoading(false);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Your work has been saved',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Navigate to the articles page
                        navigate("../articles"); // Replace with your articles page route
                    });
                }else{
                    console.error("Unable to create article.");
                }

            } catch (error) {
                console.error("Error creating article:", error);
            }
        }

        setIsLoading(false);
    };

    const handleCategoriesChange = (selectedItems) =>{
        setSelectedCategories(selectedItems)
    }

    const handleTagsChange = (updatedTags) => {
        setSelectedTags(updatedTags)
    }

    return (
        <main className="article-editor-page">
            <section className="header-bar flex-between">
                <p>unsaved changes</p>
                <div className="buttons flex-container">
                    <button className="save" onClick={handleSubmit}>save</button>
                    <button className="cancel">cancel</button>
                </div>
            </section>
            <section className="card article-details">
                <div className="title-container">
                    <label htmlFor="title">Title</label><br/>
                    <input type="text" name="title" id="title" value={title} onChange={(e)=>setTitle(e.target.value)}/>
                </div>
                <div className="subtitle-container">
                    <label htmlFor="subtitle">Sub Title</label><br/>
                    <textarea name="subtitle" id="subtitle" value={subTitle} onChange={(e)=>setSubTitle(e.target.value)}/>
                </div>
            </section>
            <section className="card article-info">
                <div className="container visibility">
                    <div className="toggle">
                        <span onClick={() => setIsPublic(false)}>Draft</span>
                        <input type="checkbox" checked={isPublic} onChange={(e) => setIsPublic(e.target.checked)}
                               name="isPublic" id="isPublic"/>
                        <span onClick={() => setIsPublic(true)}>Publish</span>
                    </div>
                </div>
                <hr/>
                <div className="container pinned">
                    <input type="checkbox" checked={isPinned} onChange={(e) => setIsPinned(e.target.checked)}
                           name="isPinned" id="isPinned"/>
                    <label htmlFor="isPinned">{isPinned ? 'Pinned' : 'unPinned'}</label>
                </div>
                <hr/>
                <div className="container thumbnail">
                    <div className="label">thumbnail</div>
                    {dataLoaded && <ThumbnailUpload
                        existingThumbnailUrl={thumbnailUrl}
                        onUpload={(url) => setThumbnailUrl(url)}/>}
                </div>
                <hr/>
                <div className="container categories">
                    <div className="label">categories</div>
                    {dataLoaded && <DataList preSelectedCategories={selectedCategories} onCategoriesChange={handleCategoriesChange} />}
                </div>
                <hr/>
                <div className="container tags">
                    <div className="label">Topics</div>
                    {dataLoaded && <TagInput preSelectedTags={selectedTags} onTagsChange={handleTagsChange}/>}
                </div>
            </section>
            <section className="card article-container">
                <RichTextEditor article_content={content} onWriting={(content)=>setContent(content)}/>
            </section>
            {isLoading ? <SaveLoading/> : null}
        </main>

    )
}