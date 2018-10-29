<?php

namespace yii2lab\ai\game\scenario\step;

use yii2lab\ai\game\events\MoveEvent;
use yii2lab\extension\scenario\base\BaseScenario;
use yii2lab\extension\scenario\exceptions\StopException;

/**
 * Class IsPossibleScenario
 *
 * @package yii2lab\ai\game\scenario\step
 * @property MoveEvent $event
 */
class IsPossibleScenario extends BaseScenario {
	
	public function run() {
		$isCanReplace = $this->event->toCellEntity->isCanReplace();
		if(!$isCanReplace) {
			throw new StopException;
		}
	}
	
}
