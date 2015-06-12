<?php

class Core_RoleController extends Zend_Controller_Action
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

            try{
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('core_Role');
                $roles = $modelManager->core_Role->queryAllRoles();
            }catch (Exception $ex){
                $roles=array();
            }
            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($roles));
        }
        else {
            exit();
        }
    }

    public function detailAction()
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
            if (!array_key_exists('code', $params) || !preg_match('/^[A-Z]{2}$/', $params['code']))
                exit();

            $db = Puppy_Core_Db::getConnection();
            $modelManager = Puppy_Core_Model_Manager::getInstance();
            $modelManager->setDbConnection($db);
            $modelManager->registerModel('core_Role');
            $role = $modelManager->core_Role->queryRoleDetail($params['code']);

            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($role));
        }
        else {
            exit();
        }

    }

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
            if (!array_key_exists('role-code', $params) || !array_key_exists('role-name', $params) ||
                !array_key_exists('role-status', $params) || !array_key_exists('role-desc', $params) ||
                !preg_match('/^[A-Z]{2}$/', $params['role-code']) || strlen($params['role-name']) > 20 ||
                strlen($params['role-desc']) > 100 || !preg_match('/^(Y|N)$/', $params['role-status'])
            )
                exit();
            /*
             * Check role code if exists
             */
            try {
                $db = Puppy_Core_Db::getConnection();
                $prefix = Puppy_Core_Db::getPrefix();
                $select = $db->select()
                    ->from(array('a' => $prefix . 'core_role'), array('total' => 'count(*)'))
                    ->where('rolecode=?', $params['role-code']);
                $result = $select->query()->fetch();
                if ($result->total > 0) {
                    $response = array('success' => false,
                        'info' => $this->view->translator('role_add_code_exists'));
                }
                else {
                    $role = array('rolecode' => $params['role-code'],
                        'rolename' => $params['role-name'],
                        'description' => $params['role-desc'],
                        'validflag' => $params['role-status']);
                    $modelManager = Puppy_Core_Model_Manager::getInstance();
                    $modelManager->setDbConnection($db);
                    $modelManager->registerModel('core_Role');
                    $affectedRows = $modelManager->core_Role->addRole($role);
                    if ($affectedRows > 0)
                        $response = array('success' => true,
                            'info' => $this->view->translator('role_add_success'));
                    else
                        throw new Exception();
                }
            } catch (Exception $ex) {
                $response = array('success' => false,
                    'info' => $this->view->translator('role_add_failure'));
            }

            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($response));
        }
        else {
            exit();
        }
    }

    public function editAction()
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
            if (!array_key_exists('role-code', $params) || !array_key_exists('role-name', $params) ||
                !array_key_exists('role-status', $params) || !array_key_exists('role-desc', $params) ||
                !preg_match('/^[A-Z]{2}$/', $params['role-code']) || strlen($params['role-name']) > 20 ||
                strlen($params['role-desc']) > 100 || !preg_match('/^(Y|N)$/', $params['role-status'])
            )
                exit();
            /*
             * Check role code if exists
             */
            try {
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('core_Role');
                $originRole = $modelManager->core_Role->queryRoleDetail($params['role-code']);
                if ($originRole == null) {
                    $response = array('success' => false,
                        'info' => $this->view->translator('role_not_exists'));
                }
                else {
                    /*
                     * Abort to update to invalid if some users belongs to it
                     */
                    $userCount = $modelManager->core_Role->countRoleUser($params['role-code']);
                    if ($originRole->validflag == 'Y' && $params['role-status'] == 'N' && $userCount > 0) {
                        $response = array('success' => false,
                            'info' => $this->view->translator('role_users_not_empty'));
                    }
                    else {
                        $set = array('rolename' => $params['role-name'],
                            'description' => $params['role-desc'],
                            'validflag' => $params['role-status']);
                        $where['rolecode=?'] = $params['role-code'];
                        $modelManager->core_Role->updateRole($set, $where);
                        $response = array('success' => true,
                            'info' => $this->view->translator('role_edit_success'));
                    }
                }
            } catch (Exception $ex) {
                $response = array('success' => false,
                    'info' => $this->view->translator('role_edit_failure'));
            }

            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($response));
        }
    }


}
