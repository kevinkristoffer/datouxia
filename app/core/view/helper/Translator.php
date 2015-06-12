<?php

class Puppy_Core_View_Helper_Translator extends Zend_View_Helper_Abstract
{

    /**
     * @var string
     */
    private static $_lang = null;

    public function __construct()
    {
        $config = Puppy::getIniConfig('web');
        self::$_lang = $config['lang'];
    }

    /**
     * @param string $key
     * @param string $module
     * @return $this|null|string
     */
    public function translator($key = null, $module = null)
    {
        if (null == $key && null == $module) {
            return $this;
        }
        if (null == $module) {
            //Get current module
            $module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
        }
        $file = APPLICATION_PATH . DS . 'modules' . DS . $module . DS . 'languages' . DS . 'lang.' . self::$_lang .
                '.ini';
        if (file_exists($file) && file_get_contents($file) != '') {
            $translate = new Zend_Translate('Ini', $file, self::$_lang);
            return $translate->_($key);
        }
        return null;
    }
}