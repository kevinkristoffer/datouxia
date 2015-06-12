<?php

class Puppy_Core_Model
{

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db;
    /**
     *
     * @var string
     */
    protected $_prefix;

    function __construct($dbAdapter)
    {
        $this->_db = $dbAdapter;
        $this->_prefix = Puppy_Core_Db::getPrefix();
    }
}