<?php declare(strict_types=1);

return [

	'default' => $_ENV['DATABASE_DEFAULT_CONNECTION'] ?? 'mysql',

	// How much links should the pool keep at once.
	'max-connections' => 25,

	'connections' => [
		'mysql' => [
			'driver' => 'mysql',
			'host' => $_ENV['DATABASE_MYSQL_HOST'] ?? 'localhost',
			'port' => $_ENV['DATABASE_MYSQL_PORT'] ?? '3306',
			'user' => $_ENV['DATABASE_MYSQL_USER'] ?? 'root',
			'password' => $_ENV['DATABASE_MYSQL_PASSWORD'] ?? 'root',
			'dbname' => $_ENV['DATABASE_MYSQL_DBNAME'] ?? 'php_bin_template',
			'charset' => $_ENV['DATABASE_MYSQL_CHARSET'] ?? 'utf8',
			'collate' => 'utf8_unicode_ci',
		],
	],

];
