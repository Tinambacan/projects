import React, { useEffect, useState } from "react";

interface VideoProps {
  video: string;
}

const Video: React.FC<VideoProps> = ({ video }) => {
  const [loading, setLoading] = useState(true);
  const isYouTube = video.includes("youtube.com") || video.includes("youtu.be");
  const isLocalPath = video.startsWith("/");

  const getYouTubeEmbedURL = (url: string) => {
    const videoIdMatch = url.match(
      /(?:youtube\.com.*(?:\?|&)v=|youtu\.be\/)([^&?\/]+)/
    );
    const videoId = videoIdMatch ? videoIdMatch[1] : null;
    return videoId ? `https://www.youtube.com/embed/${videoId}` : null;
  };

  const handleLoad = () => {
    setLoading(false);
  };

  useEffect(() => {
    setLoading(true);
  }, [video]);

  return (
    <div className="relative h-72">
      {loading && (
        <div className="flex items-center justify-center z-10 h-72">
          <div className="border-gray-300 h-14 w-14 animate-spin rounded-full border-8 border-t-blue-600" />
        </div>
      )}
      {isYouTube ? (
        <iframe
          src={getYouTubeEmbedURL(video) || ""}
          className={`absolute inset-0 w-full h-full object-cover ${
            loading ? "hidden" : ""
          }`}
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowFullScreen
          onLoad={handleLoad}
        />
      ) : isLocalPath ? (
        <video
          src={video}
          controls
          className={`absolute inset-0 w-full h-full object-cover ${
            loading ? "hidden" : ""
          }`}
          onCanPlayThrough={handleLoad}
        >
          Your browser does not support the video tag.
        </video>
      ) : (
        <div className="flex items-center justify-center h-full">
          <p>Invalid video source</p>
        </div>
      )}
    </div>
  );
};

export default Video;
