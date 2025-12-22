<?php

require_once __DIR__ . '/../dao/BaseDao.php';

class BaseService {

    protected $dao;
    
    public function __construct($dao) {
        $this->dao = $dao;
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        return $this->dao->getById($id);
    }
    
    public function add($data) {
        return $this->dao->add($data);
    }
    
    public function update($data, $id) {
        return $this->dao->update($id, $data);
    }
    
    public function delete($id) {
        return $this->dao->delete($id);
    }

    public function getConnection() {
        return $this->dao->getConnection();
    }
}
?>

