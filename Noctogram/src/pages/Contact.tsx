import TitlePage from "../components/TitlePage";
import { AnimatePresence, motion } from "framer-motion";
import Background from "../components/Background";
import ContactInfo from "../components/ContactInfo";

function Contact() {
  return (
    <>
      <TitlePage title="Contact" />

      <div className="relative min-h-screen">
        <div className="absolute inset-0  bg-gradient-to-r from-black to-transparent z-20" />
        <Background background="/images/bgfour.jpg">
          <AnimatePresence>
            <motion.div
              initial={{ opacity: 0, x: -50 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: 50 }}
              transition={{ duration: 0.5 }}
              className=" flex flex-col  md:flex-row gap-6 md:gap-10  items-center justify-center w-full md:pt-0 md:pb-0 pt-20 pb-5 px-2 md:px-10 z-20"
            >
              <ContactInfo />
            </motion.div>
          </AnimatePresence>
        </Background>
      </div>
    </>
  );
}

export default Contact;
