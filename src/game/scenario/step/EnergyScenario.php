<?php

namespace yii2lab\ai\game\scenario\step;

use yii2lab\ai\game\entities\unit\BaseUnitEntity;
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
		$this->onMove($this->event->fromBaseUnitEntity, $this->event->toBaseUnitEntity);
	}
	
	private function onMove(BaseUnitEntity $fromBaseUnitEntity, BaseUnitEntity $toBaseUnitEntity) {
		if($toBaseUnitEntity instanceof FoodEntity) {
			/** @var BotEntity $fromBaseUnitEntity */
			$fromBaseUnitEntity->upEnergy($toBaseUnitEntity->energy);
		} elseif($toBaseUnitEntity instanceof ToxicEntity) {
			$fromBaseUnitEntity->downEnergy($toBaseUnitEntity->energy);
		}
		$fromBaseUnitEntity->downEnergy(5);
	}
	
}
