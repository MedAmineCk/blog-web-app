import {CgEye} from "react-icons/cg";
import {AiOutlineComment} from "react-icons/ai";
import {MdDeleteForever, MdOutlineFavoriteBorder} from "react-icons/md";
import {BiLinkExternal, BiSolidEdit} from "react-icons/bi";
import {useEffect, useState} from "react";


export const ArticleItem = ({article, data, totalChecked}) => {
    const {thumbnail, title, subtitle, date, category, type} = article;
    const {reads, comments, favorites} = data;

    const [isArticleChecked, setIsArticleChecked] = useState(false);
    const handleArticleChange = (event) => {
        setIsArticleChecked(event.target.checked)
    }
    useEffect(() => {
        setIsArticleChecked(totalChecked)
    }, [totalChecked]);

    return (
        <tr className="article">
            <td className="id">
                <input type="checkbox" checked={isArticleChecked} onChange={handleArticleChange}/>
            </td>
            <td className="thumbnail">
                <div className="thumbnail-container">
                    <img src={thumbnail} alt="article thumbnail"/>
                </div>
            </td>
            <td className="details">
                <div className="title">{title}</div>
                <div className="subtitle">{subtitle}</div>
                <div className="date">{date}</div>
            </td>
            <td className="category">{category}</td>
            <td className="type">{type}</td>
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
                <button className="delete">
                    <MdDeleteForever/>
                </button>
                <button className="link">
                    <BiLinkExternal/>
                </button>
            </td>
        </tr>
    )
}