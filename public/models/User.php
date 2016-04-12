<?php
namespace models\User;

class User
{
    public $pdo;

    function __construct () {
        $this->pdo = require './helpers/connection.php';
    }

    public function getUsersById($userId) {
        $selectStatement = $this->pdo->select()
                                     ->from('Users')
                                     ->where('id', '=', $userId);
        $stmt = $selectStatement->execute();
        return $stmt->fetch();
    }
        public function getUsersByLogin($login) {
        $selectStatement = $this->pdo->select()
                                     ->from('Users')
                                     ->where('login', '=', $login);
        $stmt = $selectStatement->execute();
        return $stmt->fetch();
    }
    public function getUserNameById($userId) {
        $selectStatement = $this->pdo->select(['name'])
                                     ->from('Users')
                                     ->where('id', '=', $userId);
        $stmt = $selectStatement->execute();
        $res = $stmt->fetch();
        return $res['name'];
    }
    public function getUserIdByName($userName) {
        $selectStatement = $this->pdo->select(['id'])
                                     ->from('Users')
                                     ->where('name', '=', $userName);
        $stmt = $selectStatement->execute();
        $res = $stmt->fetch();
        return $res['id'];
    }
    public function getUsersIndependentLeaderId($leaderId) {
        $selectStatement = $this->pdo->select(['Users.id', 'Users.name', 'Users.login'])
                                     ->from('Users')
                                     ->leftJoin('LeadersEmployee', 'LeadersEmployee.employeeId', '=', 'Users.id')
                                     ->where('Users.userTypeId', '=', '1')
                                     ->whereNull('LeadersEmployee.leaderId');

        $stmt = $selectStatement->execute();
        return $stmt->fetchAll();
    }
    public function getUsersByLeaderId($leaderId) {
        $selectStatement = $this->pdo->select(['Users.id', 'Users.name', 'Users.login'])
                                     ->from('Users')
                                     ->leftJoin('LeadersEmployee', 'LeadersEmployee.employeeId', '=', 'Users.id')
                                     ->where('LeadersEmployee.leaderId', '=', $leaderId);

        $stmt = $selectStatement->execute();
        return $stmt->fetchAll();
    }
    public function getLeadersByEmployeeId($employeeId) {
        $selectStatement = $this->pdo->select(['Users.id', 'Users.name', 'Users.login'])
                                     ->from('Users')
                                     ->leftJoin('LeadersEmployee', 'LeadersEmployee.leaderId', '=', 'Users.id')
                                     ->where('LeadersEmployee.employeeId', '=', $employeeId);

        $stmt = $selectStatement->execute();
        return $stmt->fetchAll();
    }
    public function getUsersByType($typeId) {
        $selectStatement = $this->pdo->select()
                                     ->from('Users')
                                     ->where('userTypeId', '=', $typeId);

        $stmt = $selectStatement->execute();
        return $stmt->fetchAll();
    }
    public function getAllEmployeesLeaders() {
        $selectStatement = $this->pdo->select()
                                     ->from('Users')
                                     ->where('userTypeId', '=', 1)
                                     ->orWhere('userTypeId', '=', 2);

        $stmt = $selectStatement->execute();
        return $stmt->fetchAll();  
    }
    public function addUser($data) {
        $insertStatement = $this->pdo->insert(array('name', 'login', 'userTypeId', 'pass'))
                           ->into('Users')
                           ->values(array($data['username'], $data['userlogin'], $data['usertype'], '12345'));
        $insertStatement->execute(false);
    }
    public function addEmployeeLeader($data) {
        $insertStatement = $this->pdo->insert(array('leaderId', 'employeeId'))
                                     ->into('LeadersEmployee')
                                     ->values(array($data['leaderid'], $data['employeeId']));
        $insertStatement->execute(false);
    }
    public function isExistLogin($login) {
        $selectStatement = $this->pdo->select()
                                     ->from('Users')
                                     ->Where('login', '=', $login);

        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();

        if (count($data) > 0) {
            return true;
        }

        return false; 
    }
    public function isFired($login) {
        $selectStatement = $this->pdo->select(['isFired'])
                                     ->from('Users')
                                     ->Where('login', '=', $login);

        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();

        if ($data['isFired'] == 1) {
            return true;
        }

        return false; 
    }
    public function fireUser($id) {
        $insertStatement = $this->pdo->update()
                           ->set(array('isFired' => 1))
                           ->table('Users')
                           ->where('id', '=', $id);
        $insertStatement->execute(false);
    }
    public function acceptUser($id) {
        $insertStatement = $this->pdo->update()
                           ->set(array('isFired' => 0))
                           ->table('Users')
                           ->where('id', '=', $id);
        $insertStatement->execute(false);
    }
    public function deleteLeader($employeeId) {
        $insertStatement = $this->pdo->delete()
                           ->from('LeadersEmployee')
                           ->where('employeeId', '=', $employeeId);
        $insertStatement->execute(false);
    }
    public function deleteEmployee($employeeId) {
        $insertStatement = $this->pdo->delete()
                           ->from('LeadersEmployee')
                           ->where('employeeId', '=', $employeeId);
        $insertStatement->execute(false);
    }
    public function deleteEmployeeLeader($employeeId, $leaderId) {
        $insertStatement = $this->pdo->delete()
                           ->from('LeadersEmployee')
                           ->where('employeeId', '=', $employeeId)
                           ->where('leaderId', '=', $leaderId);
        $insertStatement->execute(false);
    }
    public function updateUser($data) {
        $insertStatement = $this->pdo->update(array('name' => $data['username']))
                           ->table('Users')
                           ->where('id', '=', $data['userid']);
        $insertStatement->execute(false);
    }
    public function updateUserPass($newPass, $id) {
        $updateStatement = $this->pdo->update(array('pass' => $newPass))
                                     ->table('Users')
                                     ->where('id', '=', $id);
        $updateStatement->execute(false);                            
    }
    public function isBelongsEmployee($employeeId) {
        $selectStatement = $this->pdo->select()
                                     ->from('LeadersEmployee')
                                     ->Where('employeeId', '=', $employeeId);

        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();

        if (count($data) > 0) {
            return true;
        }

        return false; 
    }
}
