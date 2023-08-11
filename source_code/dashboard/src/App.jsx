import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import { DashboardLayout } from "./DashboardLayout";
import { Home } from "./pages/Home";
import { Articles } from "./pages/Articles";
import { Reviews } from "./pages/Reviews";
import { Ads } from "./pages/Ads";
import { Settings } from "./pages/Settings";
import PrivateRoute from "./auth/PrivateRoute.jsx";
import {Login} from "./auth/Login";
import {AuthRoute} from "./auth/AuthRoute";

function App() {
    return (
        <Router>
            <Routes>
                {/* Public route for login */}
                <Route element={<AuthRoute />}>
                    <Route path="/auth" element={<Login />} />
                </Route>
                {/* Private route for dashboard */}
                <Route element={<PrivateRoute/>}>
                    <Route path="/dashboard" element={<DashboardLayout />}>
                        <Route index element={<Home />} />
                        <Route path="articles" element={<Articles />} />
                        <Route path="reviews" element={<Reviews />} />
                        <Route path="ads" element={<Ads />} />
                        <Route path="settings" element={<Settings />} />
                    </Route>
                </Route>
            </Routes>
        </Router>
    );
}

export default App;
