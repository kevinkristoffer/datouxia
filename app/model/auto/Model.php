<?php

/**
 * Created by PhpStorm.
 * User: hujianhong05
 * Date: 2015/10/20
 * Time: 15:59
 */
class Puppy_Model_Auto_Model extends Puppy_Core_Model
{
    /**
     * @param null $where
     * @param array $fields
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Zend_Db_Select_Exception
     */
    public function queryModelList($where = null, $fields = array(), $limit = 10, $offset = 0)
    {
        $select = $this->_db->select()->from($this->_prefix . 'vehicle_model', $fields);
        if ($where != null && is_array($where)) {
            foreach ($where as $key => $value) {
                $select = $select->where($key, $value);
            }
        }
        $select = $select->limit($limit, $offset);
        $result = $select->query()->fetchAll();
        return $result;
    }

    /**
     * @param null $where
     * @return int
     */
    public function countModel($where = null)
    {
        $select = $this->_db->select()->from($this->_prefix . 'vehicle_model', array('total' => 'count(*)'));
        if ($where != null && is_array($where)) {
            foreach ($where as $key => $value) {
                $select = $select->where($key, $value);
            }
        }
        $result = $select->query()->fetch();
        return $result->total;
    }

    /**
     * @param $model
     * @return int
     * @throws Zend_Db_Adapter_Exception
     */
    public function addModel($model)
    {
        $affectedRows = $this->_db->insert($this->_prefix . 'vehicle_model', $model);
        return $affectedRows;
    }

    /**
     * @param $set
     * @param $where
     * @return int
     * @throws Zend_Db_Adapter_Exception
     */
    public function updateModel($set, $where)
    {
        $affectedRows = $this->_db->update($this->_prefix . 'vehicle_model', $set, $where);
        return $affectedRows;
    }

}