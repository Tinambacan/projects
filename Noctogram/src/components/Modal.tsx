import React, { ReactNode } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faXmark } from "@fortawesome/free-solid-svg-icons";
import Video from "./Video"; // Make sure to import the Video component

interface Props {
  children: ReactNode;
  onClose: () => void;
  title: string;
  video: string;
}

const Modal: React.FC<Props> = ({ children, onClose, title, video }) => {
  return (
    <>
      <div className="inset-0 fixed z-50">
        <div className="flex items-center justify-center min-h-screen p-1">
          <div className="fixed inset-0 transition-opacity" aria-hidden="true">
            <div className="z-[100] inset-0 bg-black opacity-75 absolute"></div>
          </div>

          <div className="bg-black rounded-lg shadow-xl transform transition-all max-w-screen-md w-full text-stone-300 border border-zinc-500 max-h-screen">
            <div className="flex justify-end items-center p-2 border-b border-transparent relative">
              <FontAwesomeIcon
                className="text-xl hover:bg-zinc-500 cursor-pointer p-2 w-5 h-5 rounded-full bg-zinc-600 z-30"
                icon={faXmark}
                onClick={onClose}
              />
            </div>
            <div className="flex flex-col relative">
              <div className="relative">
                <Video video={video} />
              </div>
              <div className=" w-full text-start py-2 px-4">
                <span className="text-2xl font-bold">{title}</span>
                <div>{children}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default Modal;
