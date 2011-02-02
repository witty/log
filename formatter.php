<?php

class Log_Formatter 
{
	public $args;

	public function get_time()
	{
		static $time;
		empty($time) && $time = date('Y/m/d H:i:s');
		return $time;
	}

	public function get_uri()
	{
		return $_SERVER['REQUEST_URI'];
	}

	public function get_level()
	{
		return $this->args['level'];
	}

	public function get_logger()
	{
		return $this->args['logger'];
	}

	public function get_message()
	{
		return $this->args['message'];
	}
}
