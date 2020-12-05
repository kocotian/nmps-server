<?php
require_once "require/nmps.php";

$onlineUsers = db::query("SELECT *, UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(users.lastOnline) AS timeDifference FROM users WHERE UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(users.lastOnline) < 300");
echo "\033[1;97mNow online:";
foreach ($onlineUsers as $onlineUser) {
	echo "\n\033[0;97m" . $onlineUser['username'] . str_repeat(" ", 16 - strlen($onlineUser['username'])) . "\033[0;33m" . $onlineUser['lastAddress'] . str_repeat(" ", 16 - strlen($onlineUser['lastAddress'])) . "\033[0;97m" . ($onlineUser['timeDifference'] > 60 ? (" \033[0;33m(\033[1;33m" . $onlineUser['timeDifference'] . " \033[0;33mseconds ago)") : "");
}
