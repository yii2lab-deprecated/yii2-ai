<?php

namespace yii2lab\ai\domain\models;

class FeatureBasedNB extends \NlpTools\Models\FeatureBasedNB {
	
	public function getClasses() {
		return array_keys($this->priors);
	}
	
}
