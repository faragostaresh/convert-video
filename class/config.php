<?php 

class Config
{
	public static function WakingUp()
	{
		// Start session
		session_start();
		// require class
		require_once 'language.php';
		require_once 'general.php';
		require_once 'convert.php';
	}

	public static function getHost($host)
	{
		$list = self::setHost();
		if (array_key_exists($host, $list)) {
			return $list[$host];
		}

		return '';
	}

	public static function setHost()
	{
		$host = array(
			'mainUrl'     => '',
			'mainPath'    => '',
			'sourcePath'  => '',
			'loginEmail'  => '',
			'loginPass'   => '',
		);

		return $host;
	}

	public static function getConvertConfig($config)
	{
		$list = self::setConvertConfig();
		if (array_key_exists($config, $list)) {
			return $list[$config];
		}
		
		return '';
	}

	public static function setConvertConfig()
	{
		$config = array(
			'size_medium'  => '640x480',
			'size_low'     => '320x240',
		);

		return $config;
	}
}
?>