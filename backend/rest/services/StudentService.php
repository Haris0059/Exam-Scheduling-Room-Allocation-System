<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/StudentDao.php';

class StudentService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new StudentDao());
    }

    // @Override
    public function add($data)
    {
        $data['status'] = 'active';

        return parent::add($data);
    }
    


    
    // @Override
    public function delete($id)
    {
        $data = ['status' => 'inactive'];

        return parent::update($id, $data);
    }

    
    public function getStudentsPaginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $count = $this->dao->countStudentsPaginated($search)['count'];
        $data = $this->dao->getStudentsPaginated($offset, $limit, $search, $order_column, $order_direction);

        return [
            'total_records' => $count,
            'data' => $data
        ];
    }

    public function getStudentByEmail($email)
    {
        return $this->dao->getByEmail($email);
    }

    public function getStudentByDepartment($department_id)
    {
        return $this->dao->getByDepartment($department_id);
    }
}