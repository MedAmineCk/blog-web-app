import RichTextEditor from "../components/Articles/RichTextEditor.jsx";

export const ArticleEditor = () => {
    return (
        <main className="article-editor-page">
            <section className="header-bar flex-between">
                <p>unsaved changes</p>
                <div className="buttons flex-container">
                    <button className="save">save</button>
                    <button className="cancel">cancel</button>
                </div>
            </section>
            <section className="card article-details">
                <div className="title-container">
                    <label htmlFor="title">Title</label><br/>
                    <input type="text" name="title" id="title"/>
                </div>
                <div className="subtitle-container">
                    <label htmlFor="subtitle">Sub Title</label><br/>
                    <textarea name="subtitle" id="subtitle"/>
                </div>
            </section>
            <section className="card article-info">
                <div className="container">
                    <div className="label">visibility</div>
                    <label htmlFor="isPublic">publish</label>
                    <input type="checkbox" name="isPublic" id="isPublic"/>
                </div>
                <hr/>
                <div className="container">
                    <div className="label">thumbnail</div>
                    <div className="thumbnail-container"></div>
                    <div className="buttons">
                        <button>upload</button>
                        <button>cancel</button>
                    </div>
                </div>
                <hr/>
                <div className="container">
                    <div className="label">categories</div>
                    <select name="categories" id="categories">
                        <option value="cat_1">cat_1</option>
                        <option value="cat_2">cat_2</option>
                        <option value="cat_3">cat_3</option>
                    </select>
                    <div className="selected-categories">
                        <div className="cat-item">cat_1</div>
                        <div className="cat-item">cat_2</div>
                    </div>
                </div>
                <hr/>
                <div className="container">
                    <div className="label">Topics</div>
                    <input type="text" id="topics"/>
                    <div className="added-topics">
                        <div className="topic-item">topic_1</div>
                        <div className="topic-item">topic_2</div>
                    </div>
                </div>
            </section>
            <section className="card article-container">
                <RichTextEditor />
            </section>
        </main>
    )
}