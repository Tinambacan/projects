import { useEffect, useState } from "react";
import Modal from "./Modal";
import FontSizeDisplay from "./FontText";
import { CardBody, CardContainer, CardItem } from "./ui/Card";
import Carousel from "./Carousel";
import Button from "./Button";
import topicsData from "../topics.json";

export interface SubtopicItem {
  title: string;
  description: string | string[];
  itemImage: string;
}

export interface SubtopicSection {
  mainTitle: string;
  mainImage: string;
  mainDescription: string;
  items: SubtopicItem[];
  references?: string;
}

export interface TopicItem {
  id: number;
  title: string;
  image: string;
  description: string;
  video: string;
  subtopics?: SubtopicSection[];
}

export const topics: TopicItem[] = topicsData as TopicItem[];

function TopicList() {
  const handleBodyOverflow = (isOpen?: boolean) => {
    if (typeof window !== "undefined" && window.document) {
      document.body.style.overflow = isOpen ? "hidden" : "auto";
    }
  };

  const [openModalItem, setOpenModalItem] = useState<TopicItem | null>(null);

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

  const filteredTopics = topics.filter((item) => item.id !== 7);

  return (
    <>
      <div className="md:block hidden">
        <div className="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {filteredTopics.map((item) => (
            <CardContainer key={item.id}>
              <CardBody className="relative group/card dark:hover:shadow-2xl hover:shadow-emerald-500/[0.1] dark:bg-black border-white/[0.2] rounded-xl p-2 border">
                <div
                  className="flex rounded-lg flex-col gap-2 justify-center items-center"
                  onClick={() => handleOpenModal(item)}
                >
                  <CardItem translateZ="100" className="w-full mt-4">
                    <img
                      className="shadow-2xl cursor-pointer rounded-lg h-96 w-screen object-cover"
                      src={item.image}
                      alt={item.title}
                    />
                  </CardItem>
                  <CardItem>
                    <FontSizeDisplay sizeVariant="smallbold">
                      {item.title}
                    </FontSizeDisplay>
                  </CardItem>
                </div>
              </CardBody>
            </CardContainer>
          ))}
        </div>
      </div>
      <div className="md:hidden block">
        <Carousel data={filteredTopics} handleOpenModal={handleOpenModal} />
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
          <div className="flex items-end justify-end py-2">
            <Button color="red" to={`/information/${openModalItem.id}`}>
              See Additional Information
            </Button>
          </div>
        </Modal>
      )}
    </>
  );
}

export default TopicList;

