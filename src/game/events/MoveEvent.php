<?php

namespace yii2lab\ai\game\events;

use yii\base\BaseObject;
use yii2lab\ai\game\entities\unit\BaseUnitEntity;

class MoveEvent extends BaseObject {
	
	/**
	 * @var BaseUnitEntity
	 */
	public $fromBaseUnitEntity;
	
	/**
	 * @var BaseUnitEntity
	 */
	public $toBaseUnitEntity;
	
}
