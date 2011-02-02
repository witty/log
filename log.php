<?php
/**
 * Log Class
 *
 * @author lzyy http://blog.leezhong.com
 * @version 0.1.1
 */
class Log extends Witty_Base
{
	protected $_config = array(
		'loggers' => array(
			'base' => '',
			'system' => 'system',
			'app' => 'app'
		),
		'levels' => array('DEBUG', 'INFO', 'ERROR', 'WARN', 'FATAL'),
		'adapters' => array(
			'file' => array(
				'level' => array('DEBUG'),
				'formatter' => 'generic',
				'enabled' => true,
				'config' => array(
					'dir' => '/var/log',
				),
			),
		),
		'formatters' => array(
			'generic' => '{time} {level} [{logger}] {uri} """{message}"""',
		),
	);

	protected $_logger = '';
	protected $_logger_adapters = array();

	/**
	 * set adapters after construct
	 * 
	 * @since 0.1.1
	 */
	protected function _after_construct()
	{
		foreach ($this->_config['adapters'] as $driver => $adapter)
		{
			if (!empty($adapter['enabled']) && $adapter['enabled'] == true)
			{
				$this->_logger_adapters[$driver] = $adapter;
			}
		}
	}

	public function set_logger($filepath)
	{
		$config = $this->_config;
		$base_path = rtrim($config['loggers']['base'], '/').'/';
		unset($config['loggers']['base']);

		$dest_logger = $filepath;
		foreach ($config['loggers'] as $logger => $path)
		{
			if (substr($filepath, 0, strlen($base_path.$path)) == $base_path.$path)
			{
				$filepath = substr(str_replace($base_path.$path.DIRECTORY_SEPARATOR, '', $filepath), 0, -4);
				$dest_logger = $logger.'.'.str_replace(DIRECTORY_SEPARATOR, '.', $filepath);
				break;
			}
		}
		$this->_logger = $dest_logger;
	}

	public function __call($method, $args)
	{
		$config = $this->_config;
		$method = strtoupper($method);
		if (!in_array($method, $config['levels']))
		{
			throw new Log_Exception('method not allowed: {method}', array('{method}' => $method));
		}
		foreach ($this->_logger_adapters as $driver => $adapter)
		{
			if (in_array($method, $adapter['level']))
			{
				$class = 'Log_Adapter_'.strtoupper($driver);
				$class = Witty::instance($class, $config);
				$class->set_formatter_args(array(
					'message' => $args[0],
					'level' => $method,
					'logger' => $this->_logger,
				));
				$class->save();
			}
		}
		parent::__call($method, $args);
	}
}
