<?php declare(strict_types=1);

namespace PhpBinTemplate;

use Psr\Log\LoggerInterface;

// -----------------------------------------------------------------------------

final class Application
{
	/**
	 * Constructor.
	 *
	 * @param LoggerInterface $log
	 */
	public function __construct(
		private LoggerInterface $log,
	) {
	}

	/**
	 * Run the application.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$this->log->debug('Hello World!');
	}
}
