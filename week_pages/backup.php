<?php
include '../SurvivorLeagueFunctions.php';

if(isset($_GET['action']) && $_GET['action'] ==='submitPicks'){
  submitPicks("insert into player values(", 1);
}
if(isset($_GET['action']) && $_GET['action'] ==='edit'){
  if ($_POST['delete']){
    submitPicks("delete from player where name="."'".$_POST['name']."'",-1);
  } else {
    submitPicks("update player set ", 1);
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
  <h1>backup week</h1>
  <table>
    <tr>
      <th>Name</th>
      <th>Total Points</th>
      <th><img src="/MusqueamSurvivorLeague/team_images/dallasCowboys.jpeg" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/newEnglandPatriots.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/baltimoreRavens.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/buffaloBills.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/arizonaCardinals.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/atlantaFalcons.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/carolinaPanthers.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/chicagoBears.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/cincinnatiBengals.gif" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/clevelandBrowns.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/denverBroncos.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/detroitLions.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/greenBayPackers.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/houstonTexans.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/indianapolisColts.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/jacksonvilleJaguars.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/kansasCityChiefs.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/losAngelasChargers.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/losAngelasRams.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/miamiDolphins.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/minnesotaVikings.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/newOrleansSaints.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/newYorkGiants.gif" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/newYorkJets.gif" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/oaklandRaiders.gif" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/philadelphiaEagles.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/pittsburghSteelers.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/sanFrancisco49ers.gif" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/seattleSeahawks.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/tampaBayBuccaneers.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/tennesseeTitans.png" style="height:15%;"></th>
      <th><img src="/MusqueamSurvivorLeague/team_images/washingtonRedskins.gif" style="height:15%;"></th>
      <th></th>
    </tr>
    <?php buildDefaultTableFromDB(); ?>
    <tr>
      <form action="?action=submitPicks" method="post">
        <td><input type="text" name="name" maxlength="30" size="30"></td>
        <td><input type="text" name="wins" maxlength="2" size="2"></td>
        <?php buildLastTRForDefault(); ?>
        <td><span style="padding-left:10px"><input type="submit" value="submit"></span></td>
      </form>
    </tr>
  </table>

  <span class="left">
    <a value="prevWeek">&lt;-prev week</a>
  </span>
  <span class="center">
    <a href="week1.php" value="week1">week1</a>
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
    <a value="nextWeek">next week-></a>
  </span>
</body>
</html>
