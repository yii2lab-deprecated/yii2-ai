<?php

namespace yii2lab\ai\game\scenario\factory\unit;

use yii2lab\ai\game\entities\UnitCellEntity;
use yii2lab\ai\game\events\UnitEvent;
use yii2lab\extension\scenario\base\BaseScenario;

/**
 * Class CreateUnitScenario
 *
 * @package yii2lab\ai\game\scenario\factory
 * @property UnitEvent $event
 */
class CreateUnitScenario extends BaseScenario {
	
	public $logicClass;
	
	public function run() {
		$this->event->unitCellEntity = new UnitCellEntity();
		$this->event->matrix->setCellByPoint($this->event->pointEntity, $this->event->unitCellEntity);
	}
	
}
