<?php

namespace yii2lab\ai\game\scenario\step;

use yii2lab\ai\game\entities\unit\CellEntity;
use yii2lab\ai\game\entities\unit\FoodEntity;
use yii2lab\ai\game\entities\unit\ToxicEntity;
use yii2lab\ai\game\entities\unit\BotEntity;
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
			/** @var BotEntity $fromCellEntity */
			$fromCellEntity->upEnergy($toCellEntity->energy);
		} elseif($toCellEntity instanceof ToxicEntity) {
			$fromCellEntity->downEnergy($toCellEntity->energy);
		}
		$fromCellEntity->downEnergy(5);
	}
	
}
