<?php
  if (isset($_POST["do"]))
  {
    $do = $_POST["do"];

    if ($do=="new-transaction")
    {
      $reply_good = $_POST["on-compleate"];
      $reply_bad = $_POST["on-failure"];

      $depositAccount = $_POST["to"];

      $companyName = $_POST["cname"];
      $discription = $_POST["about"];
    }
  }
?>
