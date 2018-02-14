<?php
include '../SurvivorLeagueFunctions.php';

if(isset($_GET['action']) && $_GET['action'] ==='submitPicks'){
  submitPicks("insert into player values(", 7);
}
if(isset($_GET['action']) && $_GET['action'] ==='edit'){
  if ($_POST['delete']){
    submitPicks("delete from player where name="."'".$_POST['name']."'",-1);
  } else {
    submitPicks("update player set ", 7);
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
  <h1>Week 7</h1>
  <table>
    <tr>
      <th>Name</th>
      <th>Total Points</th>
      <?php genTableHeader(7); ?>
    </tr>
    <?php buildTableFromDB(7); ?>
    <tr>
      <form action="?action=submitPicks" method="post">
        <td><input type="text" name="name" maxlength="30" size="30"></td>
        <td><input type="text" name="wins" maxlength="2" size="2"></td>
        <?php buildLastTableRow(7); ?>
        <td><span style="padding-left:10px"><input type="submit" value="submit"></span></td>
      </form>
    </tr>
  </table>

  <span class="left">
    <a href="week6.php" value="prevWeek">&lt;-prev week</a>
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
    <a href="week8.php" value="nextWeek">next week-></a>
  </span>
</body>
</html>
