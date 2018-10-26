<?php

namespace yii2lab\ai\domain\entities;

use yii2lab\domain\BaseEntity;

/**
 * Class TrainEntity
 * 
 * @package yii2lab\ai\domain\entities
 * 
 * @property $id
 * @property $class_id
 * @property $is_enabled
 * @property $value
 */
class TrainEntity extends BaseEntity {

	protected $id;
	protected $class_id;
	protected $hash;
	protected $is_enabled;
	protected $value;

	public function getHash() {
		$str = serialize([$this->class_id, $this->value]);
		return hash('crc32b', $str);
	}
	
}
