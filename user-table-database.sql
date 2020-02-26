CREATE TABLE users (
  idUsers int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
  uidUsers TINYTEXT NOT NULL,
  emailUsers TINYTEXT NOT NULL,
  pwdUsers LONGTEXT NOT NULL,
  failloginUsers TINYTEXT NOT NULL,
  activeUsers TINYTEXT NOT NULL
);


CREATE TABLE pwdReset (
  pwdResetId int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  pwdResetEmail TEXT NOT NULL,
  pwdResetSelector TEXT NOT NULL,
  pwdResetToken LONGTEXT NOT NULL,
  pwdResetExpires TEXT NOT NULL
);


CREATE TABLE unblock (
  unblockId int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  unblockEmail TEXT NOT NULL,
  unblockSelector TEXT NOT NULL,
  unblockToken LONGTEXT NOT NULL
);
