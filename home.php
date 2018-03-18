<?php
  session_start();

  function redirrect($url = "index.php")
  {
    echo "<body>If you're browser doesn't redirrect <a href='$url'>click me</a>";
    echo "<script>setTimeout(function(){ window.location = '$url' }, 1000);</script>";
  }

  if (isset($_SESSION["home_prefrence"]))
  {
    $opt = $_SESSION["home_prefrence"];
    if ($opt=="new")
    {
      redirrect("homepage/home_new.php");
    } elseif ($opt=="old")
    {
      redirrect("homepage/home_old.php");
    }
  }

  if (isset($_GET["r"]))
  {
    if ($_GET["r"]=="set")
    {
      $choice = $_GET["opt"];
      if ($choice=="new")
      {
        $_SESSION["home_prefrence"] = "new";
        redirrect("home.php");
      } elseif ($choice=="old"){
        $_SESSION["home_prefrence"] = "old";
        redirrect("home.php");
      }
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>BankU</title>
    <script   src="https://code.jquery.com/jquery-3.2.1.js"   integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="   crossorigin="anonymous"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">

  	<style>

      body {
        background-position: center;
        background-repeat: no-repeat;
        background-size: fill;
        padding: 0px;
      }

      .wrong {
       border: 2px red solid;
      }
      .valid {
       border: 2px green solid;
      }

    </style>

  </head>
  <body>
    <div class="container">
      <h1 class="text-center">Choose Your Prefrence</h1>
      <button onclick="window.location='home.php?r=set&opt=new'" class="btn btn-success btn-block">New</button>
      <br>
      <button onclick="window.location='home.php?r=set&opt=old'" class="btn btn-info btn-block">Original</button>
      <p class="text-center" style="padding:5px;">Website developed by Ethan Manzi (C)2017</p>
    </div>
  </body>
</html>
