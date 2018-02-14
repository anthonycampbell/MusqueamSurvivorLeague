<?php
include 'SurvivorLeagueFunctions.php';

if(isset($_GET['action']) && $_GET['action'] ==='submitPicks'){
  submitPicks("insert into player values(");
}
if(isset($_GET['action']) && $_GET['action'] ==='edit'){
  if ($_POST['delete']){
    submitPicks("delete from player where name="."'".$_POST['name']."'");
  } else {
    submitPicks("update player set ");
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>MusqueamSurvivorPool</title>
  <style>
  table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 250%
  }
  th {
    border: 2px solid #dddddd;
    text-align: center;
  }
  td {
    border: 2px solid #dddddd;
  }
  tr:nth-child(even) {
    background-color: #dddddd;
  }
  .raise{
    position: relative;
    bottom: 20px;
    text-align: center;
  }
  .left{
    float: left;
  }
  .center {
    display: inline-block;
    text-align: center;
    width: 88%;
  }
  .right {
    float: right;
  }
  </style>
</head>
<body>
  <?php
  $week = trim($_GET['weekSelect'], "Week ");
  if ($week >= 1 && $week <= 17 || isset($_GET['action'])){
    echo '<h1>Week '.$week.'</h1>'.PHP_EOL;
  } else {
    http_response_code(404);
    include('/Library/Webserver/Documents/MusqueamSurvivorLeague/msl404.php'); // provide your own HTML for the error page
    die();
  }
  ?>
  <table>
    <tr>
      <th>Name</th>
      <th>Total Points</th>
      <?php genTableHeader(1); ?>
    </tr>
    <?php buildTableFromDB(1); ?>
    <tr>
      <form action="?action=submitPicks" method="post">
        <td><input type="text" name="name" maxlength="30" size="30"></td>
        <td><input type="text" name="wins" maxlength="2" size="2"></td>
        <?php buildLastTableRow(1); ?>
        <td><span style="padding-left:10px"><input type="submit" value="submit"></span></td>
      </form>
    </tr>
  </table>

  <span class="left">
    <?php
    $nextWeek = $week + 1;
    $prevWeek = $week - 1;
    if ($week > 1 && $week <= 17){
      echo '<a href="MusqueamSurvivorLeague.php?weekSelect=Week+'.$prevWeek.'" value="prevWeek">&lt;-prev week</a>';
    } else {
      echo '<a value="prevWeek">&lt;-prev week</a>';
    }
    ?>
  </span>
  <!--<span class="center">
    <form action="MusqueamSurvivorLeague" method="get">
      <select name="weekSelect">
        <option value="default">Select week</option>
        <?php
        for($i = 1; $i <= 17; $i++){
          $n = (string)$i;
          echo '<option value="Week '.$n.'">Week '.$n.'</option>'.PHP_EOL;
        }
        ?>
      </select>
      <input type="submit" value="go to week"/>
    </form>
  </span>-->
  <span class="right">
    <?php
    if ($week > 0 && $week < 17){
      echo '<a href="MusqueamSurvivorLeague.php?weekSelect=Week+'.$nextWeek.'" value="nextWeek">next week-></a>'.PHP_EOL;
    } else {
      echo '<a value="nextWeek">next week-></a>'.PHP_EOL;
    }
    ?>
  </span>
</body>
</html>
