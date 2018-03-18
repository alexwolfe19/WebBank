<?php
  session_start();

  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 1);
  error_reporting(-1);

  if (isset($_SESSION["accountID"]))
  {
    if ($_SESSION["accountID"] == "0") {
      redirrect("../index.php?reason=invalidSession");
      echo $_SESSION["accountID"];
    }
  } else {
    redirrect("../index.php?reason=invalidSession");
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>BankU</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  	<style>
      .active {
        background-color: #ddd;
      }
      .tab {
        height:50px;
        width: 100px;
        border: none;
        cursor: pointer;
        padding: 3px;
        border-bottom: solid 1px black;
        background-color: #6d6d6d;
      }
      .tab:hover {
        background-color: #ddd;
        border-bottom: solid 4px #00e8bd;
      }
      .tabBar {
        background: #383838;
        padding: 3px;
      }
    </style>

  </head>
  <body>
    <ul class="tabBar">
      <button class="tab active" page="1" onclick="viewPage(this)">Main</button>
      <button class="tab" page="2" onclick="viewPage(this)">Paymets</button>
      <button class="tab" page="3" onclick="viewPage(this)">Investments</button>
      <button class="tab" page="4" onclick="viewPage(this)">Buisness</button>
      <br>
    </ul>

    <div class="container">
      <div class="page" id="page-1">
        <div class="jumbotron">
          <h1 class="text-center">Hello!</h1>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-2">
            <h3 class="text-center" style="color: green;">$<?php echo $_SESSION["balance"];?></h3>
            <hr>
            <p>
              These funds are avaiable for you to spend. All transactions are immediatly taken into account.
            </p>
            <hr>
            <button class="btn btn-info btn-block">Refresh</button>
            <button class="btn btn-danger btn-block">Logout</button>
          </div>
          <h3 class="text-center">Transaction Log</h3>
          <hr>
          <div class="col-md-10">
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
          </div>
        </div>
      </div>
      <div class="page" id="page-2">
        <div class="jumbotron">
          <h1 class="text-center">Send and Receive Money</h1>
        </div>
        <div class="row">
          <div class="col-md-6">
            <h2 class="text-center">Send</h2>
            <hr>
            <button type="button" page="sendMoney" onclick="viewPage(this)" class="btn btn-danger btn-block" id="sendPayment">Send a Payment</button>
            <button type="button" page="buy" onclick="viewPage(this)" class="btn btn-danger btn-block" id="buyStore">Buy Something</button>
          </div>
          <div class="col-md-6">
            <h2 class="text-center">Receive</h2>
            <hr>
            <button type="button" page="sell" onclick="viewPage(this)" class="btn btn-success btn-block" id="createStore">Sell Something</button>
          </div>
        </div>
      </div>
      <!-- Investments Page -->
      <div class="page" id="page-3">
        <div class="jumbotron">
          <h1 class="text-center">Investments</h1>
        </div>
        <div class="row">
          <div class="col-md-3">
            <p>
              With our investment page, you can invest your money. Large intrest rates, have large minimum wait time.
            </p>
          </div>
          <div class="col-md-9">
            <calculator>
              <p>Intrest:</p>
              <input id="calc-intrest">%
              <p>Deposit:</p>
              <input id="calc-deposit">
              <br>
              <button id="calc-calc">Calculate</button>

              <p>Minimum Widthdraw Period (MWP): <span id="calc-mwp"></span></p>
              <p>Return by end of MWP: <span id="calc-mae"></span></p>
            </calculator>
          </div>
        </div>
      </div>


      <!-- Send Money Page -->
      <div class="page" id="page-sendMoney">
        <div class="jumbotron">
          <h1 class="text-center">Send Money</h1>
        </div>
        <form action="../backend.php" method="post" id="mainForm">
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
            <input type="submit" class="btn btn-block btn-success" value="Send Payment">
          </div>
        </form>
      </div>
      <div class="page" id="page-sell">
        <form action="../seller.php" method="get">
          <input type="submit" value="Click to redirrect" class="btn btn-success btn-block">
        </form>
      </div>

      <!-- Company Page -->
      <div class="page" id="page-4">
        <div class="jumbotron">
          <h1 class="text-center">Company</h1>
        </div>
        <div class="row">
          <div class="col-md-6">
            <h1 class="text-center">Have a company?</h1>
            <form action="../company/backend.php" method="post">
              <input type="hidden" name="intent" value="login">
              <div class="form-group"> <label for="email">Email:</label>
                <input class="form-control" id="email" type="text" name="email" required>
              </div>
              <div class="form-group"> <label for="email">Password:</label>
                <input class="form-control" id="password" type="password" name="password" required>
              </div>
              <div class="form-group">
                <input type="submit" value="Login" class="btn btn-success btn-block">
              </div>
            </form>
          </div>
          <div class="col-md-6">
            <h1 class="text-center">Want a company?</h1>
            <form action="../company/backend.php" method="post">
              <input type="hidden" name="intent" value="new">
              <div class="form-group"> <label for="email">Email:</label>
                <input class="form-control" id="email" type="text" name="email" required>
              </div>
              <div class="form-group"> <label for="email">Password:</label>
                <input class="form-control" id="password" type="password" name="password" required>
              </div>
              <div class="form-group"> <label for="email">Company Name:</label>
                <input class="form-control" id="cname" type="text" name="cname" required>
              </div>
              <div class="form-group">
                <input type="submit" value="Login" class="btn btn-success btn-block">
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>

  <!-- Coding -->
  <script>

    $(".page").hide();
    $("#page-1").fadeIn();

    function viewPage(button)
    {
      $(".page").fadeOut();
      $(".page").removeClass("active");
      setTimeout(function(){
        $("#page-" + $(button).attr("page")).fadeIn();
        $(button).addClass("active");
      }, 1000);
    }

    //Investments

    console.log("READY");
    $("#calc-calc").click(function(){
    {
      // intrest in calculate every min
      var ppi = 340; // Minumum time per intrest % (in mins)
      var i = $("#calc-intrest").val()/100;
      var d = $("#calc-deposit").val();

      var minPeriod = ppi * i;
      $("#calc-mwp").text(parseInt(minPeriod) + " mins");
      var x = (i * minPeriod)*d
      x = parseInt(d)+parseInt(x);
      $("#calc-mae").text(x);
      console.log("YO BOI");
    }
  });
  </script>
</html>
