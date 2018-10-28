<?php

namespace yii2lab\ai\game\entities;

use yii2lab\ai\game\enums\ColorEnum;

/**
 * Class ToxicCellEntity
 *
 * @package yii2lab\ai\game\entities
 *
 * @property $energy
 */
class ToxicCellEntity extends CellEntity {

	public $color = ColorEnum::RED;
	protected $energy = -2;
	
	public function isCanReplace() {
		return true;
	}
	
	/*public function getContent() {
		return $this->energy;
	}*/
	
}
