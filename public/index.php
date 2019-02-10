<?php

function LogIntoJSConsole(string $data): void  
{
  echo '<script>console.log("'.$data.'");</script>';
}

function GetSummOfOrders(array $array): array 
{
  $response = file_get_contents('https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5');
  $json = json_decode($response, true);

  $result = 0;
  foreach ($array as $value) {
    $result += (float)$value['summ'];
  }
  return ['UAH' => number_format($result, 2, ',', ' '), 'USD' => number_format($result / $json[0]['buy'], 2, ',', ' ')];
}

function PrepareRequest($request): string 
{
  $result = '';
  $isFirst = true;

  if ($request['id'] && strlen($request['id']) > 0) 
  {
    $result .= $isFirst ? ' WHERE id = '.$request['id'] : ' AND id = '.$request['id'];
    $isFirst = false;
  }

  if ($request['city'] && strlen($request['city']) > 0) 
  {
    $result .= $isFirst ? ' WHERE city LIKE "%'.$request['city'].'%"' : ' AND city LIKE "%'.$request['city'].'%"';
    $isFirst = false;
  }

  if ($request['summ'] && strlen($request['summ']) > 0) 
  {
    $result .= $isFirst ? ' WHERE summ <= '.$request['summ'] : ' AND summ <= '.$request['summ'];
    $isFirst = false;
  }

  return $result;
}

$data = [];
$mysqli = new mysqli("localhost", "root", "19941005valikp", "test_task");
if ($mysqli->connect_errno) 
{
  LogIntoJSConsole("Error with connection to mysql: (".$mysqli->connect_error.")");
}
else 
{
  if (!empty($_POST))
  {
    if (!($stmt = $mysqli->prepare("SELECT * FROM orders ".PrepareRequest($_POST)." GROUP BY created_at")))
    {
      LogIntoJSConsole("Preparing failed: (".$mysqli->error.")");
    }

    if (!$stmt->execute())
    {
      LogIntoJSConsole("Request failed: (".$stmt->error.")");
    }

    if (!$res = $stmt->get_result())
    {
      LogIntoJSConsole("Result failed: (".$stmt->error.")");
    }

    while ($buffer = $res->fetch_assoc())
    {
      $data[] = $buffer;
    }

    $res->free();
  }
  else
  {
    if (!($stmt = $mysqli->prepare("SELECT * FROM orders GROUP BY created_at")))
    {
      LogIntoJSConsole("Preparing failed: (".$mysqli->error.")");
    }

    if (!$stmt->execute())
    {
      LogIntoJSConsole("Request failed: (".$stmt->error.")");
    }

    if (!$res = $stmt->get_result())
    {
      LogIntoJSConsole("Result failed: (".$stmt->error.")");
    }

    while ($buffer = $res->fetch_assoc())
    {
      $data[] = $buffer;
    }

    $res->free();
  }
  
  $mysqli->close();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <title>Orders | Table</title>
</head>
<body>
<!-- верхняя навигационная панель -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="/public/create.php">Создать заказ <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="/public">Отчеты о заказах</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Фильтрация -->

  <div class="app">

    <form action="/public/index.php" method="POST" class="filter">
      <input type="text" placeholder="# Заказа" name="id" value="<?= $_POST['id'] ?? '' ?>">
      <input type="text" placeholder="Город" name="city" value="<?= $_POST['city'] ?? '' ?>">
      <select name="summ">
        <option value="100" <?= $_POST['summ'] === '100' ? 'selected' : ''?>>До 100 грн</option>
        <option value="500" <?= $_POST['summ'] === '500' ? 'selected' : ''?>>До 500 грн</option>
        <option value="1000" <?= $_POST['summ'] === '1000' ? 'selected' : ''?>>До 1000 грн</option>
        <option value="5000" <?= $_POST['summ'] === '5000' ? 'selected' : ''?>>До 5000 грн</option>
        <option value="10000" <?= $_POST['summ'] === '10000' ? 'selected' : ''?>>До 10000 грн</option>
      </select>
      <button type="submit" class="btn btn-primary">Поиск</button>
    </form>

    <?php $foundedSumm = GetSummOfOrders($data); ?>
    <h1>Найдено: <?= count($data) ?> отчёта</h1>
    <p>На сумму <?= $foundedSumm['UAH'] ?> UAH или $<?= $foundedSumm['USD'] ?></p>

    <table class="table table-striped table-hover table-sm orders">
      <thead class="thead-light">
        <tr>
          <th scope="col">#</th>
          <th>Имя: </th>
          <th>Фамилия:</th>
          <th>Номер телефона:</th>
          <th>Email:</th>
          <th>Город:</th>
          <th>Сумма:</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $row) : ?>
          <tr>
            <?php foreach ($row as $key => $value) : ?>
              <?php if ($key !== 'created_at') : ?>
                <td><?= $value ?? "" ?></td>
              <?php endif; ?>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>