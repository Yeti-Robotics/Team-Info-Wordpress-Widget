<?php
//Make sure to get your own FRC Events API token, and update the year
$FRCEventsAPIUser = "";
$FRCEventsAPIToken = "";
$FRCEventsServer = "https://frc-api.firstinspires.org/v2.0";
$tournamentYear = 2016;

////Be sure to use your own database
$db = new mysqli();
$db->connect("", "", "", "");

function getData($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://frc-api.firstinspires.org/v2.0" . "/" . 2016 . $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array (
    "Accept: application/json",
    "Authorization: Basic " . base64_encode("" . ":" . ""),
    "If-Modified-Since: " . date(DATE_RSS, strtotime($lastmodified ? $lastmodified : "1 January 2000"))
  ));
  $response = json_decode(curl_exec($ch), true);
  $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  $returnArr = array("response" => $response, "statusCode" => $statusCode);
  return $returnArr;
}

if(!empty($_GET["teamNumber"]) && !empty($_GET["eventKey"])) {
  $team = $_GET["teamNumber"];
  $event = $_GET["eventKey"];
  $lastmodified = 0;
  
//  if($stmt = $db->prepare("SELECT lastmodified FROM rankings WHERE team_number=? AND event_key=?")) {
//    $stmt->bind_param("is", $team, $event);
//    $stmt->execute();
//    if($stmt->num_rows != 0) {
//        $lastmodified = $row["lastmodified"];
//    }
//  }

  $data = getData("/rankings/" . $event . "?teamNumber=" . $team, $lastmodified);
  $response = $data["response"]["Rankings"][0];
  $statusCode = $data["statusCode"];
  if ($statusCode != 304) {
      if($lastmodified > 0) {
      	$query = "UPDATE rankings SET qual_rank=?, wins=?, losses=?, ties=? WHERE team_number=? AND event_key=?";
        $stmt = $db->prepare($query);
	$stmt->bind_param("iiiiis", $response["rank"], $response["wins"], $response["losses"], $response["ties"], $response["teamNumber"], $event);
        $stmt->execute();
      } else {
      	$teamData = getData("/teams?teamNumber=" . $team)["response"]["teams"][0];
      	$eventData = getData("/events?eventCode=" . $event)["response"]["Events"][0];
      	$query = "INSERT INTO rankings (team_number, team_name, event_key, event_name, qual_rank, wins, losses, ties) VALUES (?,?,?,?,?,?,?,?)";
	mysqli_report(MYSQLI_REPORT_ALL);
	$stmt = $db->prepare($query);
	echo mysqli_error($db);
	$stmt->bind_param("isssiiii", $response["teamNumber"], $teamData["nameShort"], $event, $eventData["name"], $response["rank"], $response["wins"], $response["losses"], $response["ties"]);
        $stmt->execute();
    }
  }
} else {
//Error
}
?>
