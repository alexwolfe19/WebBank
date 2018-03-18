<?php
  session_start();
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 1);
  error_reporting(-1);

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


  function generate_uuid()
  {
      return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
          mt_rand( 0, 0xffff ),
          mt_rand( 0, 0x0fff ) | 0x4000,
          mt_rand( 0, 0x3fff ) | 0x8000,
          mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
      );
  }

  //setup the DB
  //$db = new PDO('sqlite:database/main.db');
  $db = new SQLite3('database/main.db');
  consoleLog("Database online!");
  $sql = "CREATE TABLE IF NOT EXISTS users (card TEXT, firstname TEXT, lastname TEXT, id TEXT, password TEXT, ref TEXT, funds NUMBER, UNIQUE (card))"; $result = $db->query($sql);

  if ($result==false)
  {
    consoleLog("FATAL ERROR: SAME INFORMATION");
    redirrect("index.php?status=existing");
  }

  $sql = "CREATE TABLE IF NOT EXISTS sessions (linked TEXT, start TEXT, sessionA TEXT, sessionB TEXT)"; $result = $db->query($sql);
  $sql = "CREATE TABLE IF NOT EXISTS transactions (party1 TEXT, party2 TEXT, ammount TEXT, stamp TEXT)"; $result = $db->query($sql);
  $sql = "CREATE TABLE IF NOT EXISTS buisnesses (name TEXT, id TEXT)"; $result = $db->query($sql);
  $sql = "CREATE TABLE IF NOT EXISTS paycodes (name TEXT, discription TEXT, creator TEXT, ammount TEXT, limited TEXT, uses TEXT, ref TEXT)"; $result = $db->query($sql);
  $sql = "CREATE TABLE IF NOT EXISTS savings (account TEXT, ammount TEXT, intrest TEXT, minimumAge TEXT)"; $result = $db->query($sql);
  consoleLog("Tables online!");
  consoleLog("Backend Ready");


  function signup(&$db)
  {
    consoleLog("Signing up user");
    $after = $_POST["redirrect"];
    $card = strtoupper($_POST["card"]);
    $pass = md5($_POST["pwd"]);
    $id = generate_uuid();
    $ref = md5($card . $pass);
    $sql = "INSERT INTO users (card,firstname,lastname,id,password,ref,funds) VALUES ('$card','','','$id','$pass','$ref',100)"; $db->query($sql);
    consoleLog("Record Compleated!");
    consoleLog("Logging in user...");
    login($db);
  }
  function login(&$db)
  {
    $after = $_POST["redirrect"];
    $card = strtoupper($_POST["card"]);
    $pass = md5($_POST["pwd"]);

    $t = time();
    $ref = md5($card . $pass);

    $a = md5(generate_uuid().generate_uuid().rand(10000,99999));
    $b = md5(generate_uuid().generate_uuid().(rand(10000,99999)*time()));
    $c = md5(rand(10000,99999)*time()^rand(10000,99999));

    $finalB = md5($a.$b.$c);
    $finalA = md5($finalB.$a);

    $sql = "INSERT INTO sessions (linked, start) VALUES ('$ref',''$t', '$finalA','$finalB')";
    setcookie("auth_a", $a,time()+((1000*60)*5));
    setcookie("auth_b", $b,time()+((1000*60)*5));
    setcookie("auth_c", $c,time()+((1000*60)*5));

    // store the users information for now
    $sql = "SELECT * FROM users";
    $result = $db->query($sql);
    $found = false;
    while ($row = $result->fetchArray(SQLITE3_ASSOC))
    {
        if ($row["ref"]==$ref)
        {
          $_SESSION["balance"] = $row["funds"];
          $_SESSION["accountID"] = $ref;
          $_SESSION["validLogin"] = time();
          $_SESSION["card"] = $row["card"];
          $found = true;
        }
    }

    if ($found)
    {
      consoleLog("SESSION TEST: $" . $_SESSION["balance"]);
      consoleLog("GOOD");
      redirrect($after);
    } else {
      consoleLog("OMFG IT IS WRONG");
      redirrect("index.php?login=wrong");
    }
  }
  function verifyUser(&$db)
  {
    $a = $_COOKIE["auth_a"];
    $b = $_COOKIE["auth_b"];
    $c = $_COOKIE["auth_c"];

    $key1 = md5($a.$b.$c);
    $key2 = md5($key1.$a);

    $valid = false;

    $sql = "SELECT * FROM sessions";
    $result = $db->query($sql);
    while ($row = $result->fetchArray(SQLITE3_ASSOC))
    {
      if ($row["sessionA"]==$key1)
      {
        consoleLog("Key 1 matched!");
        if ($row["sessionB"]==$key2)
        {
          consleLog("Key 2 matched");
          $valid = true;
        }
      }
    }

    if (valid)
    {
      echo "valid";
      return true;
    } else {
      echo "invalid";
      return false;
    }

  }

  function pay(&$db) {
    $from = $_POST["sender"];
    $from_card = "";
    $to = strtoupper($_POST["card"]);
    $ammount = $_POST["ammount"];
    $senderPSK = md5($_POST["pwd"]);

    $funds_sender = "";
    $funds_to = "";

    $senderCanPay = false;

    consoleLog("Looking up user");

    $sql = "SELECT * FROM users";
    $result = $db->query($sql);
    while ($row = $result->fetchArray(SQLITE3_ASSOC))
    {
      //echo $row["ref"] . "==" . $from . "<br>";
      if ($row["ref"]==$from && $row["password"]==$senderPSK)
      {
        consoleLog("Sender found!");
        if ($row["funds"]>=$ammount)
        {
          consoleLog("Enough Money!");
          $from_card = $row["card"];
          $funds_sender = $row["funds"];
          $senderCanPay = true;
        } else {
          consoleLog("Not enough funds");
        }
      }
      if ($row["card"]==$to)
      {
        $funds_to = $row["funds"];
      }
    }

    $funds_sender = $funds_sender - $ammount;
    $funds_to = $funds_to + $ammount;

    // run the transaction
    // transactions (party1 TEXT, party2 TEXT, ammount TEXT, stamp TEXT)
    $ts = time();
    $sql = "INSERT INTO transactions (party1, party2, ammount, stamp) VALUES ('$from_card', '$to', '$ammount', '$ts')";
    $db->query($sql);



    $sql = "UPDATE users SET funds='$funds_sender' WHERE card='$from_card'"; $db->query($sql);
    $sql = "UPDATE users SET funds='$funds_to' WHERE card='$to'"; $db->query($sql);

    // update the users session information
    $_SESSION["balance"] = $funds_sender;

    redirrect("home.php");

  }

  function createSellListing(&$db)
  {
    //paycodes (name TEXT, discription TEXT, creator TEXT, ammount TEXT, limited TEXT, uses TEXT, ref TEXT)"
    $ref = generate_uuid();
    $ret = $_POST["redirrect"];
    $name = $_POST['title'];
    $about = $_POST['about'];
    $creator = $_POST['creator'];
    $cost = $_POST['cost'];
    $uses = $_POST['uses'];

    $sql = "SELECT * FROM users";
    $result = $db->query($sql);
    while ($row = $result->fetchArray(SQLITE3_ASSOC))
    {
      if ($row['ref']==$creator)
      {
        $creator = $row['card'];
      }
    }

    if (isset($_POST['limited']))
    {
      consoleLog("Limited Payments");
      $sql = "INSERT INTO paycodes (name,discription, creator, ammount, limited, uses, ref) VALUES
      ('$name','$about','$creator','$cost','1','$uses','$ref')"; $db->query($sql);
      redirrect($ret . '?status=ready&ref=' . $ref);
    } else {
      consoleLog("Unlimited");
      $sql = "INSERT INTO paycodes (name,discription, creator, ammount, limited, uses, ref) VALUES
      ('$name','$about','$creator','$cost','0','','$ref')"; $db->query($sql);
      redirrect($ret . '?status=ready&ref=' . $ref);
    }

  }

  function redirrect($url = "index.php")
  {
    echo "<body>If you're browser doesn't redirrect <a href='$url'>click me</a>";
    echo "<script>setTimeout(function(){ window.location = '$url' }, 1000);</script>";
  }

  // sessionA && sessionB
  if (isset($_POST["intent"]))
  {
    $intent = $_POST["intent"];
    if ($intent=="login")
    {
      login($db);
    } elseif ($intent == "signup")
    {
      signup($db);
    } elseif ($intent == "verify")
    {
      verifyUser($db);
    } elseif ($intent == "redirrect") {
      $yesURL = $_POST["sucess"] . "?reply=safe";
      $noURL = $_POST["failure"] . "?reply=loginInvalid";
      if (verifyUser($db))
      {
        setcookie("safe_redirrect", "true",time()+(1000*10));
        redirrect($yesURL);
      } else {
        setcookie("safe_redirrect", "true",time()+(1000*10));
        redirrect($noURL);
      }
    } elseif ($intent=="pay") {
      consoleLog("Sending Payment");
      pay($db);
    } elseif ($intent == "push-sell") {
      createSellListing($db);
    } elseif ($intent == "refresh") {
      $sql = "SELECT * FROM users";
      $result = $db->query($sql);
      $found = false;
      while ($row = $result->fetchArray(SQLITE3_ASSOC))
      {
          if ($row["ref"]==$_SESSION["accountID"])
          {
            $_SESSION["balance"] = $row["funds"];
            $_SESSION["validLogin"] = time();
            $_SESSION["card"] = $row["card"];
            $found = true;
          }
      }
      redirrect($_POST["after"]);
    }
  }

?>
