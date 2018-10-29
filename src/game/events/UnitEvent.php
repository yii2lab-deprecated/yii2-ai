<?php

namespace yii2lab\ai\game\events;

use yii\base\BaseObject;
use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\entities\UnitCellEntity;
use yii2lab\ai\game\helpers\Matrix;

class UnitEvent extends BaseObject {
	
	/**
	 * @var UnitCellEntity
	 */
	public $unitCellEntity;
	
	/**
	 * @var Matrix
	 */
	public $matrix;
	
	/**
	 * @var PointEntity
	 */
	public $pointEntity;
	
}
