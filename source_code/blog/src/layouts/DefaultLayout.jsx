import {Outlet} from "react-router-dom";
import {Header} from "../components/Header.jsx";
import Footer from "../components/Footer.jsx";
import {Aside} from "../components/Aside";

export const DefaultLayout = () => {
    return (
        <div className="grid-container">
            <Header/>
            <main>
                <Outlet/>
            </main>
            <Aside/>
            <Footer/>
        </div>
    )
}