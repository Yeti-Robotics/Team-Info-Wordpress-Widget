<?php 
include("updateTeam.php");
$stmt = $db->prepare("SELECT * FROM rankings WHERE team_number=? AND event_key=?");
$stmt->bind_param("is", $_GET["teamNumber"], $_GET["eventKey"]);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
?>
	<html>

	<head></head>

	<body>
		<link rel="stylesheet" type="text/css" href="bootstrap.min.css" />
	</body>

	</html>
	<link rel="stylesheet" type="text/css" href="bootstrap-theme.min.css" />
	<div class="panel 
panel-primary">
		<div class="panel-body">
			<h4 class="text-center">
			<a href="">Team <?php echo $result["team_number"]; ?> @
<?php echo $result["event_name"]; ?></a>
		</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr class="info">
						<td class="text-center">Qualification rank</td>
						<td class="text-center">
							<?php echo $result["qual_rank"]; ?>
						</td>
					</tr>
					<tr class="success">
						<td class="text-center">Wins</td>
						<td class="text-center">
							<?php echo $result["wins"]; ?>
						</td>
					</tr>
					<tr class="danger">
						<td class="text-center">Losses</td>
						<td class="text-center">
							<?php echo $result["losses"]; ?>
						</td>
					</tr>
					<tr class="warning">
						<td class="text-center">Ties</td>
						<td class="text-center">
							<?php echo $result["ties"]; ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>