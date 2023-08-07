import {
    FaChartLine,
    FaExternalLinkAlt,
    FaLongArrowAltRight, FaPlus,
    FaRegBookmark,
    FaShareAlt,
    FaUserGraduate
} from "react-icons/fa";

const containerStyles = {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    minWidth: '35px',
    minHeight: '35px',
};
export const BookmarkIcon = () => {
    return (
        <div className="icon-container" style={containerStyles}>
            <FaRegBookmark/>
        </div>
    )
}

export const LinkIcon = () => {
    return (
        <div className="icon-container" style={containerStyles}>
            <FaLongArrowAltRight/>
        </div>
    )
}

export const ShareIcon = () => {
    return (
        <div className="icon-container" style={containerStyles}>
            <FaShareAlt/>
        </div>
    )
}

export const TrendIcon = () => {
    return (
        <div className="icon-container" style={containerStyles}>
            <FaChartLine/>
        </div>
    )
}

export const UrlIcon = () => {
    return (
        <div className="icon-container" style={containerStyles}>
            <FaExternalLinkAlt/>
        </div>
    )
}

export const AuthorIcon = () => {
    return (
        <div className="icon-container" style={containerStyles}>
            <FaUserGraduate/>
        </div>
    )
}
export const AddIcon = () => {
    return (
        <div className="icon-container" style={containerStyles}>
            <FaPlus/>
        </div>
    )
}