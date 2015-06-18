<?php

class Puppy_Modules_Core_Services_Auth implements Zend_Auth_Adapter_Interface {

	const SUCCESS = 1;

	const NOT_ACTIVE = - 1;

	const FAILURE = - 2;

	private $_username;

	private $_password;

	public function __construct($username, $password)
	{
		$this->_username = $username;
		$this->_password = $password;
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see Zend_Auth_Adapter_Interface::authenticate()
	 * @return boolean
	 */
	public function authenticate()
	{
		// TODO Auto-generated method stub
		$db = Puppy_Core_Db::getConnection ();
		$modelManager = Puppy_Core_Model_Manager::getInstance ();
		$modelManager->setDbConnection ( $db );
		$modelManager->registerModel ( 'core_User' );
		$user = $modelManager->core_User->queryUser ( $this->_username, $this->_password );
		if ($user == null)
		{
			return new Zend_Auth_Result ( self::FAILURE, null );
		}
		if ($user->validflag != 'Y')
		{
			return new Zend_Auth_Result ( self::NOT_ACTIVE, null );
		}
		return new Zend_Auth_Result ( self::SUCCESS, $user );
	}
}
