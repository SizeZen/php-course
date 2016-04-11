<?php
use Slim\Views\PhpRenderer;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \models;
use \auth;

require '../vendor/autoload.php';
require './models/User.php';
require './models/Salary.php';
require './helpers/auth.php';

$app = new Slim\App;

$container = $app->getContainer();
$container['view'] = $container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('views/');
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));
    return $view;
};

$app->get('/', function (Request $request, Response $response) {
    if(!auth\is_auth()) {
        return $response->withHeader('Location', '/login');
    } else {
        if($_SESSION['usertype'] == 1) { $url = '/employee'; }
        if($_SESSION['usertype'] == 2) { $url = '/leader'; }
        if($_SESSION['usertype'] == 3) { $url = '/admin'; }
        return $response->withHeader('Location', $url);
    }
});

$app->get('/login', function (Request $request, Response $response) {
    if(auth\is_auth()) {
        return $response->withHeader('Location', '/');
    }
    return $this->view->render($response, "login.html");
});

$app->get('/logout', function (Request $request, Response $response) {
    auth\logout_session();
    return $response->withHeader('Location', '/');
});

$app->post('/login', function (Request $request, Response $response) {
    $login = $request->getParsedBody()['inputLogin'];
    $pass = $request->getParsedBody()['inputPassword'];

    $User = new models\User\User();

    if($User->isExistLogin($login) && !$User->isFired($login)) {
        $user = $User->getUsersByLogin($login);
        if($user['pass'] == $pass) {
            auth\login_session($user['id'], $user['userTypeId']);
            return $response->withHeader('Location', '/');
        }
    }

    return $this->view->render($response, "login.html", ["error" => "something went wrong"]);
});

$app->get('/employee', function (Request $request, Response $response) {
    $User = new models\User\User();
    $Salary = new models\Salary\Salary();

    if(!auth\check_user(1)) {
        return $response->withHeader('Location', '/');
    }

    $userid = $_SESSION['userid'];

    $salaryModel = $Salary->getSalaryByEmpoyeeId($userid);
    $userName = $User->getUserNameById($userid);

    $salaryData = [];
    foreach ($salaryModel as $value) {
        $tmp["salary"] = $value["salary"];
        $tmp["date"] = $value["date"];
        $tmp["leaderName"] = $User->getUserNameById($value["leaderId"]);
        array_push($salaryData, $tmp);
        
        $data["salaryData"] = $salaryData;
        $data["userName"] = $userName;
        $data["leaders"] = $User->getLeadersByEmployeeId($userid);
    }
    return $this->view->render($response, "employee.html", $data);
});

$app->get('/leader[/{employee}]', function (Request $request, Response $response) {
    $User = new models\User\User();
    $Salary = new models\Salary\Salary();

    if(!auth\check_user(2)) {
        return $response->withHeader('Location', '/');
    }

    $leaderId = $_SESSION['userid'];
    
    $resultData = [];

    $employeeLogin = $request->getAttribute('employee');

    if(!empty($employeeLogin)) {
        $employee = $User->getUsersByLogin($employeeLogin);
    
        if(empty($employee['id']) || !$User->isBelongsEmployee($employee['id'])) {
           return $response->withHeader('Location', '/leader');
        }

        $salaryModel = $Salary->getSalaryByEmpoyeeIdLeaderId($employee['id'], $leaderId);
        
        $salaryData = [];
        foreach ($salaryModel as $value) {
            $tmp["salary"] = $value["salary"];
            $tmp["date"] = $value["date"];
            array_push($salaryData, $tmp);
        }

        $resultData['employee'] = $employee;
        $resultData['salaryData'] = $salaryData;

    }
    $resultData['allEmployees'] = $User->getUsersIndependentLeaderId($leaderId);
    
    $resultData['employees'] = $User->getUsersByLeaderId($leaderId);

    $resultData['leaderName'] = $User->getUserNameById($leaderId);
    
    return $this->view->render($response, "leader.html", $resultData);
});

$app->get('/admin[/{userType}]', function (Request $request, Response $response) {
    $User = new models\User\User();
    $Salary = new models\Salary\Salary();

    if(!auth\check_user(3)) {
        return $response->withHeader('Location', '/');
    }

    $adminId = $_SESSION['userid'];

    $resultData["userName"] = $User->getUserNameById($adminId);
    
    $userType = $request->getAttribute('userType');

    if($userType == "employee" || $userType == "leader") {

        if($userType == "employee") { 
            $resultData["userType"] = "Працівник";
            $userTypeId = 1;
        }
        elseif($userType == "leader") {
            $resultData["userType"] = "Керівник";
            $userTypeId = 2;
        }
        $resultData["usersData"] = $User->getUsersByType($userTypeId);

        return $this->view->render($response, "admin.html", $resultData);
    } else {
        $resultData["usersData"] = $User->getAllEmployeesLeaders();
    }

    return $this->view->render($response, "admin.html", $resultData);

});

$app->post('/api/addUser', function (Request $request, Response $response) {
    $User = new models\User\User();

    if(!auth\check_user(3)) {
        return $response->withHeader('Location', '/');
    }

    $json = $request->getBody();
    $data = json_decode($json, true);

    if($User->isExistLogin($data['userlogin']) || empty($data['username']) || empty($data['userlogin']))  {
        return $response->withStatus(500)
                        ->withHeader('Content-Type', 'text/html')
                        ->write('Something went wrong!');
    }

    $User->addUser($data);

    return $response->withStatus(201)
                    ->withHeader('Content-Type', 'text/html')
                    ->write('Success');
});

$app->post('/api/fireUser', function (Request $request, Response $response) {
    $User = new models\User\User();

    if(!auth\check_user(3)) {
        return $response->withHeader('Location', '/');
    }

    $json = $request->getBody();
    $data = json_decode($json, true);

    $User->fireUser($data['id']);
    if($data['id'] == 1) {
        $User->deleteEmployee($data['id']);
    } elseif ($data['id'] == 2) {
        $User->deleteLeader($data['id']);
    }

    return $response->withStatus(201)
                    ->withHeader('Content-Type', 'text/html')
                    ->write('Success');
});

$app->post('/api/acceptUser', function (Request $request, Response $response) {
    $User = new models\User\User();

    if(!auth\check_user(3)) {
        return $response->withHeader('Location', '/');
    }

    $json = $request->getBody();
    $data = json_decode($json, true);

    $User->acceptUser($data['id']);

    return $response->withStatus(201)
                    ->withHeader('Content-Type', 'text/html')
                    ->write('Success');
});

$app->post('/api/updateUser', function (Request $request, Response $response) {
    $User = new models\User\User();

    if(!auth\check_user(3)) {
        return $response->withHeader('Location', '/');
    }

    $json = $request->getBody();
    $data = json_decode($json, true);

    if(empty($data['username']) || empty($data['userid'])) {
        return $response->withStatus(500)
                ->withHeader('Content-Type', 'text/html')
                ->write('Something went wrong!');
    }

    $User->updateUser($data);

    return $response->withStatus(201)
                    ->withHeader('Content-Type', 'text/html')
                    ->write('Success');
});

$app->post('/api/addSalary', function (Request $request, Response $response) {
    $Salary = new models\Salary\Salary();

    if(!auth\check_user(2)) {
        return $response->withHeader('Location', '/');
    }

    $json = $request->getBody();
    $data = json_decode($json, true);

    if(!is_numeric($data['employeesalary'])) {
        return $response->withStatus(500)
                ->withHeader('Content-Type', 'text/html')
                ->write('Something went wrong!');
    }

    $data['leaderid'] = $_SESSION['userid'];

    $Salary->addSalary($data);

    return $response->withStatus(201)
                ->withHeader('Content-Type', 'text/html')
                ->write('Success');
});

$app->post('/api/addEmployeeLeader', function (Request $request, Response $response) {
    $User = new models\User\User();

    if(!auth\check_user(2)) {
        return $response->withHeader('Location', '/');
    }

    $json = $request->getBody();
    $data = json_decode($json, true);

    $data['leaderid'] = $_SESSION['userid'];

    $User->addEmployeeLeader($data);

    return $response->withStatus(201)
                ->withHeader('Content-Type', 'text/html')
                ->write('Success');
});

$app->post('/api/resetPass', function (Request $request, Response $response) {
    $User = new models\User\User();

    $json = $request->getBody();
    $data = json_decode($json, true);

    if(!auth\is_auth()) {
        return $response->withHeader('Location', '/');
    }

    $userId = $_SESSION['userid'];

    $user = $User->getUsersById($userId);
    if($user['pass'] == $data['oldpass']) {
        $User->updateUserPass($data['newpass'], $userId);

        return $response->withStatus(201)
                        ->withHeader('Content-Type', 'text/html')
                        ->write('Success');    
    }
    return $response->withStatus(500)
                    ->withHeader('Content-Type', 'text/html')
                    ->write('Something went wrong!');
});
$app->post('/api/removeEmployeeLeader', function (Request $request, Response $response) {
    $User = new models\User\User();

    $json = $request->getBody();
    $data = json_decode($json, true);

    if(!auth\check_user(2)) {
        return $response->withHeader('Location', '/');
    }

    $leaderId = $_SESSION['userid'];

    $User->deleteEmployeeLeader($data['employeeId'], $leaderId);

    return $response->withStatus(201)
                    ->withHeader('Content-Type', 'text/html')
                    ->write('Success');    
});
$app->run();
