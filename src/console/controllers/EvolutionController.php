<?php

namespace yii2lab\ai\console\controllers;

use yii2lab\ai\console\commands\ConsoleRender;
use yii2lab\ai\game\helpers\Game;
use yii2lab\extension\console\base\Controller;
use yii2lab\extension\console\helpers\Output;

class EvolutionController extends Controller {
	
	public function actionIndex() {
		$height = 20;
		$width = 20;
		$game = new Game($height, $width);
		$game->setRender(ConsoleRender::class);
		$game->run(5);
		Output::block('Game over!');
	}
	
}
