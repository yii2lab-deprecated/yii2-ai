<?php

namespace yii2lab\ai\game\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii2lab\domain\BaseEntity;

class ValidateFilter extends Behavior {
	
	public function events() {
		return [
			BaseEntity::EVENT_AFTER_VALIDATE => 'afterValidate',
		];
	}
	
	public function afterValidate(Event $event) {
		/** @var BaseEntity $entity */
		$entity = $event->sender;
		foreach($entity->attributes() as $attribute) {
			$value = $entity->{$attribute};
			if(is_object($value) && method_exists($value, 'validate')) {
				$value->validate();
			}
		}
	}
	
}
