<?php
	require_once "require/nmps.php";
	echo $argv[0] == "-s" ? 0 : "\033[1;97mYou have \033[1;33m0 \033[1;97munread mails.";
