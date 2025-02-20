import Button from "../components/Button";
import TitlePage from "../components/TitlePage";
import FontSizeDisplay from "../components/FontText";
import { AnimatePresence, motion } from "framer-motion";
import Background from "../components/Background";

function LandingPage() {
  return (
    <>
      <TitlePage title="Noctogram" />

      <div className="min-h-screen">
        <Background background="/images/bg.png">
          <AnimatePresence>
            <motion.div
              initial={{ opacity: 0, x: -50 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: 50 }}
              transition={{ duration: 0.5 }}
              className=" top-0 z-20 flex flex-col ml-7 mb-12 mt-20 mx-5 md:my-0 gap-5 md:items-start md:justify-start items-center justify-center text-justify md:text-left"
            >
              <FontSizeDisplay sizeVariant="xlbold">
                Project : <span className="text-red-600">Noctogram</span>
              </FontSizeDisplay>
              <motion.div
                initial={{ opacity: 0, x: -50 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: 50 }}
                transition={{ duration: 0.5 }}
                className="flex flex-col gap-2 w-full md:w-1/2 space-y-2"
              >
                <FontSizeDisplay sizeVariant="large">
                  Welcome to Noctogram, your beacon in the digital darkness. In
                  an era where technology connects us all, the shadows of
                  cybercrime lurk just beyond the glow of our screens. Noctogram
                  is your guide to understanding and confronting these digital
                  threats head-on.
                </FontSizeDisplay>
                <FontSizeDisplay sizeVariant="large">
                  Explore our curated content covering a wide range of topics,
                  from cyberbully and malware attacks to hacking and identity
                  theft. Whether you're a seasoned cybersecurity professional or
                  a digital novice, Noctogram offers valuable insights and
                  practical advice to help you navigate the ever-evolving
                  landscape of online security.
                </FontSizeDisplay>
                <div className=" font-semibold">
                  <FontSizeDisplay sizeVariant="large">
                    Join us in our fight against cybercrime. Together, we can
                    shine a light on the darkest corners of the digital world
                    and build a safer, more secure future for all.
                  </FontSizeDisplay>
                </div>
              </motion.div>
              <Button color="red" to="/home">
                Get Started
              </Button>
            </motion.div>
          </AnimatePresence>
        </Background>
      </div>
    </>
  );
}

export default LandingPage;
