<?php
//Make sure to get your own FRC Events API token, and update the year
$FRCEventsAPIUser = "user";
$FRCEventsAPIToken = "token";
$FRCEventsServer = "https://frc-api.firstinspires.org/v2.0";
$tournamentYear = 2016;

////Be sure to use your own database
$db = new mysqli();
$db->connect("localhost", "root", "", "scouting");

function getData($url, $lastmodified) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $FRCEventsServer . "/" . $tournamentYear . $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array (
	  "Accept: application/json",
	  "Authorization: Basic xxxxxxxxxx",
	  "If-Modified-Since: " . date_format(strtotime($lastmodified), 'D, d M Y H:i:s T')
	));
	$response = json_decode(curl_exec($ch), true);
	$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE)
	curl_close($ch);
	return $response;
}

if(!empty($_GET["teamNumber"]) && !empty($_GET["eventKey"])) {
	$team = $_GET["teamNumber"];
	$event = $_GET["eventKey"];
	$lastmodified = 0;

	if($stmt = $db->prepare("SELECT lastmodified FROM rankings WHERE team_number=? AND event_key=?")) {
		$stmt->bind_param("is", $team, $event);
		$stmt->execute();
		if($stmt->num_rows != 0) {
	  		$lastmodified = $row["lastmodified"];
		}
	}

	$response = getData("/rankings/" . $event . "?teamNumber=" . $team, $lastmodified)["Rankings"][0];
	$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE)
	curl_close($ch);

	if ($statusCode != 304) {
  		if($lastmodified > 0) {
			$query = "UPDATE rankings SET qual_rank=?, wins=?, losses=?, ties=?, lastmodified=? WHERE team_number=? AND event_key=?";
	  		$stmt->bind_param("iiiisii", $response["rank"], $response["wins"], $response["losses"], $response["ties"], date('Y-m-d H:i:s'), $response["teamNumber"], $event);
	  		$stmt->execute();
  		} else {
			$teamData = getData("/teams?teamNumber=" . $team)["teams"][0];
			$eventData = getData("/events?eventCode=" . $event)["Events"][0];
			$query = "INSERT INTO rankings (team_number, team_name, event_key, event_name, qual_rank, wins, losses, ties, lastmodified) VALUES (?,?,?,?,?,?,?)";
	  		$stmt->bind_param("isisiiiis", $response["teamNumber"], $teamData["nameShort"], $event, $eventData["name"], $response["rank"], $response["wins"], $response["losses"], $response["ties"], date('Y-m-d H:i:s'));
	  		$stmt->execute();
		}
	}
else {
//Error
}
?>
