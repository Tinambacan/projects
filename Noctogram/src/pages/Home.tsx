import { useState, useEffect } from "react";
import FontSizeDisplay from "../components/FontText";
import Button from "../components/Button";
import TitlePage from "../components/TitlePage";
import TopicList from "../components/TopicList";
import PreviewVideo from "/images/preview.mp4";
import Modal from "../components/Modal";
import topicsData from "../topics.json";
// import cyberCrime from "/images/Cybercrime.mp4";

interface TopicItem {
  id: number;
  title: string;
  video: string;
  description: string;
}
const Home = () => {
  const [openModalItem, setOpenModalItem] = useState<TopicItem | null>(null);

  const handleBodyOverflow = (isOpen?: boolean) => {
    if (typeof window !== "undefined" && window.document) {
      document.body.style.overflow = isOpen ? "hidden" : "auto";
    }
  };

  const handleOpenModal = (item: TopicItem) => {
    setOpenModalItem(item);
  };

  const handleCloseModal = () => {
    setOpenModalItem(null);
  };

  useEffect(() => {
    handleBodyOverflow(openModalItem !== null);
    return () => handleBodyOverflow(false);
  }, [openModalItem]);

  const itemWithIdSeven = topicsData.find((item) => item.id === 7);

  return (
    <>
      <TitlePage title="Noctogram | Home" />

      <div className="min-h-screen ">
        <div>
          <div className="absolute inset-0 bg-gradient-to-r from-black to-transparent z-20 md:h-[70vh] h-[60vh]" />
          <video
            src={PreviewVideo}
            loop
            autoPlay
            className="inset-0 w-screen  object-cover z-10 md:h-[70vh] h-[60vh]"
          ></video>
          <div className="absolute top-0 flex items-center justify-start md:h-[70vh] h-[60vh]">
            <div className="z-20 pt-14 pl-7 flex flex-col gap-8 items-start justify-start">
              <div>
                <FontSizeDisplay sizeVariant="xxlbold">
                  Cyber Chronicles
                </FontSizeDisplay>
                <FontSizeDisplay sizeVariant="mediumbolditalic">
                  Protect your space, Outrun the Cyberchase
                </FontSizeDisplay>
              </div>
              <div className=" w-1/3 hidden md:block">
                <FontSizeDisplay sizeVariant="small">
                  Cyber Chronicles: Journey through the Virtual Underworld is a
                  compelling documentary that raises awareness about the
                  pervasive threat of cybercrime by featuring poignant scenes of
                  real-life victims and exploring the devastating impact of
                  identity theft, online fraud, and data breaches. This
                  comprehensive film sheds light on evolving cybercriminal
                  tactics and emphasizes crucial steps for safeguarding digital
                  identities and assets, empowering viewers with knowledge to
                  navigate the digital landscape securely and responsibly.
                </FontSizeDisplay>
              </div>
              <div>
                {itemWithIdSeven && (
                  <Button
                    color="red"
                    onClick={() => handleOpenModal(itemWithIdSeven)}
                  >
                    Play
                  </Button>
                )}
              </div>
            </div>
            {openModalItem && (
              <Modal
                title={openModalItem.title}
                video={openModalItem.video}
                onClose={handleCloseModal}
              >
                <FontSizeDisplay sizeVariant="small">
                  {openModalItem.description}
                </FontSizeDisplay>
                <div className="flex items-end justify-end py-2"></div>
              </Modal>
            )}
          </div>
        </div>

        <div className="mb-10 mx-7 relative flex flex-col">
          <div className="font-semibold mb-5 shadow-xl p-3 shadow-slate-800 rounded-b-lg z-10">
            <FontSizeDisplay sizeVariant="large">Topics</FontSizeDisplay>
          </div>
          <div>
            <TopicList />
          </div>
        </div>
      </div>
    </>
  );
};

export default Home;
