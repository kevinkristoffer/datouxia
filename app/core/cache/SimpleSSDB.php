<?php

/**
 * All methods(except *exists) returns false on error,
 * so one should use Identical(if($ret === false)) to test the return value.
 */
class Puppy_Core_Cache_SimpleSSDB {

	const KEY = 'Puppy_Core_Cache_SimpleSSDB_Key';

	const CONFIG_KEY = 'cache';

	public static function getInstance($serverType, $serverName = null)
	{
		$key = self::KEY;
		if (! Zend_Registry::isRegistered ( $key ))
		{
			$config = Puppy_Core_Config::loadIniConfig ( APPLICATION_PATH . DS . 'configs' . DS, 'cache.ini', 'cache' );
			$servers = $config ['cache'] ['ssdb'] [$serverType];
			
			$selectedServer = $serverName;
			
			if (null == $serverName)
			{
				// Load balancing algorithm
				$mtime = explode ( ' ', microtime () );
				$i = $mtime [1] % count ( $servers );
				$j = 0;
				foreach ( $servers as $key => $value )
				{
					if ($i == $j ++)
					{
						$selectedServer = $key;
						break;
					}
				}
			}
			
			if (! array_key_exists ( 'timeout', $servers [$selectedServer] ))
				$servers [$selectedServer] ['timeout'] = 2000;
			
			$ssdb = new Puppy_Core_Cache_SSDB ( $servers [$selectedServer] ['host'], $servers [$selectedServer] ['port'], $servers [$selectedServer] ['timeout'] );
			$ssdb->easy ();
			
			Zend_Registry::set ( $key, $ssdb );
		}
		
		return Zend_Registry::get ( $key );
	}
}

?>