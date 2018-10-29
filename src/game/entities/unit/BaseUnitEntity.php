<?php

namespace yii2lab\ai\game\entities\unit;

use yii2lab\ai\game\behaviors\ValidateFilter;
use yii2lab\ai\game\entities\PointEntity;
use yii2lab\ai\game\helpers\Matrix;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\exceptions\ReadOnlyException;
use yii2lab\extension\common\helpers\ClassHelper;

/**
 * Class BaseUnitEntity
 *
 * @package yii2lab\ai\game\entities\unit
 *
 * @property PointEntity $point
 * @property Matrix $matrix
 */
abstract class BaseUnitEntity extends BaseEntity {
	
	const EVENT_AFTER_MOVE = 'EVENT_AFTER_MOVE';
	const EVENT_BEFORE_MOVE = 'EVENT_BEFORE_MOVE';
	
	const EVENT_AFTER_OVERLAY = 'EVENT_AFTER_OVERLAY';
	const EVENT_BEFORE_OVERLAY = 'EVENT_BEFORE_OVERLAY';
	
	protected $point;
	private $matrix;
	
	public function behaviors() {
		return [
			ValidateFilter::class,
		];
	}
	
	public function fieldType() {
		return [
			'color' => 'integer',
			'point' => PointEntity::class,
		];
	}
	
	public function rules() {
		return [
			[['matrix', 'point'], 'required'],
		];
	}
	
	public function beforeOverlayTrigger() {
		$this->trigger(self::EVENT_BEFORE_OVERLAY);
	}
	
	public function afterOverlayTrigger() {
		$this->trigger(self::EVENT_AFTER_OVERLAY);
	}
	
	public function beforeMoveTrigger() {
		$this->trigger(self::EVENT_BEFORE_MOVE);
	}
	
	public function afterMoveTrigger() {
		$this->trigger(self::EVENT_AFTER_MOVE);
	}
	
	protected function getMatrix() {
		return $this->matrix;
	}
	
	public function setMatrix($value) {
		if(isset($this->matrix)) {
			throw new ReadOnlyException('Matrix already assigned!');
		}
		ClassHelper::isInstanceOf($value, Matrix::class);
		$this->matrix = $value;
	}
	
}
