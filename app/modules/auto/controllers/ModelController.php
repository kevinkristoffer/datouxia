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
            if (!array_key_exists('page', $params) || !array_key_exists('pagesize', $params) ||
                !preg_match('/^(\d+)$/', $params['page']) || !preg_match('/^(\d+)$/', $params['pagesize'])
            )
                exit();
            /*
             * 接受参数
             * sv1:search keyword
             * sv2:vehiclemaker
             * sv3:oldprice
             * sv4:validstatus
             */
            $where = array();
            if (array_key_exists('sv1', $params) && $params['sv1'] != '') {
                $keywords = explode(' ', $params['sv1']);
                foreach ($keywords as $item) {
                    $where['concat(vehiclename,brandname,familyname,groupname,vehiclemaker,remark) regexp ?'] = trim($item);
                }
            }
            if (array_key_exists('sv2', $params) && $params['sv2'] != '') {
                $where['vehiclemaker=?'] = $params['sv2'];
            }
            if (array_key_exists('sv3f', $params) && $params['sv3f']=='true') {
                if (array_key_exists('sv3a', $params) && array_key_exists('sv3b', $params) &&
                    floatval($params['sv3a']) <= floatval($params['sv3b'])
                ) {
                    $where['oldprice*0.0001>=?'] = floatval($params['sv3a']);
                    $where['oldprice*0.0001<=?'] = floatval($params['sv3b']);
                }

            }
            if (array_key_exists('sv4', $params) && preg_match('/^(0|1)$/', $params['sv4']))
                $where['validstatus=?'] = $params['sv4'];

            $response = array();
            try {
                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('auto_Model');
                /*
                 * 查询字段别名
                 */
                $fields = array();
                $fields['mf1'] = 'vehicleid';
                $fields['mf2'] = 'vehiclename';
                $fields['mf3'] = 'familyname';
                $fields['mf4'] = 'remark';
                $fields['mf5'] = 'vehiclemaker';
                $fields['mf6'] = 'oldprice';
                $fields['mf7'] = 'validstatus';
                $models = $modelManager->auto_Model->queryModelList($where, $fields, $params['pagesize'],
                    ($params['page'] - 1) * $params['pagesize']);
                $modelCount = $modelManager->auto_Model->countModel($where);
            } catch (Exception $ex) {
                //echo $ex->getMessage();
                $models = array();
                $modelCount = 0;
            }
            $response = array('Rows' => $models,
                'Total' => $modelCount);
            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($response));
        }
    }

    /*
     * 查询车型详情
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
        }

        echo 'AAA';
    }

    /*
     * 查询厂商清单
     */
    public function queryVehicleMakerListAction()
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
            try {
                $keyword = null;
                if (array_key_exists('condition', $params)) {
                    $condition = json_decode($params['condition']);
                    if (!empty($condition)) {
                        $keyword = $condition[0]->value;
                    }
                }

                $db = Puppy_Core_Db::getConnection();
                $modelManager = Puppy_Core_Model_Manager::getInstance();
                $modelManager->setDbConnection($db);
                $modelManager->registerModel('auto_Model');
                $vehicleMakers = $modelManager->auto_Model->queryVehicleMaker($keyword, $params['pagesize'],
                    ($params['page'] - 1) * $params['pagesize']);
                $countVehicleMakers = $modelManager->auto_Model->countVehicleMaker($keyword);
            } catch (Exception $ex) {
                $vehicleMakers = array();
                $countVehicleMakers = 0;
            }
            $response = array('Rows' => $vehicleMakers,
                'Total' => $countVehicleMakers);
            $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
            $this->_response->setBody(json_encode($response));
        }
    }
}