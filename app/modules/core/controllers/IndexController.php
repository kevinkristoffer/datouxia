<?php

class Core_IndexController extends Zend_Controller_Action
{

    /*
     * Index page
     */
    public function containerAction()
    {
        try {
            $db = Puppy_Core_Db::getConnection();
            $modelManager = Puppy_Core_Model_Manager::getInstance();
            $modelManager->setDbConnection($db);
            $modelManager->registerModel('core_Forum');
            $menus= $modelManager->core_Forum->queryValidMenu();
            $this->view->assign('menus',$menus);
            $this->view->assign('menusJSON', json_encode($menus, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES));
            //$this->view->assign('forums', json_encode($forums));
        } catch (Exception $ex) {
            $this->redirect('/core/error');
        }
    }

    public function dashboardAction()
    {

    }

    public function errorAction()
    {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();
        echo '<h3>System Error!</h3>';
    }
}
