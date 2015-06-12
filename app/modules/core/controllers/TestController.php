<?php

class Core_TestController extends Zend_Controller_Action {

	public function test1Action()
	{
		$this->_helper->layout->setLayout ( 'core/layout' );
	}

	public function test2Action()
	{
		$this->_helper->viewRenderer->setNoRender ( true );
		header ( 'content-type:text/html;charset=utf-8' );
		ob_start ();
		phpinfo ();
		$info = ob_get_contents ();
		$file = fopen ( 'test2.txt', 'w' );
		fwrite ( $file, $info );
		fclose ( $file );
		echo 'php info wroten...';
	}

	public function upgradeAction()
	{
		$this->_helper->viewRenderer->setNoRender ( true );
		header ( 'content-type:text/html;charset=utf-8' );
		
		echo 'System upgrade...';
	}
}

?>