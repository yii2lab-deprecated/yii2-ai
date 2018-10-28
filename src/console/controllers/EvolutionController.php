<?php

namespace yii2lab\ai\console\controllers;

use yii2lab\ai\game\entities\UnitCellEntity;
use yii2lab\ai\game\factories\UnitFactory;
use yii2lab\ai\game\helpers\Matrix;
use yii2lab\ai\game\helpers\MatrixHelper;
use yii2lab\extension\console\base\Controller;
use yii2lab\extension\console\helpers\Output;

class EvolutionController extends Controller {
	
	private $usleep = 80000;
	
	public function actionIndex() {
		$size = 23;
		$matrix = UnitFactory::createMatrix($size);
		UnitFactory::createWalls($matrix);
		UnitFactory::createFoods($matrix);
		UnitFactory::createToxic($matrix);
		$unitCollection = UnitFactory::createUnits($matrix);
		$this->renderMatrix($matrix);
		do {
			$this->stepAllUnit($unitCollection);
			$info = $this->getUnitsInfo($unitCollection);
			$this->renderMatrix($matrix, PHP_EOL . implode(PHP_EOL, $info));
		} while($info);
		Output::block('Game over!');
	}
	
	private function getUnitsInfo($unitCollection) {
		$info = [];
		/** @var UnitCellEntity[] $unitCollection */
		foreach($unitCollection as $k => $unitEntity) {
			if(!$unitEntity->isDead()) {
				$info[] = 'unit '.$k.': ' . $unitEntity->energy;
			}
		}
		return $info;
	}
	
	private function stepAllUnit($unitCollection) {
		foreach($unitCollection as $unitEntity) {
			$this->stepUnit($unitEntity);
		}
	}
	
	private function stepUnit(UnitCellEntity $unitEntity) {
		$wantCell = $unitEntity->wantCell();
		if($wantCell) {
			$unitEntity->matrix->moveCellEntity($unitEntity, $wantCell);
		}
	}
	
	private function renderMatrix(Matrix $matrix, $desc = '') {
		$text = MatrixHelper::generateMatrix($matrix);
		$text .= $desc;
		Output::render($text);
		usleep($this->usleep);
	}
	
}
