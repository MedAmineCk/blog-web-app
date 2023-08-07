import {Outlet} from "react-router-dom";
import {Header} from "./components/Header";
import {Aside} from "./components/Aside";

export const DashboardLayout = () => {
    return (
        <div className="grid-container">
            <Header/>
            <Aside/>
            <main>
                <Outlet/>
            </main>
        </div>
    )
}