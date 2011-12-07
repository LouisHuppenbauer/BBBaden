<?php

require_once('class.Session.php');

	$session = new Session();

	$session->start();

//	$session->set('name','louis');
	echo $session->get('name');

	$session->save();
