<?php
	require_once "require/nmps.php";
	$i = -1;
	while (++$i < count($argv))
		printf("%s", ($i ? " " : "") . $argv[$i]);
