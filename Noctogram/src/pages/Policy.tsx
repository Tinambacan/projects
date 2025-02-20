import { AnimatePresence, motion } from "framer-motion";
import TitlePage from "../components/TitlePage";
import Background from "../components/Background";
import FontSizeDisplay from "../components/FontText";

function Policy() {
  return (
    <>
      <TitlePage title="Policy" />

      <div className="relative min-h-screen">
        <div className="absolute inset-0  bg-gradient-to-r from-black to-transparent z-20" />
        <Background background="/images/bgthree.jpg">
          <AnimatePresence>
            <div className="z-20 pt-14 px-10 pb-5">
              <motion.div
                initial={{ opacity: 0, x: -50 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: 50 }}
                transition={{ duration: 0.5 }}
                className=" flex flex-col md:my-0 gap-5 md:items-start md:justify-start items-center justify-center text-justify md:text-left"
              >
                <motion.div
                  initial={{ opacity: 0, x: -50 }}
                  animate={{ opacity: 1, x: 0 }}
                  exit={{ opacity: 0, x: 50 }}
                  transition={{ duration: 0.5 }}
                  className="flex flex-col gap-2 w-full md:w-1/2 space-y-2"
                >
                  <div>
                    <FontSizeDisplay sizeVariant="largebold" addedClass="py-3">
                      Policy
                    </FontSizeDisplay>
                    <div className="text-justify">
                      <FontSizeDisplay sizeVariant="medium">
                        NOCTOGRAM reserves the right to modify or update this
                        policy at any time. Changes will be effective
                        immediately upon posting on the website. Users are
                        encouraged to review this policy periodically for
                        updates.
                      </FontSizeDisplay>
                    </div>
                  </div>

                  <div>
                    <FontSizeDisplay sizeVariant="largebold" addedClass="py-3">
                      Purpose
                    </FontSizeDisplay>
                    <div className="text-justify">
                      <FontSizeDisplay sizeVariant="medium">
                        NOCTOGRAM aims to serve as a comprehensive resource for:
                        <br />
                        Educating individuals about the risks and implications
                        of cybercrime. Providing practical guidance on how to
                        protect personal and sensitive information online.
                        Empowering illiterate individuals to navigate and
                        utilize technology safely.
                      </FontSizeDisplay>
                    </div>
                  </div>
                  <div>
                    <FontSizeDisplay sizeVariant="largebold" addedClass="py-3">
                      Information Accuracy
                    </FontSizeDisplay>
                    <div className="text-justify">
                      <FontSizeDisplay sizeVariant="medium">
                        We strive to provide accurate and up-to-date information
                        on cybercrime prevention and related topics. However,
                        NOCTOGRAM does not guarantee the accuracy, completeness,
                        or reliability of any information presented on the
                        website. Users are encouraged to verify information from
                        multiple sources before making decisions or taking
                        actions based on our content.
                      </FontSizeDisplay>
                    </div>
                  </div>
                  <div>
                    <FontSizeDisplay sizeVariant="largebold" addedClass="py-3">
                      Disclaimer
                    </FontSizeDisplay>
                    <div className="text-justify">
                      <FontSizeDisplay sizeVariant="medium">
                        NOCTOGRAM is not liable for any damages or losses
                        incurred as a result of using or relying on information
                        provided on the website. Users are responsible for their
                        own actions and decisions based on the content
                        presented.
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

export default Policy;
