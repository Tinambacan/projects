interface Props {
  children: React.ReactNode;
  background?: string;
}

function Background({ children, background }: Props) {
  return (
    <div
      className="bg-cover bg-center w-full z-10 min-h-screen items-center justify-center flex"
      style={{ backgroundImage: `url(${background})` }}
    >
      {children}
    </div>
  );
}

export default Background;
