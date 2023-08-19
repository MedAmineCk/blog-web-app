import RichTextEditor from "../components/Articles/RichTextEditor.jsx";
import {useState} from "react";
import DataList from "../components/Articles/DataList.jsx";
import TagInput from "../components/Articles/TagInput.jsx";
import ThumbnailUpload from "../components/Articles/ThumbnailUpload.jsx";
import axios from "axios";
import {SaveLoading} from "../components/Shared/SaveLoading";
import Swal from "sweetalert2";
import {useNavigate} from "react-router-dom";
import {MdOutlineImageNotSupported} from "react-icons/md";

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

    const article = {
        isPublic,
        isPinned,
        thumbnailUrl,
        selectedCategoriesIds: selectedCategories.map((item) => item.id),
        selectedTags: selectedTags.join(', '),
        title,
        subTitle,
        content}

    const handleSubmit = async () => {
        console.log({article});
        setIsLoading(true);

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

        setIsLoading(false);
    };

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
                    <ThumbnailUpload onUpload={(url)=>setThumbnailUrl(url)}/>
                </div>
                <hr/>
                <div className="container categories">
                    <div className="label">categories</div>
                    <DataList onCategoriesChange={(selectedItems)=>setSelectedCategories(selectedItems)}/>
                </div>
                <hr/>
                <div className="container tags">
                    <div className="label">Topics</div>
                    <TagInput onTagsChange={(updatedTags)=>setSelectedTags(updatedTags)}/>
                </div>
            </section>
            <section className="card article-container">
                <RichTextEditor onWriting={(content)=>setContent(content)}/>
            </section>
            {isLoading ? <SaveLoading/> : null}
        </main>

    )
}