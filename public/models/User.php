<?php
namespace models\User;

class User
{
    public $pdo;

    function __construct () {
        $this->pdo = require './helpers/connection.php';
    }

    public function getAllUsers() {
        $selectStatement = $this->pdo->select()
                                     ->from('Users');
        $stmt = $selectStatement->execute();
        return $stmt->fetchAll();
    }
    public function getUsersById($userId) {
        $selectStatement = $this->pdo->select()
                                     ->from('Users')
                                     ->where('id', '=', $userId);
        $stmt = $selectStatement->execute();
        return $stmt->fetch();
    }
    public function getUsersNameById($userId) {
        $selectStatement = $this->pdo->select(['name'])
                                     ->from('Users')
                                     ->where('id', '=', $userId);
        $stmt = $selectStatement->execute();
        $res = $stmt->fetch();
        return $res['name'];
    }
}
