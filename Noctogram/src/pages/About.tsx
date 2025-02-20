import FontSizeDisplay from "../components/FontText";
import TitlePage from "../components/TitlePage";
import Background from "../components/Background";
import { AnimatePresence, motion } from "framer-motion";

function About() {
  return (
    <>
      <TitlePage title="About" />

      <div className="relative min-h-screen">
        <div className="absolute inset-0  bg-gradient-to-l from-black to-transparent z-20" />
        <Background background="/images/bgtwo.jfif">
          <AnimatePresence>
            <div className="z-20 pt-14 px-10 pb-5">
              <motion.div
                initial={{ opacity: 0, x: -50 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: 50 }}
                transition={{ duration: 0.5 }}
                className=" flex flex-col md:my-0 gap-5 md:items-end md:justify-end items-center justify-center text-justify md:text-right"
              >
                <motion.div
                  initial={{ opacity: 0, x: -50 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: 50 }}
                  transition={{ duration: 0.5 }}
                  className="flex flex-col gap-2 w-full md:w-1/2 space-y-2 "
                >
                  <div>
                    <FontSizeDisplay sizeVariant="largebold" addedClass="py-3">
                      About Us
                    </FontSizeDisplay>
                    <div className="text-justify">
                      <FontSizeDisplay sizeVariant="medium">
                        At NOCTOGRAM, we are passionate about harnessing
                        knowledge and awareness to combat the growing threat of
                        cybercrime. Our mission is to empower individuals and
                        organizations with the information and tools they need
                        to protect themselves online.
                      </FontSizeDisplay>
                    </div>
                  </div>

                  <div>
                    <FontSizeDisplay sizeVariant="largebold" addedClass="py-3">
                      Our Mission
                    </FontSizeDisplay>
                    <div className="text-justify">
                      <FontSizeDisplay sizeVariant="medium">
                        NOCTOGRAM is dedicated to: Educating the public about
                        cyber threats and best practices for online safety.
                        Raising Awareness about the impact of cybercrime on
                        individuals, businesses, and society. Empowering
                        individuals with resources and strategies to defend
                        against cyber threats.
                      </FontSizeDisplay>
                    </div>
                  </div>
                  <div>
                    <FontSizeDisplay sizeVariant="largebold" addedClass="py-3">
                      Why NOCTOGRAM?
                    </FontSizeDisplay>
                    <div className="text-justify">
                      <FontSizeDisplay sizeVariant="medium">
                        In today's interconnected world, cybercrime poses a
                        significant risk to individuals and organizations alike.
                        From identity theft to ransomware attacks, the
                        consequences can be devastating. At NOCTOGRAM, we
                        believe that knowledge is power. By understanding the
                        risks and adopting proactive measures, everyone can
                        contribute to a safer digital environment.
                      </FontSizeDisplay>
                    </div>
                  </div>
                  <div>
                    <FontSizeDisplay sizeVariant="largebold" addedClass="py-3">
                      What We Offer
                    </FontSizeDisplay>
                    <div className="text-justify">
                      <FontSizeDisplay sizeVariant="medium">
                        Informative Articles: Stay informed with our in-depth
                        articles on cybercrime trends, prevention tips, and
                        cybersecurity technologies. Resources: Access resources
                        such as guides, toolkits, and checklists to enhance your
                        cybersecurity practices. Community Engagement: Join our
                        community discussions, webinars, and events focused on
                        cybersecurity awareness.
                      </FontSizeDisplay>
                    </div>
                  </div>
                </motion.div>
              </motion.div>
            </div>
          </AnimatePresence>
        </Background>
      </div>
    </>
  );
}

export default About;
