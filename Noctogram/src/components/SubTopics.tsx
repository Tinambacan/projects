import React from "react";
import { SubtopicSection } from "../components/TopicList";
import FontSizeDisplay from "./FontText";

export interface SubTopicsProps {
  subtopics: SubtopicSection[];
}

const SubTopics: React.FC<SubTopicsProps> = ({ subtopics }) => {
  return (
    <div>
      {subtopics.map((subtopicSection, sectionIndex) => (
        <div
          key={sectionIndex}
          className="mt-4"
          id={subtopicSection.mainTitle.replace(/\s+/g, "-")}
        >
          <div>
            <div className="capitalize">
              <FontSizeDisplay sizeVariant="largebold">
                {subtopicSection.mainTitle}
              </FontSizeDisplay>
            </div>
            <div className="my-3">
              <img
                src={subtopicSection.mainImage}
                className="w-screen object-cover"
                alt=""
              />
            </div>
            {subtopicSection.mainDescription && (
              <FontSizeDisplay sizeVariant="small">
                {subtopicSection.mainDescription}
              </FontSizeDisplay>
            )}
          </div>

          {subtopicSection.items.map(
            (subtopic, itemIndex) =>
              (subtopic.itemImage ||
                subtopic.title ||
                subtopic.description) && (
                <div
                  key={itemIndex}
                  className={`mt-5 flex items-center justify-center ${
                    itemIndex % 2 === 0
                      ? "flex-col md:flex-row"
                      : "flex-col md:flex-row-reverse text-right"
                  }`}
                >
                  {subtopic.itemImage && (
                    <img
                      src={subtopic.itemImage}
                      className="h-48"
                      alt={subtopic.title}
                    />
                  )}
                  <div
                    className={`flex flex-col justify-center ${
                      itemIndex % 2 === 0 ? "md:ml-4" : "md:mr-4"
                    } md:mt-0`}
                  >
                    {subtopic.title && (
                      <FontSizeDisplay sizeVariant="mediumbold">
                        {subtopic.title}
                      </FontSizeDisplay>
                    )}
                    {Array.isArray(subtopic.description) ? (
                      <div>
                        {subtopic.description.map((desc, idx) => (
                          <div key={idx} className="text-white">
                            {desc}
                          </div>
                        ))}
                      </div>
                    ) : subtopic.description ? (
                      <FontSizeDisplay sizeVariant="small">
                        {subtopic.description}
                      </FontSizeDisplay>
                    ) : null}
                  </div>
                </div>
              )
          )}

          {subtopicSection.references && (
            <div className="mt-4 flex flex-col justify-end items-end">
              <FontSizeDisplay sizeVariant="mediumbold">
                Reference
              </FontSizeDisplay>
              <FontSizeDisplay sizeVariant="small">
                {subtopicSection.references}
              </FontSizeDisplay>
            </div>
          )}
        </div>
      ))}
    </div>
  );
};

export default SubTopics;
