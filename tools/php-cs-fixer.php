<?php

$finder = PhpCsFixer\Finder::create()
	->in(dirname(__DIR__) . '/bootstrap')
	->in(dirname(__DIR__) . '/config')
	->in(dirname(__DIR__) . '/src')
	->in(dirname(__DIR__) . '/tests')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
		'@PSR12' => true,
		'@PHP81Migration' => true,
		'@PHP80Migration:risky' => true,

		'concat_space' => ['spacing' => 'one'],
		'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
	])
	->setFinder($finder)
	->setIndent("\t")
;
