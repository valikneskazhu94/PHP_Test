<?php

function LogIntoJSConsole(string $data): void  
{
  echo '<script>console.log("'.$data.'");</script>';
}

$request = $_POST ?? [];
if (!empty($request)) 
{
  $mysqli = new mysqli("localhost", "root", "19941005valikp", "test_task");

  if ($mysqli->connect_errno) 
  {
    LogIntoJSConsole("Error with connection to mysql: (".$mysqli->connect_error.")");
  }
  else 
  {
    if (!($stmt = $mysqli->prepare("INSERT INTO orders(first_name, last_name, phone_number, email, city, summ) VALUES(?, ?, ?, ?, ?, ?)")))
    {
      LogIntoJSConsole("Preparing failed: (".$mysqli->error.")");
    }

    if (!$stmt->bind_param("sssssd", 
                           $request['first_name'], 
                           $request['last_name'], 
                           $request['phone_number'], 
                           $request['email'], 
                           $request['city'], 
                           $request['summ'])) 
    {
      LogIntoJSConsole("Binding failed: (".$stmt->error.")");
    }

    if (!$stmt->execute())
    {
      LogIntoJSConsole("Request failed: (".$stmt->error.")");
    }
    else
    {
      header('Location: /public');
    }
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
  <title>Orders | Create order</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="/public/create.php">Создать заказ <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/public">Отчеты о заказах</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <form action="create.php"
          method="post">
        <div class="form-group">
          <label for="first_name">Имя:
            <input type="text"
            class="form-control"
                  name="first_name"
                  id="first_name"
                  value="<?= $request['first_name'] ?? "" ?>">
          </label>
        </div>
        <div class="form-group"><label for="last_name">Фамилия: <input type="text"
        class="form-control"
                  name="last_name"
                  id="last_name"
                  value="<?= $request['last_name'] ?? "" ?>"></label></div>
        <div class="form-group"><label for="phone_number">Номер телефона:<input type="tel"
        class="form-control"
                  name="phone_number"
                  id="phone_number"
                  pattern="[\+]\d{2}[\(]\d{3}[\)]\d{3}[\-]\d{4}"
                  value="<?= $request['phone_number'] ?? "" ?>"></label></div>
        <div class="form-group"><label for="email">Email <input type="text"
        class="form-control"
                  name="email"
                  id="email"
                  value="<?= $request['email'] ?? "" ?>"></label></div>
        <div class="form-group"><label for="city">Город: <input type="text"
        class="form-control"
                  name="city"
                  id="city"
                  value="<?= $request['city'] ?? "" ?>"></label></div>
        <div class="form-group"><label for="summ">Сумма: <input type="text"
        class="form-control" placeholder="00.00"
                  name="summ"
                  id="summ"
                  value="<?= $request['summ'] ?? "" ?>"></label></div>
        <button type="submit" class="btn btn-primary">Добавить.</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>
</html>