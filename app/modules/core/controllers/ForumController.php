<?php

class Core_ForumController extends Zend_Controller_Action
{

    public function listAction()
    {
        if ($this->_request->isPost()) {
            if (!isset ($_SERVER ['HTTP_X_REQUESTED_WITH']) ||
                strtolower($_SERVER ['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'
            ) {
                exit();
            }
        }

        $this->_helper->getHelper('viewRenderer')->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();

        try {
            $db = Puppy_Core_Db::getConnection();
            $modelManager = Puppy_Core_Model_Manager::getInstance();
            $modelManager->setDbConnection($db);
            $modelManager->registerModel('core_Forum');

            $forums = $modelManager->core_Forum->queryValidForum();

        } catch (Exception $ex) {
            $forums = array();
        }

        $this->_response->setHeader('content-type', 'application/json;charset=utf-8');
        $this->_response->setBody(json_encode($forums, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES));
    }
}
