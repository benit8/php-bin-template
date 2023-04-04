<?php declare(strict_types=1);

use Amp\Log\ConsoleFormatter;
use Amp\Log\StreamHandler;
use Amp\Mysql\MysqlConfig;
use Amp\Mysql\MysqlConnectionPool;
use Amp\Sql\Common\ConnectionPool;
use Amp\Sql\SqlConfig;
use Illuminate\Config\Repository as Configuration;
use Illuminate\Container\Container;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;
use PhpBinTemplate\Application;
use Psr\Log\LoggerInterface;
use Symfony\Component\Dotenv\Dotenv as Environment;

use function Amp\ByteStream\getStderr;
use function Amp\File\openFile;

// -----------------------------------------------------------------------------

($env = new Environment())->loadEnv(
	path: \dirname(__DIR__) . '/.env',
	defaultEnv: 'production',
	overrideExistingVars: true
);

$container = new Container();
$container->bind(Application::class);

// Environment & configuration
$container->instance(Environment::class, $env);

$container->singleton(Configuration::class, function ($app) {
	$directory = \dirname(__DIR__) . '/config';
	$config = new Configuration();

	try {
		$contents = new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS);

		/** @var \SplFileInfo $f */
		foreach ($contents as $pathName => $f) {
			if ($f->isFile() && $f->isReadable() && $f->getExtension() === 'php') {
				$config->set($f->getBasename('.' . $f->getExtension()), include $pathName);
			}
		}
	} catch (\UnexpectedValueException $e) {
		// Directory doesn't exist, ignore.
	}

	return $config;
});

// Logging
$container->singleton(LoggerInterface::class, function ($app) {
	$stderrHandler = new StreamHandler(getStderr());
	$stderrHandler->setFormatter(new ConsoleFormatter());

	$jsonlHandler = new StreamHandler(openFile($_ENV['LOG_JSONL_FILENAME'], 'a'), $_ENV['LOG_LEVEL'] ?? 'ERROR', $_ENV['APP_ENV'] === 'dev');
	$jsonlHandler->setFormatter(new JsonFormatter(JsonFormatter::BATCH_MODE_NEWLINES));

	return (new Logger('default', [$jsonlHandler, $stderrHandler]))
		->useLoggingLoopDetection(false);
});

// MySQL facilities
$container->bind(SqlConfig::class, function ($app) {
	$config = $app['config']->get('database');
	$config = $config['connections'][$config['default']];
	return new MysqlConfig(
		$config['host'],
		(int) $config['port'],
		$config['user'],
		$config['password'],
		$config['dbname'],
		null,
		$config['charset'],
		$config['collate'],
	);
});

$container->singleton(ConnectionPool::class, function ($app) {
	return new MysqlConnectionPool(
		$app[SqlConfig::class],
		$app['config']->get('database.max-connections', ConnectionPool::DEFAULT_MAX_CONNECTIONS)
	);
});

// Aliases
$container->alias(Environment::class, 'env');
$container->alias(Configuration::class, 'config');
$container->alias(LoggerInterface::class, 'log');
$container->alias(ConnectionPool::class, 'db');

return $container;
