<?php declare(strict_types=1); 
require_once __DIR__ . '/../models/Admin.php';

class Admin_C{
    private $adminModel;

    public function __construct() {
        $this->adminModel = new Admin();
    }
    public function getTotalUser(){
        $adminModel = new Admin();
        return $adminModel->getUserCount();
    }

    public function getUser(){
        $adminModel = new Admin();
        return $adminModel->getUsers();
    }

}
?>

