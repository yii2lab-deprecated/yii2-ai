<?php

namespace yii2lab\ai\domain;

/**
 * Class Domain
 * 
 * @package yii2lab\ai\domain
 * @property-read \yii2lab\ai\domain\interfaces\services\{Entity}Interface ${entity}
 * @property \yii2lab\ai\domain\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yii2lab\ai\domain\interfaces\services\TrainInterface $train
 * @property-read \yii2lab\ai\domain\interfaces\repositories\RepositoriesInterface $repositories
 */
class Domain extends \yii2lab\domain\Domain {

	public function config() {
		return [
			'repositories' => [
				'{entity}',
			],
			'services' => [
				'{entity}',
			],
		];
	}

}