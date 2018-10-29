<?php

namespace yii2lab\ai\game\entities\unit;

use yii2lab\ai\game\enums\ColorEnum;

/**
 * Class ToxicEntity
 *
 * @package yii2lab\ai\game\entities\unit
 *
 * @property $energy
 */
class ToxicEntity extends CellEntity {

	public $color = ColorEnum::RED;
	protected $energy = -2;
	
}
