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
    public function getSalaryByEmpoyeeIdLeaderId($EmpoyeeId, $LeaderId) {
        $selectStatement = $this->pdo->select()
                                     ->from('EmployeeSaraly')
                                     ->where('employeeId', '=', $EmpoyeeId)
                                     ->where('leaderId', '=', $LeaderId);
        $stmt = $selectStatement->execute();
        return $stmt->fetchAll();
    }
    public function addSalary($data) {
        $insertStatement = $this->pdo->insert(array('salary', 'employeeId', 'leaderId'))
                                     ->into('EmployeeSaraly')
                                     ->values(array(
                                                    $data['employeesalary'],
                                                    $data['employeeid'],
                                                    $data['leaderid'] ));
        $insertStatement->execute(false);
    }
}
