<?php

class Core_UserController extends Zend_Controller_Action
{
    /*
     * 系统用户管理页面
     */
    public function indexAction()
    {
        //查询有效的用户组
        try {
            $db = Puppy_Core_Db::getConnection();
            $modelManager = Puppy_Core_Model_Manager::getInstance();
            $modelManager->setDbConnection($db);
            $modelManager->registerModel('core_Role');
            $params = $this->_request->getParams();
            $where = array('validflag=?' => 'Y');
            $fields = array('rolecode','rolename');
            $roles = $modelManager->core_Role->queryRoleList($where,$fields);
        } catch (Exception $ex) {
            $roles = array();
        }
        $this->view->assign(array('roles'=>$roles));
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
            $where = array();
            if (array_key_exists('authid', $params) && preg_match('/^[0-9]{8}$/', $params['authid']))
                $where['a.authid=?'] = $params['authid'];
            if (array_key_exists('accountname', $params) && trim($params['accountname']) != '')
                $where['a.accountname regexp ?'] = trim($params['accountname']);
            if (array_key_exists('rolecode', $params) && preg_match('/^[A-Z]{2}$/', $params['rolecode']))
                $where['a.rolecode=?'] = $params['rolecode'];
            if (array_key_exists('validflag', $params) && preg_match('/^Y|N$/', $params['validflag']))
                $where['a.validflag=?'] = $params['validflag'];
            try {
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('core_User');
                $users = $modelManager->core_User->queryUserList($where, $params['limit'], $params['pageIndex'] *
                                                                                           $params['limit']);
                $userCount = $modelManager->core_User->countUser($where);
            } catch (Exception $ex) {
                //echo $ex->getMessage();
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

    /*
     * 新增系统用户
     */
    public function addAction()
    {
        if ($this->_request->isPost()) {
            if (!isset ($_SERVER ['HTTP_X_REQUESTED_WITH']) ||
                strtolower($_SERVER ['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'
            ) {
                exit();
            }
            $this->_helper->getHelper('viewRenderer')->setNoRender();
            $this->_helper->getHelper('layout')->disableLayout();

            $response = array();
            $params = $this->_request->getParams();
            if (!array_key_exists('accountname', $params) || !array_key_exists('password1', $params) ||
                !preg_match('/^(?![a-zA-Z0-9]+$)(?![^a-zA-Z/D]+$)(?![^0-9/D]+$).{8,20}$/', $params['password1']) ||
                !array_key_exists('rolecode', $params) || !preg_match('/^[A-Z]{2}$/', $params['rolecode']) ||
                !array_key_exists('validflag', $params) || !preg_match('/^Y|N$/', $params['validflag'])
            )
                exit();

            try {
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('core_User');
                $db->beginTransaction();
                try {
                    $authid = $modelManager->core_User->generateAuthId();
                    $user = array('authid' => $authid,
                        'accountname' => $params['accountname'],
                        'credantial' => md5($params['password1']),
                        'rolecode' => $params['rolecode'],
                        'validflag' => $params['validflag']);
                    /*$affectedRows = $modelManager->core_User->addUser($user);
                    if ($affectedRows > 0)
                        $response = array('success' => true,
                                        'info' => $this->view->translator('user_add_success')) . ' : ' . $authid;
                    else
                        throw new Exception();*/
                    var_dump($user);
                    exit();
                    $db->commit();
                } catch (Exception $ex2) {
                    $db->rollBack();
                    throw $ex2;
                }
            } catch (Exception $ex) {
                $response = array('success' => false,
                    'info' => $this->view->translator('user_add_failure'));
            }

            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($response));
        }
    }

    public function generateIdAction()
    {

    }

    public function editAction()
    {

    }
}
