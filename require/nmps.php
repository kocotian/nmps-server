<?php

$argv = explode("\1", $_SERVER['HTTP_ARGV']);
db::query("DELETE FROM authTokens WHERE UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - UNIX_TIMESTAMP(lastUsage) >= 300");

if (!isset($unauthorized)) {
	$authToken = $_SERVER['HTTP_AUTH_TOKEN'];
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

class db
{
	private static function connect()
	{
		try
		{
			$dbCredentials = require("../nmpsdb.php");
			$pdo = new PDO("mysql:host={$dbCredentials['host']};dbname={$dbCredentials['database']};charset=utf8", $dbCredentials['username'], $dbCredentials['password'], [
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]);
			return $pdo;
		}
		catch(PDOException $error)
		{
			exit("Database error");
		}
	}

	public static function query($query, $parameters = [])
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
