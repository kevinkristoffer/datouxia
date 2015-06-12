<?php

class App extends Zend_Application_Bootstrap_Bootstrap {

	protected function _initUpgradeChecker()
	{
		if (file_exists ( APPLICATION_PATH . DS . 'maintenance.flag' ))
		{
			$this->bootstrap ( 'FrontController' );
			$front = $this->getResource ( 'FrontController' );
			$request = new Zend_Controller_Request_Http ();
			/*
			 * set redirect uri
			 */
			$request->setRequestUri ( '/puppy/default/test/upgrade' );
			$front->setRequest ( $request );
		}
	}

	/**
	 * Autolaod the class
	 *
	 * @return Zend_Loader_Autoloader
	 */
	protected function _initAutoload()
	{
		require_once APPLICATION_PATH . '/core/Autoloader.php';
		$autoloader = Zend_Loader_Autoloader::getInstance ();
		$autoloader->unshiftAutoloader ( new Puppy_Core_Autoloader (), 'Puppy' );
		return $autoloader;
	}

	protected function _initPlugins()
	{
		$this->bootstrap ( 'FrontController' );
		$front = $this->getResource ( 'FrontController' );
		
		$front->registerPlugin ( new Puppy_Core_Controller_Plugin_Ini () );
	}

	protected function _initRoutes()
	{
		$this->bootstrap ( 'FrontController' );
		$front = $this->getResource ( 'FrontController' );
		
		$routes = Puppy_Core_Module_Loader::getInstance ()->getRoutes ();
		$front->setRouter ( $routes );
		// $front->getRouter()->removeDefaultRoutes(); //开发环境关闭
		
		// Add route for index/index
		$front->getRouter ()
			->addRoute ( 'index', new Zend_Controller_Router_Route ( '/', array (
				'module' => 'front',
				'controller' => 'Index',
				'action' => 'index' ) ) );
		
		// Add routes for static pages
	}
}