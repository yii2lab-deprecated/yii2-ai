<?php

namespace yii2lab\ai\game\scenario\step;

use yii2lab\ai\game\entities\CellEntity;
use yii2lab\ai\game\entities\FoodCellEntity;
use yii2lab\ai\game\entities\UnitCellEntity;
use yii2lab\extension\scenario\base\BaseScenario;

class FoodScenario extends BaseScenario {
	
	public function run() {
		$data = $this->getData();
		$this->onMove($data['fromCellEntity'], $data['toCellEntity']);
	}
	
	private function onMove(UnitCellEntity $fromCellEntity, CellEntity $toCellEntity) {
		if($toCellEntity instanceof FoodCellEntity) {
			$fromCellEntity->upEnergy($toCellEntity->energy);
		} else {
			$fromCellEntity->downEnergy(5);
		}
	}
	
}
