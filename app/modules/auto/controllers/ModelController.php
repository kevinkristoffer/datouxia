<?php

/**
 * Created by PhpStorm.
 * User: hujianhong05
 * Date: 2015/10/20
 * Time: 15:25
 */
class Auto_ModelController extends Zend_Controller_Action
{

    public function indexAction()
    {

    }

    /*
     * 查询车型列表
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
            if (!array_key_exists('pageIndex', $params) || !array_key_exists('limit', $params) ||
                !preg_match('/^(\d+)$/', $params['pageIndex']) || !preg_match('/^(\d+)$/', $params['limit'])
            )
                exit();

            //搜索参数
            $where = array();

            $response = array();
            try {
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('auto_Model');
                $fields = array('vehicleid',
                    'vehiclename',
                    'familyname',
                    'remark',
                    'validflag');
                $models = $modelManager->auto_Model->queryModelList($where, $fields, $params['limit'],
                    $params['pageIndex'] * $params['limit']);
                $modelCount = $modelManager->auto_Model->countModel($where);
            } catch (Exception $ex) {
                //echo $ex->getMessage();
                $models = array();
                $modelCount = 0;
            }
            $responseBody = array('rows' => $models,
                'results' => $modelCount);
            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($responseBody));
        }
    }
}