<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="./static/style.css">
</head>
<body>
  <nav class="navbar navbar-inverse navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#"><span class="label label-primary">Сouse</span></a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container"> 
    <div class="panel panel-default">
      <div class="panel-heading">Привіт <?php echo $userName; ?> <span class="label label-info">Працівник</span></div>
      <div class="panel-body">
        <h3>Інформація про виплати</h3>
      </div>
      <table class="table table-condensed">
        <thead>
          <tr>
            <th>#</th>
            <th>Виплата</th>
            <th>Керівник</th>
            <th>Дата</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            for($i = 0; $i < count($salaryData); $i++) {
              $html = "<tr>
                    <td>" . ($i + 1) . "</td>
                    <td>" . $salaryData[$i]['salary'] . "</td>
                    <td>" . $salaryData[$i]['leaderName'] . "</td>
                    <td>" . $salaryData[$i]['date'] . "</td>
                  </tr>";
              echo $html;
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>