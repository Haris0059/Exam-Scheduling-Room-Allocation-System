<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/FacultyDao.php';

class FacultyService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new FacultyDao());
    }
}
