<?php

require_once "require/nmps.php";

$inventories = db::query("SELECT * FROM inventories WHERE userId=:userId", [':userId' => $userinfo['id']]);

if (!count($inventories)) {
	echo "\033[1;33m* \033[0;97mCreating new inventory\n";
	db::query("INSERT INTO storages VALUES ()");
	echo "\033[1;32m* \033[0;97mCreated storage\n";
	$storage = db::query("SELECT * FROM storages ORDER BY id DESC LIMIT 1")[0];
	db::query("INSERT INTO inventories (userId, storageId) VALUES (:userId, :storageId)",
	[':userId' => $userinfo['id'], ':storageId' => $storage['id']]);
	echo "\033[1;32m* \033[0;97mCreated main inventory\n";
	for ($i = 0; $i < 9; ++$i)
		db::query("INSERT INTO slots (storageId) VALUES (:storageId)",
		[':storageId' => $storage['id']]);
	echo "\033[1;32m* \033[0;97mCreated 9 slots\n";
	$inventories = db::query("SELECT * FROM inventories WHERE userId=:userId", [':userId' => $userinfo['id']]);
	echo "\033[1;32mCreated new inventory with id #0\033[0;32m" . $inventories[0]['id'] . "\n";
}

$index = -1;
echo "\033[1;97mYour inventories:\n";
foreach ($inventories as $inv) {
	$slotindex = -1;
	echo "\033[1;33m" . ++$index . ". \033[0;37m" . ($inv['name'] ? $inv['name'] : "no name") . " \033[1;97m[\033[0;97m#0" . $inv['id'] . "\033[1;97m]:\n";
	$slots = db::query("SELECT * FROM slots WHERE storageId=:storageId",
	[':storageId' => $inv['storageId']]);
	if (!count($slots))
		echo "\033[1;97m| \033[0;33mno slots.\n";
	foreach ($slots as $slot) {
		$item = db::query("SELECT * FROM items WHERE slotId=:slotId",
		[':slotId' => $slot['id']])[0];
		echo "\033[1;97m| \033[0;33m" . ++$slotindex . ". \033[1;37m[\033[0;97m" .
		   	($item ? $item['name'] : "\033[1;97mempty") . "\033[1;37m]\n";
	}
}
