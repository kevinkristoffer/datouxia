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
            //搜索参数
            $where=array();
            if(array_key_exists('authid',$params) && preg_match('/^[0-9]{8}$/',$params['authid']))
                $where['a.authid=?']=$params['authid'];
            try {
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('core_User');
                $users = $modelManager->core_User->queryUserList($where, $params['limit'], $params['pageIndex'] *
                                                                                         $params['limit']);
                $userCount = $modelManager->core_User->countUser($where);
                sleep(3);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                $users = array();
                $userCount = 0;
            }
            $responseBody = array('rows' => $users,
                'results' => $userCount);
            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($responseBody));
        }
    }

    public function detailAction()
    {

    }

    public function addAction()
    {
        if ($this->_request->isPost()) {

        }
    }

    public function generateIdAction()
    {

    }

    public function editAction()
    {

    }
}
