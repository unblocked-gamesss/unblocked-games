<?php
require( '../config.php' );
require( '../init.php' );

if(isset($_POST['vote']) && isset($_POST['action']) && isset($_POST['id'])){
	$ip_address = getIpAddr();
	$conn = open_connection();
	$sql = "SELECT * FROM votelogs WHERE ip = :ip AND game_id = :game_id AND action = :action";
	$st = $conn->prepare($sql);
	$st->bindValue(":ip", $ip_address, PDO::PARAM_STR);
	$st->bindValue(":game_id", $_POST['id'], PDO::PARAM_INT);
	$st->bindValue(":action", $_POST['action'], PDO::PARAM_STR);
	$st->execute();
	$row = $st->fetch(PDO::FETCH_ASSOC);
	if(!$row){
		if($_POST['action'] == 'upvote'){
			Game::upvote($_POST['id']);
			if($login_user){
				$login_user->like($_POST['id']);
			}
		} elseif ($_POST['action'] == 'downvote') {
			Game::downvote($_POST['id']);
			if($login_user){
				$login_user->dislike($_POST['id']);
			}
		}
		//
		$sql = "INSERT INTO votelogs(ip,game_id,action) VALUES(:ip_address, :game_id, :action)";
		$st = $conn->prepare($sql);
		$st->bindValue(":ip_address", $ip_address, PDO::PARAM_STR);
		$st->bindValue(":game_id", $_POST['id'], PDO::PARAM_INT);
		$st->bindValue(":action", $_POST['action'], PDO::PARAM_STR);
		$st->execute();
		//Check count
		$sql = "SELECT * FROM votelogs";
		$st = $conn->prepare($sql);
		$st->execute();
		$count = $st->rowCount();
		if($count > 120){
			$sql = "DELETE FROM votelogs ORDER BY id ASC LIMIT 20";
			$st = $conn->prepare($sql);
			$st->execute();
		}
	} else {
		echo(' exist');
	}
}
if(isset($_POST['favorite']) && isset($_POST['action']) && isset($_POST['id'])){
	if($login_user){
		$conn = open_connection();
		$sql = "SELECT * FROM favorites WHERE user_id = :user_id AND game_id = :game_id LIMIT 1";
		$st = $conn->prepare($sql);
		$st->bindValue(":user_id", $login_user->id, PDO::PARAM_INT);
		$st->bindValue(":game_id", $_POST['id'], PDO::PARAM_INT);
		$st->execute();
		$row = $st->fetch(PDO::FETCH_ASSOC);
		if($row){
			// Remove from favorite
			$sql = "DELETE FROM favorites WHERE user_id = :user_id AND game_id = :game_id LIMIT 1";
			$st = $conn->prepare($sql);
			$st->bindValue(":user_id", $login_user->id, PDO::PARAM_INT);
			$st->bindValue(":game_id", $_POST['id'], PDO::PARAM_INT);
			$st->execute();
			echo 'rm-favorite';
		} else {
			// Add to favorite
			$sql = "INSERT INTO favorites(game_id,user_id) VALUES(:game_id, :user_id)";
			$st = $conn->prepare($sql);
			$st->bindValue(":user_id", $login_user->id, PDO::PARAM_INT);
			$st->bindValue(":game_id", $_POST['id'], PDO::PARAM_INT);
			$st->execute();
			echo 'add-favorite';
		}
	}
}
?>