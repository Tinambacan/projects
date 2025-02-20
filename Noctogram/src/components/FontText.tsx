import React from "react";
import { motion } from "framer-motion";

interface FontSizeDisplayProps {
  children: React.ReactNode;
  sizeVariant:
    | "small"
    | "smallbold"
    | "medium"
    | "large"
    | "xl"
    | "xlbold"
    | "xxlbold"
    | "largebold"
    | "mediumbold"
    | "mediumbolditalic";
  addedClass?: string;
}

const sizeVariants = {
  small: "text-sm md:text-base",
  smallbold: "text-sm md:text-base font-bold my-3",
  medium: "text-base md:text-lg",
  large: "text-xl lg:text-2xl",
  xl: "text-3xl lg:text-4xl",
  largebold: "text-xl lg:text-2xl font-bold",
  mediumbold: "text-base md:text-lg font-bold",
  mediumbolditalic: "text-base md:text-md font-bold italic",
  xlbold: "text-3xl lg:text-4xl font-semibold",
  xxlbold: "text-5xl font-semibold",
};

const FontSizeDisplay: React.FC<FontSizeDisplayProps> = ({
  children,
  sizeVariant,
  addedClass,
}) => {
  const handleAddClass = () => {
    return addedClass ? addedClass : "";
  };

  return (
    <motion.div
      initial={{ opacity: 0, x: -50 }}
      animate={{ opacity: 1, x: 0 }}
      exit={{ opacity: 0, x: 50 }}
      transition={{ duration: 1 }}
      className={`${sizeVariants[sizeVariant]} ${handleAddClass()}`}
    >
      <p>{children}</p>
    </motion.div>
  );
};

export default FontSizeDisplay;
