<?php

class Puppy_Core_Model_Manager
{

    /**
     *
     * @var Puppy_Core_Model_Manager
     */
    private static $_instance;

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    private $_db;

    /**
     *
     * @var array
     */
    private $_models;

    /**
     *
     * @var string
     */
    private $_modelClassPrefix = 'Puppy_Model_';

    private function __construct()
    {
        $this->_models = array();
    }

    /**
     *
     * @return Puppy_Core_Model_Manager
     */
    public static function getInstance()
    {
        if (null == self::$_instance) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }

    /**
     *
     * @param Zend_Db_Adapter_Abstract $dbAdapter
     */
    public function setDbConnection($dbAdapter)
    {
        $this->_db = $dbAdapter;
    }

    /**
     *
     * @param string $prefix
     */
    public function setModelClassPrefix($prefix)
    {
        $this->_modelClassPrefix = $prefix;
    }

    /**
     *
     * @param string $modelName
     * @return Puppy_Core_Model_Manager
     */
    public function registerModel($modelName)
    {
        if (!isset ($this->{$modelName})) {
            $modelClassName = $this->_modelClassPrefix . ucwords($modelName);
            if (class_exists($modelClassName)) {
                $ref = new Zend_Reflection_Class ($modelClassName);
                $model = $ref->newInstance($this->_db);
                $this->{$modelName} = $model;
            }
        }
        return $this;
    }

    /**
     *
     * @param string $modelName
     * @return Puppy_Core_Model_Manager
     */
    public function unregisterModel($modelName)
    {
        unset ($this->{$modelName});
        return $this;
    }

    /**
     *
     * @return array:
     */
    public function getModels()
    {
        return $this->_models;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_models)) {
            return $this->_models [$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        $this->_models [$name] = $value;
    }

    public function __isset($name)
    {
        return isset ($this->_models [$name]);
    }

    public function __unset($name)
    {
        if (isset ($this->{$name})) {
            $this->_models [$name] = null;
        }
    }
}
