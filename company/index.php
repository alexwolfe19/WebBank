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
       	background-image: url("../res/th_c1.jpg");
        background-position: center;
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
    <p></p><p></p>
    <div class="login col-md-6 mx-auto card align-middle inline-block">
      <div class="alert alert-danger" style="display: none" id="ic">
  			<strong>Oh Oh!</strong> You are missing information
			</div>
      <h1 class="text-center">
        Company Management Portal
      </h1>
      <form action="backend.php" method="post" class="" id="mainForm">
        <input name="redirrect" value="management.php" type="hidden">
        <input name="intent" value="lookup" id="dt" type="hidden">

        <div class="form-group"> <label for="email">Company URL:</label> <input class="form-control" id="uname" type="text" name="uname" required>
        </div>
      	<div class="form-group">
          <input type="submit" value="Login" class="btn btn-outline-success btn-block">
        </div>
      </form>
      <button id="siup" class="btn btn-outline-success btn-block">Create your company!</button>
      <p></p>
      <br>
      <p class="text-center" style="padding:5px;">Website developed by Ethan Manzi (C)2017</p>
    </div>
  </body>

  <script>

    $('#pwd').on('input', function() {
			var val = $("#pwd").val();
      if(val.length > 6){
    		$("#pwd").removeClass("wrong");
        $("#pwd").addClass("valid");
			} else {
        $("#pwd").removeClass("valid");
        $("#pwd").addClass("wrong");
      }
    });


  $('#siup').click(function(){
    if ($("#card").val() == "" || $("#pwd").val() == "") {
      if ($("#card").val() == "") {
      	$("#card").addClass("wrong");
      }
      if ($("#pwd").val() == "") {
      	$("#pwd").addClass("wrong");
      }
      $("#ic").fadeIn();
    } else {
  		//$('#dt').attr('action', 'signup.php');
      $("#dt").val("signup");
    	$("#mainForm").submit();
    }
	});

  </script>
</html>
