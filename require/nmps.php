<?php

$argv = explode("\1", $_SERVER['HTTP_ARGV']);
db::query("DELETE FROM authTokens WHERE UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(lastUsage) >= 60");

$authToken = $_SERVER['HTTP_AUTH_TOKEN'];

if (!isset($unauthorized)) {
	$userinfo = db::query("SELECT users.*, authTokens.addressBefore, authTokens.addressAfter
	FROM users, authTokens
	WHERE authTokens.token=:token
	AND authTokens.userId=users.id", [':token' => sha1($authToken)])[0];
	if (count($userinfo)) {
		db::query("UPDATE users SET lastOnline=CURRENT_TIMESTAMP WHERE id=:userId",
		[':userId' => $userinfo['id']]);
		db::query("UPDATE authTokens SET lastUsage=CURRENT_TIMESTAMP WHERE token=:token",
		[':token' => sha1($authToken)]);
	} else {
		echo "\004\001\033[1;31mDisconnected from server";
		exit(0);
	}
}

class db {
	private static function
	connect()
	{
		try {
			$dbCredentials = require("../nmpsdb.php");
			$pdo = new PDO("mysql:host={$dbCredentials['host']};dbname={$dbCredentials['database']};charset=utf8", $dbCredentials['username'], $dbCredentials['password'], [
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]);
			return $pdo;
		} catch(PDOException $error) {
			exit("Database error");
		}
	}

	public static function
	query($query, $parameters = [])
	{
		$statement = self::connect() -> prepare($query);
		$statement -> execute($parameters);
		if(explode(' ', $query)[0] == 'SELECT')
		{
			$data = $statement -> fetchAll();
			return $data;
		}
	}
}

class userAccount
{
	public static function
	usernameToId($username)
	{
		$id = db::query("SELECT id FROM users WHERE username=:username", [':username' => $username]);
		if ($id) return $id[0]['id'];
		else return false;
	}

	public static function
	idToUsername($id)
	{
		$username = db::query("SELECT username FROM users WHERE id=:id", [':id' => $id]);
		if ($username) return $username[0]['username'];
		else return false;
	}

	public static function
	isUserOnline($id)
	{
		return count(db::query("SELECT * FROM authTokens WHERE userId=:userId", [':userId' => $id]));
	}

	public static function
	getOnline()
	{
		return db::query("SELECT *, UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(authTokens.lastUsage) AS timeDifference FROM authTokens WHERE 1");
	}
}

class nmps
{
	public static function
	sendEvent($target, $sender, $eventType, $eventData)
	{
		return db::query("INSERT INTO events (userId, senderId, eventType, eventData) VALUES (:userId, :senderId, :eventType, :eventData)",
			[':userId' => $target, ':senderId' => $sender,
			':eventType' => $eventType,
			':eventData' => ((0 ? "" :
				"\033[1;33m[\033[1;97m" . date("H:i:s") . "\033[1;33m] \033[0;97m") .
			$eventData)]);
	}

	public static function
	printEvent($eventData)
	{
		echo "\033[1;33m[\033[1;97m" . date("H:i:s") . "\033[1;33m] \033[0;97m" . $eventData;
	}
}
