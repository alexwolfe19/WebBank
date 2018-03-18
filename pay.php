<?php
  session_start();
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 1);
  error_reporting(-1);

  $db = new SQLite3('database/main.db');

  function redirrect($url = "index.php")
  {
    echo "<body>If you're browser doesn't redirrect <a href='$url'>click me</a>";
    echo "<script>setTimeout(function(){ window.location = '$url' }, 1000);</script>";
  }

  $buyer = "";
  if (isset($_SESSION['accountID']))
  {
    $buyer = $_SESSION['accountID'];
  } else {
    redirrect("index.php?return=pay.php");
  }

  $buying = $_GET["obj"];
  $dat;

  // now get the info
  $sql = "SELECT * FROM paycodes";
  $result = $db->query($sql);
  while ($row = $result->fetchArray(SQLITE3_ASSOC))
  {
    if ($row["ref"] == $buying)
    {
      $dat = $row;
    }
  }
  //paycodes (name TEXT, discription TEXT, creator TEXT, ammount TEXT, limited TEXT, uses TEXT, ref TEXT)"
?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>BankU</title>
    <script   src="https://code.jquery.com/jquery-3.2.1.js"   integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="   crossorigin="anonymous"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

  </head>
  <body>
    <div class="container">

      <p>Title: <?php echo $dat["name"]; ?></p>
      <p>Ammount: <?php echo $dat["ammount"]; ?></p>
      <hr>
      <p>Discription:<br> <?php echo $dat["discription"]; ?></p><br><br><hr>

      <form action="backend.php" method="post" class="" id="mainForm">
        <input name="redirrect" value="home.php?status=bought" type="hidden">
        <input name="intent" value="pay" id="dt" type="hidden">
        <input name="sender" value="<?php echo $_SESSION['accountID']; ?>" type="hidden">
        <input name="card" value="<?php echo $dat["creator"]; ?>" type="hidden">
        <input name="ammount" value="<?php echo $dat["ammount"]; ?>" type="hidden">


        <div class="form-group"><label for="psk">Password:</label><input name="pwd" id="pwd" type="password"></div>

        <div class="form-group">
          <input type="submit" value="Buy" class="btn btn-outline-success btn-block">
        </div>
      </form>
    </div>
</html>
