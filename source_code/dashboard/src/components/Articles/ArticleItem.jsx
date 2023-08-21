import {CgEye} from "react-icons/cg";
import {AiOutlineComment} from "react-icons/ai";
import {MdDeleteForever, MdOutlineFavoriteBorder} from "react-icons/md";
import {BiLinkExternal, BiSolidEdit} from "react-icons/bi";
import {useEffect, useState} from "react";
import {VscPinned} from "react-icons/vsc";
import {Link} from "react-router-dom";


export const ArticleItem = ({article, totalChecked, onDeleting}) => {
    const {id, thumbnail_url, subtitle,  title, is_public, is_pinned, created_at, tags, categories} = article;
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
                    {is_pinned && (<div className="pinned flex-center"><VscPinned/></div>)}
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
                <Link to={`/dashboard/edit-article/${id}`} className="edite flex-center">
                    <BiSolidEdit/>
                </Link>
                <Link className="delete flex-center" onClick={handleArticleDeleting}>
                    <MdDeleteForever/>
                </Link>
                <Link className="link flex-center" to={`/article/${id}`}>
                    <BiLinkExternal/>
                </Link>
            </td>
        </tr>
    )
}