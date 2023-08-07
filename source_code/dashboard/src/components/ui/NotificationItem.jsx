import {MdDeleteForever} from "react-icons/md";

export const NotificationItem = ({notificationObj, onDelete}) => {
    const {id, title, date} = notificationObj;
    return (
        <div className="log-item flex-between">
            <div className="container">
                <div className="title">{title}</div>
                <div className="date">{date}</div>
            </div>
            <div className="delete-icon flex-center" onClick={() => onDelete(id)}>
                <MdDeleteForever/>
            </div>
        </div>
    )
}