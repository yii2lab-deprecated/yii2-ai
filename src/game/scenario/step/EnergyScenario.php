<?php

namespace yii2lab\ai\game\scenario\step;

use yii2lab\ai\game\entities\CellEntity;
use yii2lab\ai\game\entities\FoodCellEntity;
use yii2lab\ai\game\entities\ToxicCellEntity;
use yii2lab\ai\game\entities\UnitCellEntity;
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
		if($toCellEntity instanceof FoodCellEntity) {
			/** @var UnitCellEntity $fromCellEntity */
			$fromCellEntity->upEnergy($toCellEntity->energy);
		}
		if($toCellEntity instanceof ToxicCellEntity) {
			$fromCellEntity->downEnergy($toCellEntity->energy);
		}
	}
	
}
