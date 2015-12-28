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
     * @return $this|null|string
     */
    public function translator($key = null)
    {
        if (null == $key) {
            return $this;
        }

        $module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();

        $file = APPLICATION_PATH . DS . 'modules' . DS . $module . DS . 'languages' . DS . 'lang.' . self::$_lang .
                '.ini';
        if (file_exists($file) && file_get_contents($file) != '') {
            /*
             * 取$key以后的全部参数
             */
            $args = func_get_args();
            if (count($args) > 1)
                $args = array_slice($args, 1);
            /*
             * 正则匹配替换${1}、${2} ....
             */
            $translate = new Zend_Translate('Ini', $file, self::$_lang);
            $value = $translate->_($key);
            preg_match_all('/#\{(\d+)\}/', $value, $matches);
            $patterns = array();
            for ($i = 0; $i < count($matches[0]); $i++)
                $patterns[$i] = '/#\{' . ($i + 1) . '\}/';

            return preg_replace($patterns, $args, $value);
        }
        return null;
    }
}