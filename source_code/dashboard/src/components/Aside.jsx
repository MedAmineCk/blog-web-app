import logo from "../assets/logo.svg"
import navigationsData from "../data/navigations.js";
import {NavItem} from "./ui/NavItem";
import {useEffect, useState} from "react";

export const Aside = () => {
    const [navigations, setNavigations] = useState(navigationsData);
    const handleNavItemClick = (index) => {
        const updatedNavigations = navigations.map((nav, i) => ({
            ...nav,
            isActive: i === index
        }))
        setNavigations(updatedNavigations);
    }

    useEffect(() => {
        // Update the active class based on the current URL
        const currentPath = location.pathname;
        const updatedNavigations = navigations.map((nav) => ({
            ...nav,
            isActive: nav.target === currentPath,
        }));
        setNavigations(updatedNavigations);
    }, []);
    return (
        <aside>
            <div className="logo">
                <img src={logo} alt="logo"/>
            </div>
            <p className="username">@Valuable Books</p>
            <nav>
                {
                    navigations.map(({target, icon, label, isActive}, index) =>
                        <NavItem target={target} icon={icon} label={label} isActive={isActive}
                                 onClick={() => handleNavItemClick(index)} key={index}/>)
                }
            </nav>
        </aside>
    )
}