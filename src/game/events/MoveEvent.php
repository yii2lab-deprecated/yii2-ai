<?php

namespace yii2lab\ai\game\events;

use yii\base\BaseObject;
use yii2lab\ai\game\entities\CellEntity;

class MoveEvent extends BaseObject {
	
	/**
	 * @var CellEntity
	 */
	public $fromCellEntity;
	
	/**
	 * @var CellEntity
	 */
	public $toCellEntity;
	
}
