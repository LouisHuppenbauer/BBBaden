<?php
require_once('/domains/huppi.info/subdomains/pathfinder/silex.phar');
require_once('lib/response.php');

define('GAMES_DIR', 'games/');

$app = new Silex\Application(); 

$app->get('/', function() {
	return response(array('code' => 200, 'msg'=> 'This is a simple Tic Tac Toe REST API'));
});

$app->post('/putMove/{game}/{player}/{x}/{y}', function($game, $player, $x, $y) use($app) { 
	//serialize file {game}
	//add new move {x}{y} from {player}	

        if(file_exists(GAMES_DIR.$game.'.json')) {
                $game = json_decode(file_get_contents(GAMES_DIR.$game.'.json'), true);    
		//if player already moved - don't do anything!
		$lastMove = end($game['moves']);
		if((empty($lastMove) AND $player == 1) OR ($lastMove['player'] != $player)) {
			$game['moves'][] = array(
				'player' => $player,
				'x'	 => $x,
				'y'	 => $y,
			);
			file_put_contents(GAMES_DIR.$game['id'].'.json', json_encode($game));
			return $app->redirect('/bbbaden/tic/server/getMoves/'.$game['id']);
		}
		else {
			return response(array('code' => 500, 'msg' => 'Its not Player '.$player.'s turn yet.'));
		}
        }
        else {
                return response(array('code' => 404, 'msg' => 'Game with id '.$game.' not found'));
        }   
});

$app->get('/getLastPlayer/{game}', function($game) {
        if(file_exists(GAMES_DIR.$game.'.json')) {
                $game = json_decode(file_get_contents(GAMES_DIR.$game.'.json'), true);
		$lastMove = end($game['moves']);
		$player = empty($lastMove['player']) ? 2 : $lastMove['player'];
		return response(array('code' => 200, 'player' => $player));
        }
	else {
                return response(array('code' => 404, 'msg' => 'Game with id '.$game.' not found'));
        }
});

$app->get('/hasSomebodyWon/{game}', function($game) {

});

$app->get('/startNewGame', function() {
	$id = sha1(microtime());
	file_put_contents(GAMES_DIR.$id.'.json', json_encode(array('id' => $id, 'moves' => array())));
	return response(array('code' => 200, 'msg' => 'Game successfully created', 'id' => $id));
});

$app->get('/getMoves/{game}', function($game) {
	if(file_exists(GAMES_DIR.$game.'.json')) {
		$game = json_decode(file_get_contents(GAMES_DIR.$game.'.json'), true);	
		return response(array('code' => 200, 'msg' => 'Game found', 'moves' => $game['moves']));
	}
	else {
		return response(array('code' => 404, 'msg' => 'Game with id '.$game.' not found'));
	}	
});

$app->run(); 
