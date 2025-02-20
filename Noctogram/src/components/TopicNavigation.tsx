import React, { useEffect, useState } from "react";
import { SubTopicsProps } from "./SubTopics";
import { SubtopicSection } from "../components/TopicList";
import FontSizeDisplay from "./FontText";

const TopicNavigation: React.FC<SubTopicsProps> = ({ subtopics }) => {
  const [activeSection, setActiveSection] = useState<string | null>(null);

  useEffect(() => {
    const handleScroll = () => {
      let closestSectionId = null;
      let closestSectionDistance = Infinity;

      subtopics.forEach((subtopicSection: SubtopicSection) => {
        const sectionId = subtopicSection.mainTitle.replace(/\s+/g, "-");
        const element = document.getElementById(sectionId);

        if (element) {
          const rect = element.getBoundingClientRect();
          const distanceFromTop = Math.abs(rect.top);

          if (distanceFromTop < closestSectionDistance) {
            closestSectionDistance = distanceFromTop;
            closestSectionId = sectionId;
          }
        }
      });

      if (closestSectionId !== null) {
        setActiveSection(closestSectionId);
      }
    };

    window.addEventListener("scroll", handleScroll);
    return () => {
      window.removeEventListener("scroll", handleScroll);
    };
  }, [subtopics]);

  const handleScrollToSection = (id: string) => {
    const element = document.getElementById(id);
    if (element) {
      const offset = 100;
      const elementPosition = element.offsetTop - offset;
      window.scrollTo({
        top: elementPosition,
        behavior: "smooth",
      });
      setActiveSection(id);
    }
  };

  return (
    <div>
      <div className="text-center p-2 bg-zinc-800 rounded-t-lg">
        <FontSizeDisplay sizeVariant="medium">Navigation</FontSizeDisplay>
      </div>
      <div className="bg-zinc-900 rounded-b-lg text-center w-56 shadow-inner shadow-zinc-800">
        {subtopics.map(
          (subtopicSection: SubtopicSection, sectionIndex: number) => {
            const sectionId = subtopicSection.mainTitle.replace(/\s+/g, "-");
            const isActive = activeSection === sectionId;
            return (
              <div
                key={sectionIndex}
                className={`p-2 hover:text-gray-400 capitalize w-full ${
                  isActive ? "text-blue-500" : ""
                }`}
                onClick={() => handleScrollToSection(sectionId)}
              >
                <div className="cursor-pointer">
                  <FontSizeDisplay sizeVariant="medium">
                    {subtopicSection.mainTitle}
                  </FontSizeDisplay>
                </div>
              </div>
            );
          }
        )}
      </div>
    </div>
  );
};

export default TopicNavigation;
