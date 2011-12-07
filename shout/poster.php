<?php
	$posts = unserialize(file_get_contents('posts.txt'));
	$posts[] = array('id' => count($posts), 'message' => htmlentities($_POST['message']), 'timestamp' => time());
	file_put_contents('posts.txt', serialize($posts));
	
	echo json_encode($posts[count($posts)-1]);	
