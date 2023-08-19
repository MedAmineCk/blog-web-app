import {CgEye} from "react-icons/cg";
import {AiOutlineComment} from "react-icons/ai";
import {MdDeleteForever, MdOutlineFavoriteBorder} from "react-icons/md";
import {BiLinkExternal, BiSolidEdit} from "react-icons/bi";
import {useEffect, useState} from "react";


export const ArticleItem = ({article, totalChecked, onDeleting}) => {
    const {id, thumbnail_url, subtitle,  title, is_public, created_at, tags, categories} = article;
    const reads = 0;
    const comments = 0;
    const favorites = 0;

    const [isArticleChecked, setIsArticleChecked] = useState(false);
    const handleArticleChange = (event) => {
        setIsArticleChecked(event.target.checked)
    }
    useEffect(() => {
        setIsArticleChecked(totalChecked)
    }, [totalChecked]);

    const handleArticleDeleting = ()=>{
        onDeleting(id)
    }

    return (
        <tr className="article">
            <td className="id">
                <input type="checkbox" checked={isArticleChecked} onChange={handleArticleChange}/>
            </td>
            <td className="thumbnail">
                <div className="thumbnail-container">
                    <img src={`http://localhost/api/uploads/${thumbnail_url}`} alt="article thumbnail"/>
                </div>
            </td>
            <td className="details">
                <div className="title">{title}</div>
                <div className="subtitle">{subtitle}</div>
                <div className="date">{created_at}</div>
            </td>
            <td className="category">{categories.map(cat => cat.name).join(', ')}</td>
            <td className="status">
                <div className={`status-container ${is_public?'published':'draft'}`}>
                    {is_public?'published':'draft'}
                </div>
            </td>
            <td className="data">
                <div className="container">
                    <div className="flex-container">
                        <div className="icon">
                            <CgEye/>
                        </div>
                        <span>{reads}</span>
                    </div>
                    <div className="flex-container">
                        <div className="icon">
                            <AiOutlineComment/>
                        </div>
                        <span>{comments}</span>
                    </div>
                    <div className="flex-container">
                        <div className="icon">
                            <MdOutlineFavoriteBorder/>
                        </div>
                        <span>{favorites}</span>
                    </div>
                </div>
            </td>
            <td className="buttons">
                <button className="edite">
                    <BiSolidEdit/>
                </button>
                <button className="delete" onClick={handleArticleDeleting}>
                    <MdDeleteForever/>
                </button>
                <button className="link">
                    <BiLinkExternal/>
                </button>
            </td>
        </tr>
    )
}