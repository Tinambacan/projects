import React, { useEffect } from 'react';
import { useParams } from "react-router-dom";
import { topics } from "../components/TopicList";
import FontSizeDisplay from "../components/FontText";
import SubTopics from "../components/SubTopics";
import TopicNavigation from "../components/TopicNavigation";
import TitlePage from "../components/TitlePage";


const Information: React.FC = () => {
  const { id } = useParams<{ id?: string }>();

  const topic = topics.find((item) => item.id.toString() === id);

  useEffect(() => {
    window.scrollTo(0, 0); 
  }, []); 

  if (!topic) {
    return <div>Topic not found</div>;
  }

  return (
    <>
      <TitlePage title={topic.title} />

      <div className="min-h-screen">
        <div className="relative">
          <div className="absolute inset-0 bg-gradient-to-r from-black to-transparent z-20 h-[60vh]" />
          <img
            src={topic.image}
            alt={topic.title}
            className="inset-0 w-screen object-cover z-10 h-[60vh]"
          />

          <div className="absolute top-0 z-20 flex flex-col ml-7 mt-20 gap-8">
            <FontSizeDisplay sizeVariant="xxlbold">
              {topic.title}
            </FontSizeDisplay>
            <div className="md:w-1/3 w-full">
              <FontSizeDisplay sizeVariant="small">
                {topic.description}
              </FontSizeDisplay>
            </div>
          </div>
        </div>
        <div className="items-center justify-center mx-auto max-w-6xl">
          <div className="flex gap-10">
            <div className="sticky top-28 h-56 md:block hidden pt-8">
              {topic.subtopics && (
                <TopicNavigation subtopics={topic.subtopics} />
              )}
            </div>
            <div className="p-5">
              {topic.subtopics && <SubTopics subtopics={topic.subtopics} />}
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default Information;
