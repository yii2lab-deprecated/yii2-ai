<?php

namespace yii2lab\ai\console\controllers;

use yii2lab\ai\game\helpers\Game;
use yii2lab\ai\game\helpers\MatrixHelper;
use yii2lab\extension\console\base\Controller;
use yii2lab\extension\console\helpers\Output;

class EvolutionController extends Controller {
	
	public function actionIndex() {
		$size = 23;
		$game = new Game($size);
		$game->setOutputHandler([$this, 'renderMatrix']);
		$game->run(4);
		Output::block('Game over!');
	}
	
	public function renderMatrix($matrix, $info) {
		$text = MatrixHelper::generateMatrix($matrix);
		$text .= PHP_EOL . implode(PHP_EOL, $info);
		Output::render($text);
	}
	
}
