## Log Module

### usage:

	<?php
	require '/path/to/witty.php';
	Witty::init();
	Witty::set_config_dir('/path/to/config');

	$log = Witty::instance('Log');

	$log->set_logger(__FILE__);
	$log->debug('hello world');

### config file content

	<?php
	return array(
		'Log' => array(
			'loggers' => array(
				'base' => '/path/to/base/dir/', // it will be prefixed to reset item
				'system' => 'system',
				'app' => 'app'
			),
			// this is the method you call call , eg: $log->debug
			'levels' => array('DEBUG', 'INFO', 'ERROR', 'WARN', 'FATAL'),
			// there can be many adapters
			'adapters' => array(
				'file' => array(
					'level' => array('DEBUG'),
					'formatter' => 'generic',
					'enabled' => true, // set to false to disable this adapter
					'config' => array(
						'dir' => '/home/lzyy/lab/tmp/log',
					),
				),
			),
			// you can define custom formatters
			'formatters' => array(
				'generic' => '{time} {level} [{logger}] {uri} """{message}"""',
			),
		),
	);

### log content demo

	# 2011-01-20.log
	2011/01/20 23:38:40 DEBUG [app.test] /lab/tmp/demo/app/test.php """hello world"""
	2011/01/20 23:40:29 DEBUG [system.core] /lab/tmp/demo/system/core.php """foo bar, some message"""

