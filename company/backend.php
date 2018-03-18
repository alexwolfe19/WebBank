<?php
    session_start();

    $isDebug = true;
    if (isset($_GET["debug"]))
    {
      if ($_GET["debug"]=="on")
      {
        $isDebug = true;
      }
    }

    define('useDebug',$isDebug);

    function consoleLog($msg = "", $fatal = false)
    {
      if (useDebug == true)
      {
        $date = date('Y-m-d H:i:s');
        echo "<code>[" . $date . "] " . $msg . "</code><br>";

        if ($fatal)
        {
          die();
        }
      }
    }

    function redirrect($url = "index.php")
    {
      echo "<body>If you're browser doesn't redirrect <a href='$url'>click me</a>";
      echo "<script>setTimeout(function(){ window.location = '$url' }, 1000);</script>";
    }

    $intent = $_POST["intent"];
    if ($intent == "login")
    {
      $email = $_POST["email"];
      $f = explode('@', $email);

      consoleLog($f[0]);
      consoleLog($f[1]);

      $domain = $f[1];
      $user = $f[0];

      $db = new SQLite3('../database/companyAssets/' . $domain . '/database.db') or redirrect("newCompany.php?msg=na");


    } elseif ($intent == "new")
    {
      $email = $_POST["email"];
      $f = explode('@', $email);

      consoleLog($f[0]);
      consoleLog($f[1]);

      $domain = $f[1];
      $user = $f[0];

      $db = new SQLite3('../database/companyAssets/' . $domain . '/database.db') or redirrect("newCompany.php?msg=failure");
      $sql = "CREATE TABLE IF NOT EXISTS users (card TEXT, firstname TEXT, lastname TEXT, id TEXT, password TEXT, ref TEXT, funds NUMBER, UNIQUE (card))"; $result = $db->query($sql);
    }
?>
