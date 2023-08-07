import {BrowserRouter as Router, Routes, Route } from "react-router-dom";
import {Home} from "./pages/Home.jsx";
import {DashboardLayout} from "./DashboardLayout";
import {Articles} from "./pages/Articles";
import {Reviews} from "./pages/Reviews";
import {Ads} from "./pages/Ads";
import {Settings} from "./pages/Settings";

function App() {

  return (
      <Router>
        <Routes>
          <Route path="/dashboard" element={<DashboardLayout/>}>
              <Route index element={<Home/>}/>
              <Route path="articles" element={<Articles/>}/>
              <Route path="reviews" element={<Reviews/>}/>
              <Route path="ads" element={<Ads/>}/>
              <Route path="settings" element={<Settings/>}/>
          </Route>
        </Routes>
      </Router>
  )
}

export default App
