<?php

class Core_RoleController extends Zend_Controller_Action
{

    public function indexAction()
    {
        //        $db = Puppy_Core_Db::getConnection();
        //        $modelManager = Puppy_Core_Model_Manager::getInstance();
        //        $modelManager->setDbConnection($db);
        //        $modelManager->registerModel('core_Forum');
        //        $forums = $modelManager->core_Forum->queryValidForum();
        //        $this->view->assign('forums', json_encode($forums, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES));
    }

    /*
     * 查看用户组列表
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

            try {
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('core_Role');
                /*
                 * 查询字段别名
                 * rf1:rolecode
                 * rf2:rolename
                 * rf3:validstatus
                 */
                $fields = array();
                $fields['rf1'] = 'rolecode';
                $fields['rf2'] = 'rolename';
                $fields['rf3'] = 'validstatus';
                $roles = $modelManager->core_Role->queryRoleList($fields);
            } catch (Exception $ex) {
                $roles = array();
            }
            $responseBody = array('Rows' => $roles,
                'Total' => count($roles));
            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($responseBody));
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
            /*
             * 查询字段别名
             * rf1:rolecode
             * rf2:rolename
             * rf3:description
             * rf4:validstatus
             */
            $fields = array();
            $fields['rf1'] = 'rolecode';
            $fields['rf2'] = 'rolename';
            $fields['rf3'] = 'description';
            $fields['rf4'] = 'validstatus';
            $role = $modelManager->core_Role->queryRoleDetail($params['code'], $fields);

            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($role));
        }
        else {
            exit();
        }

    }

    /*
     * 新增用户组
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
            /*
             * 接收参数
             * av1:rolecode
             * av2:rolename
             * av3:description
             * av4:validstatus
             */
            $params = $this->_request->getParams();
            if (!array_key_exists('av1', $params) || !preg_match('/^[A-Z]{2}$/', $params['av1']) ||
                !array_key_exists('av2', $params) || !array_key_exists('av3', $params) ||
                !array_key_exists('av4', $params) || !preg_match('/^(0|1)$/', $params['av4'])
            )
                exit();
            try {
                $db = Puppy_Core_Db::getConnection();
                $prefix = Puppy_Core_Db::getPrefix();
                /*
                 * 检查用户组是否已经存在
                 */
                $select = $db->select()
                    ->from(array('a' => $prefix . 'core_role'), array('total' => 'count(*)'))
                    ->where('rolecode=?', $params['av1']);
                $result = $select->query()->fetch();
                if ($result->total > 0) {
                    $response = array('success' => false,
                        'info' => $this->view->translator('role_add_code_exists',$params['av1']));
                    throw new Exception();
                }
                $role = array('rolecode' => $params['av1'],
                    'rolename' => $params['av2'],
                    'description' => $params['av3'],
                    'validstatus' => $params['av4']);
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('core_Role');
                $affectedRows = $modelManager->core_Role->addRole($role);
                if ($affectedRows > 0)
                    $response = array('success' => true,
                        'info' => $this->view->translator('role_add_success'));
                else
                    throw new Exception();
            } catch (Exception $ex) {
                if(empty($response))
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

            /*
             * 接收参数
             * ev1:rolecode
             * ev2:rolename
             * ev3:description
             * ev4:validstatus
             */
            $response = array();
            $params = $this->_request->getParams();
            if (!array_key_exists('ev1', $params) || !preg_match('/^[A-Z]{2}$/', $params['ev1']) ||
                !array_key_exists('ev2', $params) || !array_key_exists('ev3', $params) ||
                !array_key_exists('ev4', $params) || !preg_match('/^(0|1)$/', $params['ev4'])
            )
                exit();
            /*
             * 检查用户组是否存在
             */
            try {
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('core_Role');
                $fields = array('rolename',
                    'validstatus');
                $originRole = $modelManager->core_Role->queryRoleDetail($params['ev1'], $fields);
                if (null == $originRole) {
                    $response = array('success' => false,
                        'info' => $this->view->translator('role_not_exists'));
                    throw new Exception();
                }
                /*
                 * 若设置用户组无效，该用户组下还有有效用户则终止设置
                 */
                $userCount = $modelManager->core_Role->countRoleValidUser($params['ev1']);
                if ($originRole->validstatus == '1' && $params['ev4'] == '0' && $userCount > 0) {
                    $response = array('success' => false,
                        'info' => $this->view->translator('role_users_not_empty',$userCount));
                    throw new Exception();
                }
                $set = array('rolename' => $params['ev2'],
                    'description' => $params['ev3'],
                    'validstatus' => $params['ev4']);
                $where['rolecode=?'] = $params['ev1'];
                $modelManager->core_Role->updateRole($set, $where);
                $response = array('success' => true,
                    'info' => $this->view->translator('role_edit_success'));
            } catch (Exception $ex) {
                if (empty($response))
                    $response = array('success' => false,
                        'info' => $this->view->translator('role_edit_failure'));
            }

            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($response));
        }
    }


}
