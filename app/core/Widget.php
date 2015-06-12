<?php

abstract class Puppy_Core_Widget
{

    /**
     *
     * @var Zend_Controller_Request_Abstract
     */
    protected $_request;

    /**
     *
     * @var Zend_Controller_Response_Abstract
     */
    protected $_response;

    /**
     *
     * @var Zend_View_Abstract
     */
    protected $_view;

    /**
     * Name of module that widget belongs
     *
     * @var string
     */
    protected $_module;

    /**
     * Name of widget
     *
     * @var string
     */
    protected $_name;

    protected $_helperPaths;

    const CURRENT_WIDGET_KEY = 'Puppy_Core_Widget_CurrentWidgetKey';

    public function __construct($module, $name)
    {
        $this->_module = strtolower($module);
        $this->_name = strtolower($name);

        $front = Zend_Controller_Front::getInstance();
        $request = $front->getRequest();
        $response = $front->getResponse();
        $this->_request = clone $request;
        $this->_response = clone $response;
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $this->_view = clone $viewRenderer->view;

        $this->_helperPaths = $this->_view->getHelperPaths();
        // 		$this->_view->addHelperPath ( APPLICATION_PATH . DS . 'modules' . DS . $this->_module . DS . 'views' . DS . 'helpers', 'Puppy_View_Helper_' . $this->_module . '_' );
        $this->_view->addHelperPath(APPLICATION_PATH . DS . 'modules' . DS . $this->_module . DS . 'widgets' . DS .
                                    $this->_name, 'Puppy_Widget_' . $this->_name . '_');
    }

    /**
     * Get name of widget
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Get name of module
     *
     * @return string
     */
    public function getModule()
    {
        return $this->_module;
    }

    private function _reset()
    {
        $params = $this->_request->getUserParams();
        foreach (array_keys($params) as $key) {
            $this->_request->setParam($key, null);
        }

        $this->_response->clearBody();
        $this->_response->clearHeaders()->clearRawHeaders();
    }

    public function __call($name, $arguments)
    {
        $this->_reset();

        if ($arguments != null && is_array($arguments) && count($arguments) > 0) {
            if ($arguments [0] != null && is_array($arguments [0])) {
                $this->_request->setParams($arguments [0]);
            }
        }

        Zend_Registry::set(self::CURRENT_WIDGET_KEY, $this);

        // Prepare data
        $prepare = '_prepare' . ucfirst($name);
        if (method_exists($this, $prepare)) {
            $this->$prepare ();
        }
        $name = strtolower($name);

        $path = APPLICATION_PATH . DS . 'modules' . DS . $this->_module . DS . 'widgets' . DS . $this->_name;
        if (file_exists($path . DS . $name . '.phtml')) {
            $this->_view->addScriptPath($path);
        }

        $file = $this->_view->getScriptPath(null) . $name . '.phtml';
        if ($file != null && file_exists($file)) {
            $content = $this->_view->render($name . '.phtml');
            $this->_response->appendBody($content);
        }

        $body = $this->_response->getBody();
        $this->_reset();

        return $body;
    }

    abstract protected function _prepareDisplay();
}