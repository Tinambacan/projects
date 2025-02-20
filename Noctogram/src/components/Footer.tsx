import FontSizeDisplay from "./FontText";

function Footer() {
  return (
    <div className="absolute w-full bg-zinc-900 text-white py-2 px-3 flex flex-col md:flex-row justify-between items-center text-center">
      <div className="flex">
        <FontSizeDisplay sizeVariant="medium">
          Copyright © 2024 Noctogram | All rights reserved | This is for
          educational purposes only
        </FontSizeDisplay>
      </div>
      <div className="flex gap-2">
        <img src="../images/tridev.png" className="w-10" />
        <div className="flex items-center">
          <FontSizeDisplay sizeVariant="mediumbold">TRIDEV</FontSizeDisplay>
        </div>
      </div>
    </div>
  );
}

export default Footer;
