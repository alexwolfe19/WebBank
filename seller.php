<?php
session_start();

$page = 1;
$cr = "";

if (isset($_GET["status"]))
{
  if (isset($_GET["ref"]))
  {
    $page = 2;
    $cr = $_GET["ref"];
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

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <style>

    </style>

  </head>
  <body>
    <div class="page1 page">
      <form action="backend.php" method="post" class="" id="mainForm">
        <input name="redirrect" value="seller.php" type="hidden">
        <input name="intent" value="push-sell" id="dt" type="hidden">
        <input name="creator" value="<?php echo $_SESSION['accountID']; ?>" type="hidden">

        <div class="form-group"> <label for="email">Title:</label> <input class="form-control" id="title" type="text" name="title" required>
        </div>
        <div class="form-group"> <label for="pwd">Discription:</label> <input class="form-control" placeholder="" name="about" id="about" type="text" required>
        </div>
        <div class="form-group"> <label for="pwd">Cost:</label> <input class="form-control" placeholder="$0.00" name="cost" id="cost" type="number" required>
        </div>
        <div class="form-group"><label for="limited">Limited:</label><input name="limited" id="limited" type="checkbox"></div>

        <!-- Only if it is limited -->
        <div class="form-group" id="if_limited"> <label for="uses"># of uses:</label> <input class="form-control" placeholder="0" name="uses" id="uses" type="number">
        </div>

        <div class="form-group">
          <input type="submit" value="Create Purchase" class="btn btn-outline-success btn-block">
        </div>
      </form>
    </div>
    <div class="page page2">
      <img class="center" style="position: absolute; width:auto; height:100%; top:0; left:0%; right:0%;" src="https://api.qrserver.com/v1/create-qr-code/?size=1800x1800&data=https://ethans-macbook-pro.local/projects/banku/pay.php?obj=<?php echo $cr; ?>"></img>
    </div>
  </body>
  <script>
  $(".page").hide();
  $(".page<?php echo $page;?>").show();

  $('#if_limited').hide();

    $('#limited').click(
      function () {
        if ($('#limited').is(':checked')) {
            $('#if_limited').fadeIn();
          }
          else {
            $('#if_limited').fadeOut();
          }
    });
  </script>
</html>
