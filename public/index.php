<?php
use Slim\Views\PhpRenderer;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \models;

require '../vendor/autoload.php';
require './models/User.php';
require './models/Salary.php';

$app = new Slim\App;
$container = $app->getContainer();
$container['renderer'] = new PhpRenderer("./views/");

$app->get('/employee', function (Request $request, Response $response) {
    $User = new models\User\User();
    $Salary = new models\Salary\Salary();

    $salaryModel = $Salary->getSalaryByEmpoyeeId(1);
    $userName = $User->getUsersNameById(1);

    $salaryData = [];
    foreach ($salaryModel as $value) {
        $tmp["salary"] = $value["salary"];
        $tmp["date"] = $value["date"];
        $tmp["leaderName"] = $User->getUsersNameById($value["leaderId"]);
        array_push($salaryData, $tmp);
    }
    return $this->renderer->render($response, "employee.php", [
                                                                "salaryData" => $salaryData,
                                                                "userName"   => $userName ]);
});
$app->run();


