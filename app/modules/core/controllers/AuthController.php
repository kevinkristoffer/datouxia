<?php

class Core_AuthController extends Zend_Controller_Action {

	public function loginAction()
	{
		$this->_helper->getHelper ( 'layout' )
			->disableLayout ();
		if ($this->_request->isGet ())
		{
		}
		if ($this->_request->isPost ())
		{
			$this->_helper->getHelper ( 'viewRenderer' )
				->setNoRender ();
			$params = $this->_request->getParams ();
			if (! array_key_exists ( 'f_id', $params ) || ! preg_match ( '/^(\d){8}$/', $params ['f_id'] ) || ! array_key_exists ( 'f_pwd', $params ) || strlen ( $params ['f_pwd'] ) > 20)
			{
				exit ();
			}
			$username = $params ['f_id'];
			$password = $params ['f_pwd'];
			
			$auth = Zend_Auth::getInstance ();
			$authAdapter = new Puppy_Modules_Core_Services_Auth ( $username, $password );
			$authResult = $auth->authenticate ( $authAdapter );
			switch ($authResult->getCode ())
			{
				case Puppy_Modules_Core_Services_Auth::FAILURE :
					$message = 'login failure';
					break;
				case Puppy_Modules_Core_Services_Auth::NOT_ACTIVE :
					$message = 'account not active';
					break;
				case Puppy_Modules_Core_Services_Auth::SUCCESS :
					$message = 'login success';
					break;
				default :
			}
			$this->_response->setHeader ( 'content-type', 'text/html;charset=utf-8' );
			$this->_response->setBody ( $message );
		}
	}

	public function testAuthAction()
	{
		$this->_helper->getHelper ( 'layout' )
			->disableLayout ();
		$this->_helper->getHelper ( 'viewRenderer' )
			->setNoRender ();
		$this->_response->setHeader ( 'content-type', 'text/html;charset=utf-8' );
		
		$auth = Zend_Auth::getInstance ();
		if ($auth->hasIdentity ())
		{
			$user = $auth->getIdentity ();
			// Clear Session
			// Zend_Session::destroy ( false, false );
			
			// $auth->clearIdentity ();
			$this->_response->setBody ( 'Current User:' . $user->accountname );
		}
	}

	public function logoutAction()
	{
	}
}
