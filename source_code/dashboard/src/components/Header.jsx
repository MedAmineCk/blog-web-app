import {AiOutlineLogout} from "react-icons/ai";
import {FiEdit2} from "react-icons/fi";
import {DropdownPopup} from "./Shared/DropdownPopup";
import {NotificationsIcon} from "./ui/NotificationsIcon";
import {useEffect, useRef, useState} from "react";
import {CommentsIcon} from "./ui/CommentsIcon.jsx";
import {NotificationItem} from "./ui/NotificationItem";
import notificationsData from "../data/notifications.js";
import commentsData from "../data/comments.js";
import authService from "../auth/AuthService.js";
import {Link, useNavigate} from "react-router-dom";

export const Header = () => {
    const [isNotificationsOpen, setIsNotificationsOpen] = useState(false);
    const [isCommentsOpen, setIsCommentsOpen] = useState(false);
    const notificationsWrapperRef = useRef(null);
    const commentsWrapperRef = useRef(null);

    const handleNotificationsClick = () => {
        setIsNotificationsOpen((prevState) => !prevState);
    };

    const handleCommentsClick = () => {
        setIsCommentsOpen((prevState) => !prevState);
    };

    const handleClickOutside = (event) => {
        if (notificationsWrapperRef.current && !notificationsWrapperRef.current.contains(event.target)) {
            setIsNotificationsOpen(false);
        }
        if (commentsWrapperRef.current && !commentsWrapperRef.current.contains(event.target)) {
            setIsCommentsOpen(false);
        }
    };

    useEffect(() => {
        // Attach the event listener when the component mounts
        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            // Clean up the event listener when the component unmounts
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);


    const [notifications, setNotifications] = useState(notificationsData);
    const handleDeleteNotification = (id) => {
        let newNotifications = notifications.filter((notifications) => notifications.id !== id);
        setNotifications(newNotifications)
    }
    const handleNotificationsErease = () => {
        setNotifications([])
    }

    const [comments, setComments] = useState(commentsData);
    const handleDeleteComment = (id) => {
        let newComments = comments.filter((comment) => comment.id !== id);
        setComments(newComments)
    }
    const handleCommentsErease = () => {
        setComments([])
    }

    const navigate = useNavigate();
    const handleLogout = async () => {
        try {
            await authService.logout();
            // Redirect to the login page after successful logout
            navigate('/auth')
        } catch (error) {
            console.error('Logout error:', error);
        }
    };

    return (
        <header className="flex-container">
            <div className="flex-container">
                <div className="icon-container flex-center notification" ref={notificationsWrapperRef}>
                    <NotificationsIcon onClick={handleNotificationsClick}/>
                    <div className="log flex-center">{notifications.length}</div>
                    <DropdownPopup isOpen={isNotificationsOpen}>
                        {
                            notifications.length !== 0 ?
                                notifications.slice(0, 3).map((notificationObj, index) =>
                                    <NotificationItem
                                        notificationObj={notificationObj}
                                        key={index}
                                        onDelete={handleDeleteNotification}
                                    />) :
                                <p className='empty'>nothing!</p>
                        }
                        <button className="clear-all" onClick={handleNotificationsErease}>clear all</button>
                    </DropdownPopup>
                </div>
                <div className="icon-container flex-center comments" ref={commentsWrapperRef}>
                    <CommentsIcon onClick={handleCommentsClick}/>
                    <div className="log flex-center">{comments.length}</div>
                    <DropdownPopup isOpen={isCommentsOpen}>
                        {
                            comments.length !== 0 ?
                                comments.slice(0, 3).map((notificationObj, index) =>
                                    <NotificationItem
                                        notificationObj={notificationObj}
                                        key={index}
                                        onDelete={handleDeleteComment}
                                    />) :
                                <p className='empty'>nothing!</p>
                        }
                        <button className="clear-all" onClick={handleCommentsErease}>clear all</button>
                    </DropdownPopup>
                </div>
            </div>
            <div className="flex-container">
                <Link to="/dashboard/create-article" className="write flex-container">
                    <span>Write</span>
                    <span className="icon-container">
                        <FiEdit2/>
                    </span>
                </Link>
                <div className="icon-container log-out flex-center" onClick={handleLogout}>
                    <AiOutlineLogout/>
                </div>
            </div>
        </header>
    )
}