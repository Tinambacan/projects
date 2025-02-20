import React from "react";
import { useNavigate } from "react-router-dom";
import { motion } from "framer-motion";

interface Props {
  children: React.ReactNode;
  color: keyof typeof colorVariants;
  onClick?: () => void;
  onSubmit?: () => void;
  to?: string;
}

const colorVariants = {
  blue: "bg-blue-700 p-3 w-32 rounded-lg hover:bg-blue-500 text-white",
  red: "bg-red-800 p-3 rounded-lg hover:bg-red-700 text-white font-semibold text-base",
};

function Button({ children, color, onClick, onSubmit, to }: Props) {
  const navigate = useNavigate();
  const handleClick = () => {
    if (onClick) {
      onClick();
    }
    if (onSubmit) {
      onSubmit();
    }
    if (to) {
      navigate(to);
    }
  };

  const colorClass = colorVariants[color];
  return (
    <motion.button
      whileHover={{ scale: 1.2 }}
      onHoverStart={() => {}}
      onHoverEnd={() => {}}
      type={onSubmit ? "submit" : "button"}
      className={`${colorClass}`}
      onClick={handleClick}
    >
      {children}
    </motion.button>
  );
}

export default Button;
