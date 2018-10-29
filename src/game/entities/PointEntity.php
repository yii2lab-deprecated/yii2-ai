<?php

namespace yii2lab\ai\game\entities;

use yii2lab\domain\BaseEntity;

/**
 * Class BaseUnitEntity
 *
 * @package yii2lab\ai\game\entities\unit
 *
 * @property $x
 * @property $y
 */
class PointEntity extends BaseEntity {
	
	protected $x;
	protected $y;

	public function fieldType() {
		return [
			'x' => 'integer',
			'y' => 'integer',
		];
	}
	
	public function rules() {
		return [
			[['x', 'y'], 'required'],
			[['x', 'y'], 'integer'],
		];
	}
	
}
