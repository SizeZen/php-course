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
    public function getUsersByLeaderId($leaderId) {
        $selectStatement = $this->pdo->select(['Users.id', 'Users.name', 'Users.login'])
                                     ->from('Users')
                                     ->leftJoin('LeadersEmployee', 'LeadersEmployee.employeeId', '=', 'Users.id')
                                     ->where('LeadersEmployee.leaderId', '=', $leaderId);

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
    public function deleteLeader($leaderId) {
        $insertStatement = $this->pdo->delete()
                           ->from('LeadersEmployee')
                           ->where('leaderId', '=', $leaderId);
        $insertStatement->execute(false);
    }
    public function updateUser($data) {
        $insertStatement = $this->pdo->update(array('name' => $data['username']))
                           ->table('Users')
                           ->where('id', '=', $data['userid']);
        $insertStatement->execute(false);
    }
}
