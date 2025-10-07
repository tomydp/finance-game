
const getBorderColor = (level: number) => {
  if (level >= 10) return "border-yellow-400";
  if (level >= 7) return "border-purple-500";
  if (level >= 4) return "border-green-500";
  return "border-blue-500";
};

const Avatar = ({ src, level }: { src: string; level: number }) => (
  <div
    className={`w-24 h-24 rounded-full border-4 ${getBorderColor(
      level
    )} flex items-center justify-center overflow-hidden`}
  >
    <img src={src} alt="avatar" className="w-full h-full object-cover" />
  </div>
);

export default Avatar;
