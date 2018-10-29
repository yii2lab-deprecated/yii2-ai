<?php

namespace yii2lab\ai\game\helpers\bot;

use yii2lab\ai\game\entities\CellEntity;
use yii2lab\ai\game\entities\FoodCellEntity;
use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\entities\UnitCellEntity;
use yii2lab\ai\game\helpers\PossibleHelper;
use yii2lab\ai\game\interfaces\BotLogicInterface;

class StepLogic implements BotLogicInterface {
	
	public function getPoint(UnitCellEntity $unitCellEntity) {
		$possibles = $this->getPossibles($unitCellEntity);
		/** @var PointEntity $pointEntity */
		$pointEntity = $this->seekFoodPoint($possibles);
		if(empty($pointEntity)) {
			$pointEntity = $this->randPoint($possibles);
		}
		return $pointEntity;
	}
	
	private function getPossibles(UnitCellEntity $unitCellEntity) {
		$map = $unitCellEntity->matrix->getCellsByPoint($unitCellEntity->point);
		$possibles = PossibleHelper::getPossibles($map);
		return $possibles;
	}
	
	private function seekFoodPoint($possibles) {
		$possibleCellEntity = null;
		foreach($possibles as $cellEntity) {
			if($cellEntity instanceof FoodCellEntity) {
				if($possibleCellEntity == null) {
					$possibleCellEntity = $cellEntity;
				} elseif($cellEntity->energy > $possibleCellEntity->energy) {
					$possibleCellEntity = $cellEntity;
				}
			}
		}
		return $possibleCellEntity->point;
	}
	
	private function randPoint($possibles) {
		$randIndex = mt_rand(0, count($possibles) - 1);
		/** @var CellEntity[] $possibles */
		$p = $possibles[ $randIndex ]->point;
		return $p;
	}
	
}