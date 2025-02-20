import { NavLink, useLocation } from "react-router-dom";
import { motion } from "framer-motion";
import { useEffect, useState } from "react";
import { BurgerSwipe } from "react-icons-animated";
import Logo from "./Logo";

const navigation = [
  { name: "Home", href: "home" },
  { name: "About", href: "about" },
  { name: "Contact", href: "contact" },
  { name: "Policy", href: "policy" },
];

const Navbar = () => {
  const handleBodyOverflow = (isOpen?: boolean) => {
    if (typeof window !== "undefined" && window.document) {
      document.body.style.overflow = isOpen ? "hidden" : "auto";
    }
  };
  const [isClosed, setIsClosed] = useState(false);
  const [isOpen, setIsOpen] = useState(false);
  const [isHidden, setIsHidden] = useState(true);

  useEffect(() => {
    handleBodyOverflow(isOpen);
    return () => handleBodyOverflow(false);
  }, [isOpen]);

  const getNavLinkClassName = ({ isActive }: { isActive?: boolean } = {}) => {
    const baseClass =
      "p-2 inline-flex items-center text-xl focus:outline-none transition duration-500 ease-in-out";

    const activeClass = isActive
      ? "text-red-600 border-indigo-400 hover:border-indigo-700 focus:border-indigo-700 font-bold"
      : "border-transparent text-white hover:text-gray-400 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300";

    const responsiveClass = "md:text-lg lg:text-xl xl:text-2xl";

    return `${baseClass} ${activeClass} ${responsiveClass}`;
  };

  const isHiddenStyle =
    "absolute space-y-10 backdrop-filter backdrop-blur-sm bg-opacity-20 w-full p-6 min-h-screen rounded-lg";
  const isVisibleStyle =
    "absolute space-y-10 bg-gray-950 bg-opacity-90 w-full p-6 left-0 right-0 top-0 min-h-screen ";

  const itemVariants = {
    open: {
      opacity: 1,
      y: 0,
      transition: { type: "spring", stiffness: 500, damping: 24 },
    },
    closed: { opacity: 0, y: -120, transition: { duration: 0.2 } },
  };

  const location = useLocation();

  const shouldDisplayHomeLink = location.pathname !== "/";
  return (
    <>
      <div className="bg-clip-padding backdrop-filter backdrop-blur-md bg-opacity-20 p-2 flex items-center px-5 justify-between h-16 ">
        <NavLink to="home" className="flex items-center">
          <Logo src="../images/N.png" />
        </NavLink>

        <div className="block md:hidden">
          <div className="w-full flex justify-between items-center font-poppins md:hidden">
            <motion.div
              initial={false}
              animate={isOpen ? "open" : "closed"}
              className="flex flex-col w-full z-[100]"
            >
              <motion.button
                initial={{ opacity: 0.6 }}
                whileInView={{ opacity: 1 }}
                whileHover={{ scale: 1.2, transition: { duration: 0.5 } }}
                onClick={() => {
                  setIsClosed(!isClosed);
                  setIsOpen(!isOpen);
                  setIsHidden(!isHidden);
                }}
                style={{
                  display: "grid",
                  placeItems: "center",
                }}
                className={
                  isHidden
                    ? "z-50 absolute top-2 right-5 grid place-items-center bg-zinc-700 rounded-full h-[50px] w-[50px]"
                    : "z-50 absolute top-2 right-5 grid w-[50px] h-[50px] place-items-center bg-red-500 rounded-full"
                }
              >
                <BurgerSwipe isClosed={isClosed} />
              </motion.button>

              <motion.ul
                variants={{
                  open: {
                    clipPath: "inset(0% 0% 0% 0% round 0px)",
                    transition: {
                      type: "spring",
                      bounce: 0,
                      duration: 1,
                      delayChildren: 0.3,
                      staggerChildren: 0.05,
                    },
                  },
                  closed: {
                    clipPath: "inset(10% 50% 90% 50% round 0px)",
                    transition: {
                      type: "spring",
                      bounce: 0,
                      duration: 0.3,
                    },
                  },
                }}
                layout
                className={isHidden ? isHiddenStyle : isVisibleStyle}
              >
                <div className="flex flex-col gap-5 font-bold text-3xl lg:text-5xl bg-zinc-800 justify-start z-50 rounded-lg p-2">
                  {navigation.map((item) =>
                    item.name === "Home" && !shouldDisplayHomeLink ? null : (
                      <motion.div key={item.name} variants={itemVariants}>
                        <NavLink
                          to={item.href}
                          className={getNavLinkClassName}
                          onClick={() => {
                            setIsClosed(!isClosed);
                            setIsOpen(!isOpen);
                            setIsHidden(!isHidden);
                          }}
                        >
                          {item.name}
                        </NavLink>
                      </motion.div>
                    )
                  )}
                </div>
              </motion.ul>
            </motion.div>
          </div>
        </div>

        <div className="hidden md:block">
          <motion.nav
            className="gap-10 rounded-xl flex items-center justify-center"
            initial={{ opacity: 0, x: -20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.5 }}
          >
            {navigation.map((item) =>
              item.name === "Home" && !shouldDisplayHomeLink ? null : (
                <motion.div
                  key={item.name}
                  initial={{ opacity: 0, x: -20 }}
                  animate={{ opacity: 1, x: 0 }}
                  transition={{ duration: 0.5, delay: 0.1 }}
                >
                  <NavLink to={item.href} className={getNavLinkClassName}>
                    {item.name}
                  </NavLink>
                </motion.div>
              )
            )}
          </motion.nav>
        </div>
      </div>
    </>
  );
};

export default Navbar;
