<?php
$configs = array();

/*
 * -- Database configuration --
 * Supported servers: MySQL, MariaDB
*/
$configs['db_host'] = 'localhost';
$configs['db_user'] = 'user';
$configs['db_pass'] = 'password';
$configs['db_database'] = 'infos2';

/*
 * -- Logging configuration --
 * log_level = INF | WAR | ERR
 * 	- INF: Informations, warnings and errors
 * 	- WAR: warnings and errors
 *	- ERR: errors only
*/
$configs['log_level'] = 'INF';

?>