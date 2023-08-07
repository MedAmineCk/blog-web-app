import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import {DefaultLayout} from "./layouts/DefaultLayout";
import {Home} from "./pages/Home.jsx";
import {Article} from "./pages/Article";

function App() {

    return (
        <Router>
            <Routes>
                <Route path="/" element={<DefaultLayout />}>
                    <Route index element={<Home />} />
                    <Route path="/article/:id" element={<Article />} />
                </Route>
            </Routes>
        </Router>
    );
}

export default App;
