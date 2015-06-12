<?php

class Puppy_Modules_Layout_Widgets_Header_Widget extends Puppy_Core_Widget {
	
	/*
	 * (non-PHPdoc) @see Puppy_Core_Widget::_prepareDisplay()
	 */
	protected function _prepareDisplay()
	{
		// TODO Auto-generated method stub
		$db = Puppy_Core_Db::getConnection ();
		$modelManager = Puppy_Core_Model_Manager::getInstance ();
		$modelManager->setDbConnection ( $db );
		$modelManager->registerModel ( 'user' );
		
		$user = $modelManager->user->getUserDetail ( rand ( 1, 50 ), array (
				'accountname',
				'email',
				'rolename',
				'comname' ) );
		$this->_view->assign ( 'currentUser', $user );
	}
}