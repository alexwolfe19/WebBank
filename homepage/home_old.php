<?php
  session_start();

  function redirrect($url = "index.php")
  {
    echo "<body>If you're browser doesn't redirrect <a href='$url'>click me</a>";
    echo "<script>setTimeout(function(){ window.location = '$url' }, 1000);</script>";
  }

  if (isset($_GET["do"]))
  {
    $act = $_GET["do"];
    if ($act=="logout")
    {
      $_SESSION["accountID"] = "0";
      redirrect("index.php?reason=logout");
    }
  }

  if (isset($_SESSION["accountID"]))
  {
    if ($_SESSION["accountID"] == "0") {
      redirrect("index.php?reason=invalidSession");
      echo $_SESSION["accountID"];
    }
  } else {
    redirrect("index.php?reason=invalidSession");
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
       	background-image: url("../res/th2.jpg");
      }

      .wrong {
       border: 2px red solid;
      }
      .valid {
       border: 2px green solid;
      }

      .spend {
        background-color: #fc6464;
      }

      .income {
        background-color: #84fc64;
      }

    </style>

  </head>
  <body>
    <br><br><br>
    <div class="login col-md-6 mx-auto card align-middle inline-block page" id="main">
      <div class="alert alert-danger" style="display: none" id="ic">
  			<strong>Oh Oh!</strong> You are missing information
			</div>
      <br>
      <h1 class="text-center">
        Your Account
      </h1>

      <h3 style="color: green;" class="text-center">$<?php echo $_SESSION["balance"];?></h3>
      <br>
      <button class="btn btn-outline-info" id="pay">Send A Payment</button>
      <br><p></p>
      <button class="btn btn-outline-info" id="buy">Purchase Something</button>
      <br><p></p>
      <button class="btn btn-outline-info" id="sell">Request Payment</button>
      <br><p></p>
      <button class="btn btn-outline-info" id="tlog">Transaction Log</button>
      <br><p></p>
      <button class="btn btn-outline-danger" id="logout">Logout</button>
      <p></p><br>
      <form action="../backend.php" method="post">
        <input type="hidden" name="intent" value="refresh">
        <input type="hidden" name="after" value="home.php">
        <input type="submit" class="btn btn-outline-danger btn-block" id="refresh" value="refresh">
      </form>
      <br>
      <p class="text-center">Website developed by Ethan Manzi</p>
      <br>
    </div>

    <div class="login col-md-6 mx-auto card align-middle inline-block page" id="payView">
      <div class="alert alert-danger" style="display: none" id="ic">
  			<strong>Oh Oh!</strong> You are missing information
			</div>
      <br>
      <h1 class="text-center">
        Pay Someone
      </h1>
      <form action="../backend.php" method="post" class="page" id="mainForm">
        <input name="redirrect" value="home.php" type="hidden">
        <input name="intent" value="pay" type="hidden">
        <input name="sender" value="<?php echo $_SESSION['accountID']; ?>" type="hidden">

        <div class="form-group"> <label for="email">Recipent:</label> <input class="form-control" id="card" type="text" name="card" required>
        </div>
        <div class="form-group"> <label for="email">Amount:</label> <input class="form-control" id="card" type="number" name="ammount" required>
        </div>
        <div class="form-group"> <label for="pwd">Password:</label> <input class="form-control" placeholder="6 characters min" name="pwd" id="pwd" type="password" required>
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-block btn-outline-success" value="Send Payment">
        </div>
      </form>
      <br>
      <button class="btn btn-outline-danger cancel">Cancel</button>
      <br><p></p>
    </div>
    <div class="col-md-6 mx-auto card align-middle inline-block page container" id="log">
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>Sender</th>
              <th>Recipent</th>
              <th>Ammount</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $db = new SQLite3('../database/main.db');
            $sql = "SELECT * FROM transactions";
            //p1, p2, amnt, stamp
            $result = $db->query($sql);
            while ($row = $result->fetchArray(SQLITE3_ASSOC))
            {
              if ($row["party1"] == $_SESSION["card"] || $row["party2"] == $_SESSION["card"])
              {
                if ($row["party1"] == $_SESSION["card"])
                {
                  echo "<tr class='danger spend'>";
                } elseif ($row["party2"] == $_SESSION["card"]) {
                  echo "<tr class='success income'>";
                } else {
                  echo "<tr>";
                }
                echo "<td>" . $row["party1"] . "</td>";
                echo "<td>" . $row["party2"] . "</td>";
                echo "<td>$" . $row["ammount"] . "</td>";
              }
            }
            ?>
          </tbody>
        </table>
        <button class="btn btn-outline-danger cancel" id="cancel">Return</button>
    </div>
  </body>
  <script>
    $(".page").hide();
    setTimeout(function(){ $("#main").fadeIn(); }, 1000);
    $("#pay").click(function() {
      $("#main").fadeOut();
      setTimeout(function(){ $("#payView").fadeIn(); }, 1000);
    });
    $(".cancel").click(function() {
      $(".page").fadeOut();
      setTimeout(function(){ $("#main").fadeIn(); }, 1000);
    });
    $("#logout").click(function() {
      window.location = "home.php?do=logout";
    });
    $("#sell").click(function() {
      window.location = "seller.php";
    });
    $("#tlog").click(function() {
      $(".page").fadeOut();
      setTimeout(function(){ $("#log").fadeIn(); }, 1000);
    });
  </script>

</html>
