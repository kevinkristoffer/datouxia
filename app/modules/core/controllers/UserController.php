<?php

class Core_UserController extends Zend_Controller_Action
{

    public function indexAction()
    {

    }

    public function listAction()
    {
        if ($this->_request->isPost()) {
            if (!isset ($_SERVER ['HTTP_X_REQUESTED_WITH']) ||
                strtolower($_SERVER ['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'
            ) {
                exit();
            }
            $this->_helper->getHelper('viewRenderer')->setNoRender();
            $this->_helper->getHelper('layout')->disableLayout();

            $params = $this->_request->getParams();
            if (!array_key_exists('pageIndex', $params) || !array_key_exists('limit', $params) ||
                !preg_match('/^(\d+)$/', $params['pageIndex']) || !preg_match('/^(\d+)$/', $params['limit'])
            )
                exit();
            try{
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('core_User');
                $users = $modelManager->core_User->queryUserList();
            }catch (Exception $ex){
                $users=array();
            }
            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($users));
        }
    }

    public function detailAction()
    {

    }

    public function addAction()
    {

    }

    public function generateIdAction()
    {

    }

    public function editAction()
    {

    }
}
