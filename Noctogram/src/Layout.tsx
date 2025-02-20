import { ReactNode } from "react";
import Navbar from "./components/Navbar";
import Footer from "./components/Footer";
import { useLocation } from "react-router-dom";

interface Props {
  children: ReactNode;
}

const Layout = ({ children }: Props) => {
  const location = useLocation();

  return (
    <>
      <div className="flex flex-col bg-zinc-950 min-h-screen ">
        <div className="fixed  w-full z-50">
          <Navbar />
        </div>
        <div className="flex flex-1 text-white ">
          <main className="w-full">{children}</main>
        </div>
        <div
          className={`w-full z-40  ${
            location.pathname === "/" || location.pathname === "/contact"
              ? "bottom-0  md:fixed"
              : ""
          }`}
        >
          <Footer />
        </div>
      </div>
    </>
  );
};

export default Layout;
