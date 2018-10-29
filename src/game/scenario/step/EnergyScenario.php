<?php

namespace yii2lab\ai\game\scenario\step;

use yii2lab\ai\game\entities\CellEntity;
use yii2lab\ai\game\entities\FoodEntity;
use yii2lab\ai\game\entities\ToxicEntity;
use yii2lab\ai\game\entities\UnitEntity;
use yii2lab\ai\game\events\MoveEvent;
use yii2lab\extension\scenario\base\BaseScenario;

/**
 * Class EnergyScenario
 *
 * @package yii2lab\ai\game\scenario\step
 * @property MoveEvent $event
 */
class EnergyScenario extends BaseScenario {
	
	public function run() {
		$this->onMove($this->event->fromCellEntity, $this->event->toCellEntity);
	}
	
	private function onMove(CellEntity $fromCellEntity, CellEntity $toCellEntity) {
		if($toCellEntity instanceof FoodEntity) {
			/** @var UnitEntity $fromCellEntity */
			$fromCellEntity->upEnergy($toCellEntity->energy);
		}
		if($toCellEntity instanceof ToxicEntity) {
			$fromCellEntity->downEnergy($toCellEntity->energy);
		}
	}
	
}
