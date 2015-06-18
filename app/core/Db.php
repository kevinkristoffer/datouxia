<?php

class Puppy_Core_Db
{

    const CONNECTION_KEY = 'Puppy_Core_Db_Connection_Key';

    const PREFIX_KEY = 'Puppy_Core_Db_Connection_Table_Prefix';

    /**
     *
     * @return string
     */
    public static function getPrefix()
    {
        if (!Zend_Registry::isRegistered(self::PREFIX_KEY)) {
            $config = Puppy::getIniConfig('db');
            Zend_Registry::set(self::PREFIX_KEY, $config ['db'] ['params'] ['prefix']);
        }
        return Zend_Registry::get(self::PREFIX_KEY);
    }

    /**
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public static function getConnection()
    {
        if (!Zend_Registry::isRegistered(self::CONNECTION_KEY)) {
            $config = Puppy::getIniConfig('db');
            $db = Zend_Db::factory($config ['db'] ['adapter'], $config ['db'] ['params']);
            $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $db->query("SET CHARACTER SET 'utf8'");
            Zend_Registry::set(self::CONNECTION_KEY, $db);
        }
        return Zend_Registry::get(self::CONNECTION_KEY);
    }
}

?>