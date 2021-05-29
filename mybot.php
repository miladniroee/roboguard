<?php

include("Telegram.php");
date_default_timezone_set("asia/tehran");




// bot setting
$bot_id = 'your token here';
$welcome_message = true; //true or false
$rude_message = true; //true or false
$rude_words = ["donkey", "rudeword"]; //your rude words here
$rude_counter = count($rude_words);
$channel_or_group_links_delete = true; //true or false
$sites_links_delete = true; //true or false
$Dedicate_the_group = [false, "your group id"]; //true or false | for understanding read line 26
//data base
$dbuser = "your db user here";
$dbpass = "your db password here";
$dbname = "your db name here";
$dbtable = "your db table here"; //for understanding better read line 23
// In order for you to make the table columns correctly, please go to the following link:
// https://s4.uupload.ir/files/columns_45dt.png

// To find out your group ID, first turn off the $Dedicate_the_group status, then add the robot to your group and type the word info so that the robot can tell you its group ID.


$bot_id_explode = explode(":", $bot_id);
$Dedicate_the_group[2] = $bot_id_explode[0];
// Instances the class
$telegram = new Telegram($bot_id);

//$result = $telegram->getData();

// Take text and chat_id from the message
$text 			 	 = $telegram->Text();
$caption			 = $telegram->Caption();
$chat_id 		 	 = $telegram->ChatID();
$username 		 	 = $telegram->Username();
$name 		  	 	 = $telegram->FirstName();
$family 		 	 = $telegram->LastName();
$message_id 	  	 = $telegram->MessageID();
$reply_to_message_id = $telegram->ReplyToMessageID();
$user_id 			 = $telegram->UserID();
$replyUserId	     = $telegram->ReplyToMessageFromUserID();
$msgType		  	 = $telegram->getUpdateType();
$replyUsername	     = $telegram->ReplyToMessageFromUsername();

$current_time = date('H:i');

if ($msgType == 'message') {
	if ($text == "info") {
		$content = ['chat_id' => $chat_id, 'text' => "your group id : $chat_id"];
		$telegram->sendMessage($content);
	}
}


if ($msgType == 'new_chat_member') {
	if ($welcome_message) {
		$content = ['chat_id' => $chat_id, 'text' => "Hi $name
		You are very welcome to our group
		⏰ Time to join our group:
		$current_time
		Our Telegram Channel:
		@entereourtelegramcahnnel
		"];
		$telegram->sendMessage($content);
	}
}
if ($msgType == 'new_chat_member' || $msgType == 'left_chat_member') {
	$content = ['chat_id' => $chat_id, 'message_id' => $message_id];
	$telegram->deleteMessage($content);
}
$admino = array('chat_id' => $chat_id, 'user_id' => $user_id);
$join_info = $telegram->getChatMember($admino);
$join_status = $join_info['result']['status'];

if ($msgType == 'reply') {
	if ($text == 'ban') {
		if ($join_status == 'administrator' || $join_status == 'creator') {
			$content = ['chat_id' => $chat_id, 'user_id' => $replyUserId];
			$telegram->kickChatMember($content);

			$content = ['chat_id' => $chat_id, 'text' => "this user $replyUsername
				The user was expelled from the group. Please observe the etiquette and order of the group
				Thanks Robo Guard."];
			$telegram->sendMessage($content);
		} else {
			$content = ['chat_id' => $chat_id, 'text' => "$name You are not an admin 😐"];
			$telegram->sendMessage($content);
		}
	}
	if ($text == 'alert') {
		if ($join_status == 'administrator' || $join_status == 'creator') {
			$myconn = mysqli_connect("localhost", $dbuser, $dbpass, $dbname);
			$gogo = explode("-", $chat_id);
			$oogy = $gogo[1];
			$samsam = $oogy . $replyUserId;
			$query = "SELECT * FROM $dbtable WHERE user=$samsam";
			$res = mysqli_query($myconn, $query);
			$row = mysqli_fetch_row($res);
			if (!$row) {
				$gogo = explode("-", $chat_id);
				$oogy = $gogo[1];
				$samsam = $oogy . $replyUserId;
				$ekhtar = 1;
				$que = "INSERT INTO $dbtable (user,ekhtar) VALUES ('$samsam', $ekhtar)";
				mysqli_query($myconn, $que);
				$content = ['chat_id' => $chat_id, 'text' => "this user $replyUsername
					Received a warning Dear user, please follow the group order 😬
					Number of your warnings:
					It becomes 1/4.
					Thanks Robo Guard."];
				$telegram->sendMessage($content);
			} else {
				$gogo = explode("-", $chat_id);
				$oogy = $gogo[1];
				$samsam = $oogy . $replyUserId;
				$query2 = "SELECT * FROM $dbtable WHERE user=$samsam";
				$res2 = mysqli_query($myconn, $query2);
				$row2 = mysqli_fetch_row($res2);
				$finalsam = $row2[2];
				if ($finalsam >= 3) {
					$gogo = explode("-", $chat_id);
					$oogy = $gogo[1];
					$samsam = $oogy . $replyUserId;
					$content = ['chat_id' => $chat_id, 'text' => "this user $replyUsername
						He was expelled from the group. Please observe the etiquette and order of the group
						Number of your warnings:
						It becomes 4/4.
						Thanks Robo Guard."];
					$telegram->sendMessage($content);
					$content = ['chat_id' => $chat_id, 'user_id' => $replyUserId];
					$telegram->kickChatMember($content);
				}
				if ($finalsam == 2) {
					$gogo = explode("-", $chat_id);
					$oogy = $gogo[1];
					$samsam = $oogy . $replyUserId;
					$content = ['chat_id' => $chat_id, 'text' => "this user $replyUsername
						Received a warning Dear user, please follow the group order
						Number of your warnings:
						It becomes 3/4.
						Thanks Robo Guard."];
					$telegram->sendMessage($content);
					$fekhtar = 3;
					$que = "UPDATE $dbtable SET ekhtar='$fekhtar' WHERE user=$samsam";
					mysqli_query($myconn, $que);
				}
				if ($finalsam == 1) {
					$gogo = explode("-", $chat_id);
					$oogy = $gogo[1];
					$samsam = $oogy . $replyUserId;
					$content = ['chat_id' => $chat_id, 'text' => "this user $replyUsername
						Received a warning Dear user, please follow the group order 😬
						Number of your warnings:
						It becomes 2/4.
						Thanks Robo Guard."];
					$telegram->sendMessage($content);
					$fekhtar = 2;
					$que = "UPDATE $dbtable SET ekhtar='$fekhtar' WHERE user=$samsam";
					mysqli_query($myconn, $que);
				}
			}
		} else {
			$content = ['chat_id' => $chat_id, 'text' => "$name You are not an admin 😐"];
			$telegram->sendMessage($content);
		}
	}
	if ($text == 'delete alert') {
		if ($join_status == 'administrator' || $join_status == 'creator') {
			$myconn = mysqli_connect("localhost", $dbuser, $dbpass, $dbname);
			$gogo = explode("-", $chat_id);
			$oogy = $gogo[1];
			$samsam = $oogy . $replyUserId;
			$query = "SELECT * FROM $dbtable WHERE user=$samsam";
			$res = mysqli_query($myconn, $query);
			$row = mysqli_fetch_row($res);
			if (!$row) {
				$gogo = explode("-", $chat_id);
				$oogy = $gogo[1];
				$samsam = $oogy . $replyUserId;
				$content = ['chat_id' => $chat_id, 'text' => "this user $replyUsername
					Dear user, you have no warning 😬
					Number of your warnings:
					It becomes 0/4.
					Thanks Robo Guard."];
				$telegram->sendMessage($content);
			} else {
				$gogo = explode("-", $chat_id);
				$oogy = $gogo[1];
				$samsam = $oogy . $replyUserId;
				$query2 = "SELECT * FROM $dbtable WHERE user=$samsam";
				$res2 = mysqli_query($myconn, $query2);
				$row2 = mysqli_fetch_row($res2);
				$finalsam = $row2[2];
				if ($finalsam == 1) {
					$gogo = explode("-", $chat_id);
					$oogy = $gogo[1];
					$samsam = $oogy . $replyUserId;
					$content = ['chat_id' => $chat_id, 'text' => "this user $replyUsername
						A warning has been deleted. Dear user, Thank you for following the group 😬
						Number of your warnings:
						It becomes 0/4.
						Thanks Robo Guard."];
					$telegram->sendMessage($content);
					$que = "DELETE FROM $dbtable WHERE user=$samsam";
					mysqli_query($myconn, $que);
				}
				if ($finalsam == 2) {
					$gogo = explode("-", $chat_id);
					$oogy = $gogo[1];
					$samsam = $oogy . $replyUserId;
					$content = ['chat_id' => $chat_id, 'text' => "this user $replyUsername
						A warning has been deleted. Dear user, Thank you for following the group 😬
						Number of your warnings:
						It becomes 1/4.
						Thanks Robo Guard."];
					$telegram->sendMessage($content);
					$fekhtar = 1;
					$que = "UPDATE $dbtable SET ekhtar='$fekhtar' WHERE user=$samsam";
					mysqli_query($myconn, $que);
				}
				if ($finalsam == 3) {
					$gogo = explode("-", $chat_id);
					$oogy = $gogo[1];
					$samsam = $oogy . $replyUserId;
					$content = ['chat_id' => $chat_id, 'text' => "this user $replyUsername
						A warning has been deleted. Dear user, Thank you for following the group 😬
						Number of your warnings:
						It becomes 2/4.
						Thanks Robo Guard."];
					$telegram->sendMessage($content);
					$fekhtar = 2;
					$que = "UPDATE $dbtable SET ekhtar='$fekhtar' WHERE user=$samsam";
					mysqli_query($myconn, $que);
				}
			}
		} else {
			$content = ['chat_id' => $chat_id, 'text' => "$name You are not an admin 😐"];
			$telegram->sendMessage($content);
		}
	}
}


if (
	$msgType == 'photo' ||
	$msgType == 'video' ||
	$msgType == 'animation' ||
	$msgType == 'document' &&
	!empty($caption)
) {
	$text = $telegram->Caption();
}


if ($msgType == 'message' || $msgType == 'photo' || $msgType == 'video' || $msgType == 'animation' || $msgType == 'document') {
	if ($channel_or_group_links_delete) {
		$pattern = "/(?<!\w)@\w+/";
		if (preg_match($pattern, $text)) {

			if ($join_status == 'administrator' || $join_status == 'creator') {
			} else {
				$content = ['chat_id' => $chat_id, 'message_id' => $message_id];
				$telegram->deleteMessage($content);
			}
		}
	}
}

if ($msgType == 'message' || $msgType == 'photo' || $msgType == 'video' || $msgType == 'animation' || $msgType == 'document') {
	if ($rude_message) {
		for ($fffc = 0; $fffc < $rude_counter; $fffc++) {
			$fxsam = $rude_words[$fffc];
			$pattern = "/$fxsam/";
			if (preg_match($pattern, $text)) {

				if ($join_status == 'administrator' || $join_status == 'creator') {
				} else {
					$content = ['chat_id' => $chat_id, 'message_id' => $message_id];
					$telegram->deleteMessage($content);
				}
			}
		}
	}
}

if ($msgType == 'forwarded') {
	if ($join_status == 'administrator' || $join_status == 'creator') {
	} else {
		$content = ['chat_id' => $chat_id, 'message_id' => $message_id];
		$telegram->deleteMessage($content);
	}
}
if ($msgType == 'message' || $msgType == 'photo' || $msgType == 'video' || $msgType == 'animation' || $msgType == 'document') {
	if ($Dedicate_the_group[0]) {
		if ($chat_id == $Dedicate_the_group[1]) {
		} else {
			$content = ['chat_id' => $chat_id, 'text' => "We're sorry, but this robot in your group can not do anything.
			Bye"];
			$telegram->sendMessage($content);
			$content = ['chat_id' => $chat_id, 'user_id' => $Dedicate_the_group[2]];
			$telegram->kickChatMember($content);
		}
	}
}

if ($msgType == 'message' || $msgType == 'photo' || $msgType == 'video' || $msgType == 'animation' || $msgType == 'document') {
	if ($sites_links_delete) {
		$pattern = "/((((http|https|ftp|ftps)\:\/\/)|www\.)?[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,4}(\/\S*)?)/i";
		if (preg_match($pattern, $text)) {
			if ($join_status == 'administrator' || $join_status == 'creator') {
			} else {
				$content = ['chat_id' => $chat_id, 'message_id' => $message_id];
				$telegram->deleteMessage($content);
			}
		}
	}
}
