<?php

class Core_IndexController extends Zend_Controller_Action {
	
	/*
	 * Index page
	 */
	public function containerAction()
	{
		$db = Puppy_Core_Db::getConnection ();
		$modelManager = Puppy_Core_Model_Manager::getInstance ();
		$modelManager->setDbConnection ( $db );
		$modelManager->registerModel ( 'core_Forum' );
		$forums = $modelManager->core_Forum->queryValidForum ();
		$this->view->assign ( 'forums', json_encode ( $forums, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES ) );
	}

	public function dashboardAction()
	{
	}
}
