<?php

class appconfig
{
	private static $runmode = "PRODUCTION";
	private static $httpmode = "HTTPS";
	private static $dummymode = false;
	private static $timer_cijfer_ls=60000; //Set time to execute cijfer_localstorage() function

	private static $production_baseurl = "scolpanos.qwihi.com";
	private static $development_baseurl = "server-apachecaribedev.rhcloud.com/scolpanos";

	private static $schooljaar = "2017-2018";

	public static function GetRunMode()
	{
		return self::$runmode;
	}

	public static function GetHTTPMode()
	{
		return self::$httpmode;
	}
	// Caribe Developers
	public static function GetDummy()
	{
		return self::$dummymode;
	}
	public static function GetTimerCijfer_ls()
	{
		return self::$timer_cijfer_ls;
	}
	// End code Caibe Developers

	public static function GetBaseURL()
	{
		$httpmode = strtolower(self::$httpmode);

		if(self::$runmode == "PRODUCTION")
		{
			return $httpmode . "://" . self::$production_baseurl;
		}
		else if(self::$runmode == "DEVELOPMENT")
		{
			return $httpmode . "://" . self::$development_baseurl;
		}
	}

	public static function Getschooljaar()
	{
		return self::$schooljaar;
	}

}

?>
