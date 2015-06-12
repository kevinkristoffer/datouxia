<?php

class Puppy_Core_Controller_Plugin_Ini extends Zend_Controller_Plugin_Abstract
{

    /*
     * Load view helper
     */
    public function preDispatch($request)
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null === $viewRenderer->view) {
            $viewRenderer->initView();
        }
        $view = $viewRenderer->view;
        $module = strtolower($request->getModuleName());
        // 		$view->doctype('XHTML1_STRICT');
        $view->addHelperPath(APPLICATION_PATH . DS . 'core' . DS . 'view' . DS . 'helper', 'Puppy_Core_View_Helper');
        $view->addHelperPath(APPLICATION_PATH . DS . 'modules' . DS . $module . DS . 'views' . DS . 'helpers',
            'Puppy_View_Helper_' . $module . '_');

        // 		$view->headMeta()->appendName('description', $config->web->meta_description);
        // 		$view->headMeta()->appendName('keywords', $config->web->meta_keyword);
    }
}