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
			'size_medium'                   => '320x240',
			'size_low'                      => '320x240',
			'targetBitrate_medium'          => '360k',
			'targetBitrate_low'             => '180k',
			'videoBitrateTolerance_medium'  => '416k',
			'videoBitrateTolerance_low'     => '208k',
			'audioBitRate_medium'           => '64k',
			'audioBitRate_low'              => '32k',
		);

		return $config;
	}
}
?>