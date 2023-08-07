import {CgEye} from "react-icons/cg";
import {AiOutlineComment} from "react-icons/ai";
import {MdOutlineFavoriteBorder} from "react-icons/md";


export const ArticleItem = ({index, article, data}) => {
    const {thumbnail, title, subtitle, date} = article;
    const {reads, comments, favorites} = data;

    return (
        <tr className="article">
            <td className="index">{index}</td>
            <td className="thumbnail">
                <div className="thumbnail-container">
                    <img src={thumbnail} alt="article thumbnail"/>
                </div>
            </td>
            <td className="container">
                <div className="title">{title}</div>
                <div className="subtitle">{subtitle}</div>
                <div className="date">{date}</div>
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
        </tr>
    )
}