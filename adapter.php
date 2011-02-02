<?php

abstract class Log_Adapter extends Witty_Base
{

	protected $_formatter;
	protected $_local_config = array();
	protected $_log_formatter;

	protected function _before_config($config)
	{
		$this->_config = $config;
	}

	protected function _after_construct()
	{
		$config = $this->_config;
		$class = get_class($this);
		$curr_driver = explode('_', $class);
		$curr_driver = strtolower($curr_driver[count($curr_driver)-1]);

		foreach ($config['adapters'] as $driver => $adapter)
		{
			if ($curr_driver == $driver)
			{
				$this->_formatter = $config['formatters'][$adapter['formatter']];
				$this->_local_config = $adapter['config'];
				break;
			}
		}
		$this->_log_formatter = new Log_Formatter();
	}

	public function set_formatter_args($args)
	{
		$this->_log_formatter->args = $args;
	}

	protected function _format()
	{
		preg_match_all('/\{([a-zA-Z_-]+)\}/u', $this->_formatter, $matches);
		$replace_arr = array();
		foreach($matches[1] as $key)
		{
			$replace_arr['{'.$key.'}'] = $this->_log_formatter->{'get_'.$key}();
		}
		return strtr($this->_formatter, $replace_arr);
	}

	abstract function save();
}
