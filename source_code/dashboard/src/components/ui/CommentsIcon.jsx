import {AiOutlineComment} from "react-icons/ai";

export const CommentsIcon = ({onClick}) => {
    return (
        <div className="comments-icon" onClick={onClick}>
            <AiOutlineComment/>
        </div>
    )
}