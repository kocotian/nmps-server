<?php

$argv = explode("\1", $_SERVER['HTTP_ARGV']);

if (!isset($unauthorized)) {
	$authToken = $_SERVER['HTTP_AUTH_TOKEN'];
	$userinfo = db::query("SELECT users.* FROM users, authTokens WHERE
	authTokens.token=:token AND
	authTokens.userId=users.id", [':token' => sha1($authToken)])[0];
	if (count($userinfo))
		db::query("UPDATE users SET lastOnline=CURRENT_TIMESTAMP WHERE id=:userId",
		[':userId' => $userinfo['id']]);
}

class db
{
	private static function connect()
	{
		try
		{
			$dbCredentials = require("dbconnect.php");
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
