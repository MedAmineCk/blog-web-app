import {GrNotification} from "react-icons/gr";

export const NotificationsIcon = ({onClick}) => {
    return (
        <div className="notifications-icon" onClick={onClick}>
            <GrNotification/>
        </div>
    )
}