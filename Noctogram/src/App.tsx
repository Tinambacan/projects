import "./App.css";
import { Routes, Route } from "react-router-dom";
import LandingPage from "./pages/LandingPage";
import Home from "./pages/Home";
import Policy from "./pages/Policy";
import Contact from "./pages/Contact";
import Layout from "./Layout";
import Information from "./pages/Information";
import About from "./pages/About";

function App() {
  return (
    <Layout>
      <Routes>
        <Route index element={<LandingPage />} />
        <Route path="/" element={<LandingPage />} />
        <Route path="home" element={<Home />} />
        <Route path="about" element={<About />} />
        <Route path="contact" element={<Contact />} />
        <Route path="policy" element={<Policy />} />
        <Route path="information/:id" element={<Information />} />
      </Routes>
    </Layout>
  );
}

export default App;
