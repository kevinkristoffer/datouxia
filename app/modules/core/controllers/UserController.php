<?php

class Core_UserController extends Zend_Controller_Action
{
    /*
     * 系统用户管理页面
     */
    public function indexAction()
    {
        try {
            $db = Puppy_Core_Db::getConnection();
            $modelManager = Puppy_Core_Model_Manager::getInstance();
            $modelManager->setDbConnection($db);
            $modelManager->registerModel('core_Role');
            $where = array('validstatus=?' => '1');
            $fields = array('id' => 'rolecode',
                'text' => 'rolename');
            $roles = $modelManager->core_Role->queryRoleList($where, $fields);
            $this->view->assign('rolesJSON',json_encode($roles));
        } catch (Exception $ex) {
            $this->redirect('/core/error');
        }
    }

    /*
     * 查询搜索用户列表
     */
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
            if (!array_key_exists('page', $params) || !array_key_exists('pagesize', $params) ||
                !preg_match('/^(\d+)$/', $params['page']) || !preg_match('/^(\d+)$/', $params['pagesize'])
            )
                exit();
            /*
             * 搜索参数
             * sv1:authid
             * sv2:accountname
             * sv3:rolecode
             * sv4:validstatus
             */
            $where = array();
            if (array_key_exists('sv1', $params) && preg_match('/^[0-9]{8}$/', $params['sv1']))
                $where['a.authid=?'] = $params['sv1'];
            if (array_key_exists('sv2', $params) && trim($params['sv2']) != '')
                $where['a.accountname regexp ?'] = trim($params['sv2']);
            if (array_key_exists('sv3', $params) && preg_match('/^[A-Z]{2}$/', $params['sv3']))
                $where['a.rolecode=?'] = $params['sv3'];
            if (array_key_exists('sv4', $params) && preg_match('/^0|1$/', $params['sv4']))
                $where['a.validstatus=?'] = $params['sv4'];
            try {
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('core_User');
                $users = $modelManager->core_User->queryUserList($where, $params['pagesize'], ($params['page']-1 )*
                                                                                           $params['pagesize']);
                $userCount = $modelManager->core_User->countUser($where);
            } catch (Exception $ex) {
                echo $ex->getMessage();
                $users = array();
                $userCount = 0;
            }
            $responseBody = array('Rows' => $users,
                'Total' => $userCount);
            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($responseBody));
        }
    }

    /*
     * 查询用户详情
     */
    public function itemAction()
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
            if (!array_key_exists('id', $params) || !preg_match('/^[0-9]{8}$/', $params['id']))
                exit();

            $response = array();
            try {
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('core_User');
                $user = $modelManager->core_User->queryUserDetail($params['id']);
                if ($user != null)
                    $response = array('success' => true,
                        'data' => $user);
                else
                    throw new Exception();
            } catch (Exception $ex) {
                $response = array('success' => false,
                    'data' => null);
            }

            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($response));
        }
    }

    /*
     * 新增系统用户
     */
    public function addAction()
    {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();

        if ($this->_request->isPost()) {
            if (!isset ($_SERVER ['HTTP_X_REQUESTED_WITH']) ||
                strtolower($_SERVER ['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'
            ) {
                exit();
            }
            $this->_helper->getHelper('viewRenderer')->setNoRender();
            $this->_helper->getHelper('layout')->disableLayout();

            $response = array();
            /*
             * 接受参数
             * av1:accountname
             * av2:credential
             * av3:rolecode
             * av4:validstatus
             */
            $params = $this->_request->getParams();
            if (!array_key_exists('av1', $params) || $params['av1']=='' ||
                !array_key_exists('av2', $params) ||
                //!preg_match('/^(?![a-zA-Z0-9]+$)(?![^a-zA-Z/D]+$)(?![^0-9/D]+$).{8,20}$/', $params['password1']) ||
                !array_key_exists('av4', $params) || !preg_match('/^[A-Z]{2}$/', $params['av4']) ||
                !array_key_exists('av5', $params) || !preg_match('/^0|1$/', $params['av5'])
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
                        'accountname' => $params['av1'],
                        'credential' => md5($params['av2']),
                        'rolecode' => $params['av4'],
                        'validstatus' => $params['av5']);
                    $affectedRows = $modelManager->core_User->addUser($user);
                    if ($affectedRows > 0)
                        $response = array('success' => true,
                            'info' => $this->view->translator('user_add_success') . ' : ' . $authid);
                    else
                        throw new Exception();
                    $db->commit();
                } catch (Exception $ex2) {
                    $db->rollBack();
                    throw $ex2;
                }
            } catch (Exception $ex) {
                $response = array('success' => false,
                    'info' => $this->view->translator('user_add_failure'));
                echo $ex->getMessage();
            }

            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($response));
        }

    }

    /*
     * 编辑系统用户
     */
    public function editAction()
    {
        $this->_helper->getHelper('viewRenderer')->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();

        if ($this->_request->isPost()) {
            if (!isset ($_SERVER ['HTTP_X_REQUESTED_WITH']) ||
                strtolower($_SERVER ['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'
            ) {
                exit();
            }
            $this->_helper->getHelper('viewRenderer')->setNoRender();
            $this->_helper->getHelper('layout')->disableLayout();

            $response = array();
            /*
             * 接受参数
             * ev1:authid
             * ev2:accountname
             * ev3:rolecode
             * ev4:validstatus
             */
            $params = $this->_request->getParams();
            if (!array_key_exists('ev1', $params) || !preg_match('/^[0-9]{8}$/', $params['ev1']) ||
                !array_key_exists('ev2', $params) || $params['ev2'] == '' ||
                !array_key_exists('ev3', $params) || !preg_match('/^[A-Z]{2}$/', $params['ev3']) ||
                !array_key_exists('ev4', $params) || !preg_match('/^0|1$/', $params['ev4'])
            )
                exit();

            try {
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('core_User');
                $db->beginTransaction();
                try {
                    $set = array('accountname' => $params['ev2'],
                        'rolecode' => $params['ev3'],
                        'validstatus' => $params['ev4']);
                    $where['authid=?'] = $params['ev1'];
                    $affectedRows = $modelManager->core_User->updateUser($set, $where);
                    $response = array('success' => true,
                        'info' => $this->view->translator('user_edit_success'));
                    $db->commit();
                } catch (Exception $ex2) {
                    $db->rollBack();
                    throw $ex2;
                }
            } catch (Exception $ex) {
                $response = array('success' => false,
                    'info' => $this->view->translator('user_edit_failure'));
            }

            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($response));
        }
    }
}
