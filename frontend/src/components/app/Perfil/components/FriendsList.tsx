import React from "react";

const friends = [
  { id: 1, name: "Valentín", avatar: "/avatars/avatar2.png", level: 5, streak: 10 },
  { id: 2, name: "Agustín", avatar: "/avatars/avatar3.png", level: 3, streak: 4 },
  { id: 3, name: "Lucía", avatar: "/avatars/avatar4.png", level: 7, streak: 22 },
];

const FriendsList: React.FC = () => (
  <div className="grid gap-4">
    {friends.map((friend) => (
      <div
        key={friend.id}
        className="flex items-center gap-4 p-4 bg-[var(--Blue2)] rounded-lg shadow"
      >
        <img
          src={friend.avatar}
          alt={friend.name}
          className="w-12 h-12 rounded-full object-cover"
        />
        <div className="flex-1">
          <h3 className="font-bold">{friend.name}</h3>
          <p className="text-sm text-gray-400">
            Nivel {friend.level} • 🔥 {friend.streak} días
          </p>
        </div>
      </div>
    ))}
  </div>
);

export default FriendsList;
