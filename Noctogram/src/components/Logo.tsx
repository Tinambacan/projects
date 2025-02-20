interface Props {
  src: string;
}

function Logo({ src }: Props) {
  return (
    <>
      <img
        src={src}
        alt=""
        className="rounded-full flex-nowrap flex object-cover h-16"
      />
    </>
  );
}

export default Logo;
