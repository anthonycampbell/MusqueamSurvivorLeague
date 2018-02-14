<?php
define("BEFORE_RADIO_SPACING", '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp');
define("BETWEEN_RADIO_SPACING", '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp');
define("IMAGE_DIR", '');
define("SERVER_NAME", "");
define("USERNAME", "");
define("PASSWORD", "");
define("DB_NAME", "");

$teamImageMap = array("DAL"=>"dallasCowboys.jpeg", "NE"=>"newEnglandPatriots.png",
"BAL"=>"baltimoreRavens.png", "BUF"=>"buffaloBills.png", "ARI"=>"arizonaCardinals.png",
"ATL"=>"atlantaFalcons.png", "CAR"=>"carolinaPanthers.png", "CHI"=>"chicagoBears.png",
"CIN"=>"cincinnatiBengals.gif", "CLE"=>"clevelandBrowns.png", "DEN"=>"denverBroncos.png",
"DET"=>"detroitLions.png", "GB"=>"greenBayPackers.png", "HOU"=>"houstonTexans.png",
"IND"=>"indianapolisColts.png", "JAX"=>"jacksonvilleJaguars.png", "KC"=>"kansasCityChiefs.png",
"LAC"=>"losAngelasChargers.png", "LA"=>"losAngelasRams.png", "MIA"=>"miamiDolphins.png",
"MIN"=>"minnesotaVikings.png", "NO"=>"newOrleansSaints.png", "NYG"=>"newYorkGiants.gif",
"NYJ"=>"newYorkJets.gif", "OAK"=>"oaklandRaiders.gif", "PHI"=>"philadelphiaEagles.png",
"PIT"=>"pittsburghSteelers.png", "SF"=>"sanFrancisco49ers.gif", "SEA"=>"seattleSeahawks.png",
"TB"=>"tampaBayBuccaneers.png", "TEN"=>"tennesseeTitans.png", "WAS"=>"washingtonRedskins.gif");

function submitpicks($sqlStr, $w){
  $picks = array();
  $playerInfo = array();
  $name = trim($_POST['name']);
  $name = "'".$name."'";
  $playerInfo[] = $name;
  $picks[] = $_POST['wins'];
  for ($i = 1; $i < 33; $i++){
    $j = (string)$i;
    $picks[] = $_POST[$j];
  }
  $picks[] = $name;
  $picks[] = $w;
  $sql = $sqlStr;
  switch($sqlStr){
    case "insert into player values(":
    if ($name === '\'result\''){
      $everyonesChoices = selectQuery("select * from choices where week=$picks[34]");
      foreach ($everyonesChoices as $playerChoices){
        $pWinCount = (int)$playerChoices[0];
        for ($j = 1; $j < sizeof($playerChoices) - 3; $j++){
          if ($playerChoices[$j] === $picks[$j] && $playerChoices[$j] !== null && $picks[$j] !== null){
            $pWinCount++;
          }
        }
        echo $pWinCount;
        $sql = "update choices set wins=$pWinCount where name='$playerChoices[33]' and week=$picks[34]";
        editDB($sql);
      }
    }
    $sql = "insert into player values(".$playerInfo[0].")";
    editDB($sql);
    $sql = "insert into choices values(";
    foreach ($picks as $p){
      if ($p){
        $sql = $sql.$p.",";
      } else {
        $sql = $sql."null,";
      }
    }
    $sql = rtrim($sql, ",");
    $sql = $sql.")";
    editDB($sql);
    break;
    case "update player set ":
    $playerAlreadyChose = selectQuery("select * from choices where name=$picks[33] and week=$picks[34]");
    if ($name === "result"){
      if ($playerAlreadyChose){

      } else {

      }
    } else {
      if ($playerAlreadyChose){
        $sql = "update choices set ";
        $i = 0;
        foreach ($picks as $p){
          switch($i){
            case 0:
            if($p){
              $sql = $sql."wins=".$p.",";
            } else {
              $sql = $sql."wins=null,";
            }
            break;
            case 33:
            $sql = $sql."name=".$p.",";
            break;
            case 34:
            $sql = $sql."week=".$p.",";
            break;
            default:
            if ($p){
              $sql = $sql."g$i=$p,";
            } else {
              $sql = $sql."g$i=null,";
            }
          }
          $i++;
        }
        $sql = rtrim($sql, ",");
        $sql = $sql." where name=$picks[33]";
        editDB($sql);
      } else {
        $sql = "insert into choices values(";
        foreach ($picks as $p){
          if ($p){
            $sql = $sql.$p.",";
          } else {
            $sql = $sql."null,";
          }
        }
        $sql = rtrim($sql, ",");
        $sql = $sql.")";
        editDB($sql);
      }
    }
    break;
    case "delete from player where name="."'".$_POST['name']."'":
    $sql = $sqlStr;
    editDB($sql);
    editDB("delete from choices where name="."'".$_POST['name']."'");
    break;
    default:
    echo 'BAD QUERY';
  }
}
function genTableHeader($w){
  global $teamImageMap;
  $weekxml = simplexml_load_file("/Library/WebServer/Documents/MusqueamSurvivorLeague/week_data/mslw$w.xml");
  $numGames = sizeof($weekxml->gms->g);
  $teamImageDir = opendir(IMAGE_DIR);
  for($i = 0; $i < $numGames; $i++){
    $h = $teamImageMap[(string)$weekxml->gms->g[$i]->attributes()[6]];
    $v = $teamImageMap[(string)$weekxml->gms->g[$i]->attributes()[9]];
    echo '<th>'.PHP_EOL;
    echo '<img src="/MusqueamSurvivorLeague/team_images/'.$h.'" style="height:15%;"><span class="raise">vs</span>'.PHP_EOL;
    echo '<img src="/MusqueamSurvivorLeague/team_images/'.$v.'" style="height:15%;">'.PHP_EOL;
    echo '</th>'.PHP_EOL;
  }
  echo '<th></th>'.PHP_EOL;
  closedir($teamImageDir);
}
function buildTableFromDB($w){
  $weekxml = simplexml_load_file("/Library/WebServer/Documents/MusqueamSurvivorLeague/week_data/mslw$w.xml");
  $numGames = sizeof($weekxml->gms->g);
  $playerEntry = selectQuery("select * from player");
  $playerChoices = selectQuery("select * from choices where week='$w' order by week");
  foreach ($playerEntry as $p){
    echo '<tr>'.PHP_EOL;
    echo '<form action="?action=edit" method="post">'.PHP_EOL;
    echo '<td><input type="text" name="name" maxlength="30" size="30" value="'.$p[0].'"></td>'.PHP_EOL;
    for ($k = 0; $k < sizeof($playerChoices); $k++){
      if ($playerChoices[$k][33] === $p[0]){
        $pcs = $playerChoices[$k];
        break;
      }
    }
    $j = 1;
    if (!isset($pcs[0])){
      echo '<td><input type="text" name="wins" maxlength="2" size="2" value=0></td>'.PHP_EOL;
    } else {
      echo '<td><input type="text" name="wins" maxlength="2" size="2" value="'.$pcs[0].'"></td>'.PHP_EOL;
    }
    for ($i = 1; $i < $numGames + 1; $i++){
      $n = (string)$j;
      echo '<td>'.PHP_EOL;
      echo BEFORE_RADIO_SPACING.PHP_EOL;
        switch ($pcs[$i]){
          case '1':
          echo '<input type="radio" name="'.$n.'" id="Pick" value="1" checked="true">'.PHP_EOL;
          echo BETWEEN_RADIO_SPACING.PHP_EOL;
          echo '<input type="radio" name="'.$n.'" id="Pick" value="2">'.PHP_EOL;
          break;
          case '2':
          echo '<input type="radio" name="'.$n.'" id="Pick" value="1">'.PHP_EOL;
          echo BETWEEN_RADIO_SPACING.PHP_EOL;
          echo '<input type="radio" name="'.$n.'" id="Pick" value="2" checked="true">'.PHP_EOL;
          break;
          default:
          echo '<input type="radio" name="'.$n.'" id="Pick" value="1">'.PHP_EOL;
          echo BETWEEN_RADIO_SPACING.PHP_EOL;
          echo '<input type="radio" name="'.$n.'" id="Pick" value="2">'.PHP_EOL;
          break;
      }
      $j++;
    }
    $pcs = array();
    echo '<td>'.PHP_EOL;
    echo '<input type="submit" name="re-submit" value="re-submit">'.PHP_EOL;
    echo '<span style="padding-left:10px">'.PHP_EOL;
    echo '<input type="submit" name = "delete" value="delete">'.PHP_EOL;
    echo '</span>'.PHP_EOL;
    echo '</form>'.PHP_EOL;
    echo '</td>'.PHP_EOL;
    echo '</tr>'.PHP_EOL;
  }
}
function buildLastTableRow($w){
  $weekxml = simplexml_load_file("/Library/WebServer/Documents/MusqueamSurvivorLeague/week_data/mslw$w.xml");
  $numGames = sizeof($weekxml->gms->g);
  for ($i = 1; $i <= $numGames; $i++){
    $n = (string)$i;
    echo '<td>'.PHP_EOL;
    for ($j = 1; $j < 3; $j++){
      if ($j === 1){
        echo BEFORE_RADIO_SPACING.PHP_EOL;
      }
      echo '<input type="radio" name="'.$n.'" id="pick" value="'.$j.'">'.PHP_EOL;
      if ($j === 1){
        echo BETWEEN_RADIO_SPACING.PHP_EOL;
      }
    }
    echo '</td>'.PHP_EOL;
  }
}
function buildDefaultTableFromDB(){

}

function buildLastTRForDefault(){
  for ($i = 1; $i <= 32; $i++){
    $n = (string)$i;
    echo '<td>'.PHP_EOL;
    echo BEFORE_RADIO_SPACING.PHP_EOL;
    echo '<input type="checkbox" name="'.$n.'" id="pick">'.PHP_EOL;
    echo '</td>'.PHP_EOL;
  }
}

function selectQuery($sqlStr){
  try {
    $conn = new PDO("mysql:host=".SERVER_NAME.";dbname=".DB_NAME."", USERNAME, PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sqlStr);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    $conn = null;
    return $result;
  }
  catch(PDOException $e)
  {
    echo "failed select: " . $e->getMessage();
  }
}

function editDB($sql){
  try {
    $conn = new PDO("mysql:host=".SERVER_NAME.";dbname=".DB_NAME."", USERNAME, PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec($sql);
    $conn = null;
  }
  catch(PDOException $e)
  {
    echo "Connection failed: " . $e->getMessage();
  }
}
?>
