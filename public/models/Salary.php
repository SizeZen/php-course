<?php 
namespace models\Salary;

class Salary
{
    public $pdo;

    function __construct () {
        $this->pdo = require './helpers/connection.php';
    }

    public function getSalaryByEmpoyeeId($EmpoyeeId) {
        $selectStatement = $this->pdo->select()
                                     ->from('EmployeeSaraly')
                                     ->where('employeeId', '=', $EmpoyeeId);
        $stmt = $selectStatement->execute();
        return $stmt->fetchAll();
    }
}
