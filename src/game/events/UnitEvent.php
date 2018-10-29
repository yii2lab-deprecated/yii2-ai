<?php

namespace yii2lab\ai\game\events;

use yii\base\BaseObject;
use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\entities\unit\BotEntity;
use yii2lab\ai\game\helpers\Matrix;

class UnitEvent extends BaseObject {
	
	/**
	 * @var BotEntity
	 */
	public $botEntity;
	
	/**
	 * @var Matrix
	 */
	public $matrix;
	
	/**
	 * @var PointEntity
	 */
	public $pointEntity;
	
}
