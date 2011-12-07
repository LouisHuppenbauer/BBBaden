<?php
/**
 * @todo dynamize user-id (do not use 1 and 2...)
 * @todo add simple ai (just choose a random field)
 * @todo add check if game has been won
 * @todo
 */

require_once('/domains/huppi.info/subdomains/pathfinder/silex.phar');
require_once('lib/post.php');
require_once('lib/get.php');

define('GAME_URL', 'http://huppi.info/bbbaden/tic/server/');

$app = new Silex\Application();

session_start();

$app->get('/', function() {
	return 'This is a Tic Tac Toe client';
});

$app->get('/move/{player}/{x}/{y}', function($player, $x, $y) use ($app) {
	post(GAME_URL.'putMove/'.$_SESSION['gameID'].'/'.$player.'/'.$x.'/'.$y);
	return $app->redirect('/bbbaden/tic/client/play/'.$player);
});

$app->get('/resetGame', function() {
	unset($_SESSION['gameID']);
});

$app->get('/play/{player}', function($player) {
	if(empty($_SESSION['gameID'])) {
        	$ret = get(GAME_URL.'startNewGame');

	        if($ret['code'] == 200) {
        	        $_SESSION['gameID'] = $ret['id'];
	        }
        	else {
                	exit("Could not start a new game. I'll better stop now!");
	        }
	}
	if(empty($player)) {
		exit("Please specify a player first");
	}

	$id = $_SESSION['gameID'];
	$moves = get(GAME_URL.'getMoves/'.$id);
	$orderedMoves = array();
	//make them moves useable
	if(!empty($moves['moves'])) {
		foreach($moves['moves'] AS $move) {
			$orderedMoves[$move['x']][$move['y']] = $move['player'];
		}
	}
	echo '<table style="border-style:solid">';
	for($x = 3; $x > 0; $x--) {
		echo '<tr style="border-style:solid">';
		for($y = 1; $y < 4; $y++) {
			if(empty($orderedMoves[$x][$y])) {
				$put_it = '<a href = "http://huppi.info/bbbaden/tic/client/move/'.$player.'/'.$x.'/'.$y.'">LINK</a>';
			}
			else {
				$put_it = $orderedMoves[$x][$y] == 1 ? 'BLACK' : 'WHITE';
			}	
			echo '<td style="border-style:solid; border-width:1px;">'.$put_it.'</td>';
		}
		echo '</tr>';
	} 
	echo '</table>';
	echo '<br /><br />';
	$req = get(GAME_URL.'getLastPlayer/'.$_SESSION['gameID']);
	if($req['player'] != $player) {
		echo '<strong>It\'s your turn!</strong>';
	}
	else {
		echo 'It\'s <strong>NOT</strong> your turn!';
	}
})
->value('player', 0);

$app->run();
