<?php
$eq = "\033[1;33m=======================";
for ($i = 0; $i < strlen($_SERVER['SERVER_NAME']); ++$i)
	$eq .= "=";
echo $eq . "

\033[1;97mWelcome to the \033[1;33m{$_SERVER['SERVER_NAME']} \033[1;97mserver!
\033[1;97mToday is \033[1;33m" . date("Y/m/d") . "
\033[1;97mWrite \033[1;33mhelp \033[1;97mfor help.

\033[1;97mRules:
" . file_get_contents("rules") . "
$eq";
