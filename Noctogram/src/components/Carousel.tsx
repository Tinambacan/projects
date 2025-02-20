import React, { useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faChevronLeft,
  faChevronRight,
} from "@fortawesome/free-solid-svg-icons";
import { CardBody, CardContainer, CardItem } from "./ui/Card";
import FontSizeDisplay from "./FontText";
import { TopicItem } from "./TopicList";

interface CarouselProps {
  data: TopicItem[];
  handleOpenModal: (item: TopicItem) => void;
}

const Carousel: React.FC<CarouselProps> = ({ data, handleOpenModal }) => {
  const [slide, setSlide] = useState(0);

  const nextSlide = () => {
    setSlide(slide === data.length - 1 ? 0 : slide + 1);
  };

  const prevSlide = () => {
    setSlide(slide === 0 ? data.length - 1 : slide - 1);
  };

  return (
    <div className="relative w-full overflow-hidden">
      <div className="absolute top-1/2 -left-0">
        <FontAwesomeIcon
          icon={faChevronLeft}
          onClick={prevSlide}
          className="absolute transform -translate-y-1/2 text-3xl text-white cursor-pointer z-10"
        />
      </div>

      <FontAwesomeIcon
        icon={faChevronRight}
        onClick={nextSlide}
        className="absolute -right-0 top-1/2 transform -translate-y-1/2 text-3xl text-white cursor-pointer z-10"
      />

      <div
        className="flex transition-transform duration-700 ease-in-out"
        style={{ transform: `translateX(-${slide * 100}%)` }}
      >
        {data.map((item: TopicItem, idx: number) => (
          <div key={idx} className="w-full flex-shrink-0">
            <div className="p-4">
              <CardContainer>
                <CardBody className="relative group/card dark:hover:shadow-2xl hover:shadow-emerald-500/[0.1] dark:bg-black border-white/[0.2] rounded-xl p-2 border">
                  <div
                    className="flex rounded-lg flex-col gap-2 justify-center items-center"
                    onClick={() => handleOpenModal(item)}
                  >
                    <CardItem translateZ="100" className="w-full mt-4">
                      <img
                        className="shadow-2xl cursor-pointer rounded-lg w-screen object-cover h-96"
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
            </div>
          </div>
        ))}
      </div>
      <div className="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
        {data.map((_: TopicItem, idx: number) => (
          <button
            key={idx}
            className={`w-3 h-3 rounded-full ${
              slide === idx ? "bg-white" : "bg-gray-500"
            }`}
            onClick={() => setSlide(idx)}
          ></button>
        ))}
      </div>
    </div>
  );
};

export default Carousel;
