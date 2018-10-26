<?php

namespace yii2lab\ai\domain\interfaces\services;

use yii2lab\domain\interfaces\services\CrudInterface;

/**
 * Interface TrainInterface
 * 
 * @package yii2lab\ai\domain\interfaces\services
 * 
 * @property-read \yii2lab\ai\domain\Domain $domain
 * @property-read \yii2lab\ai\domain\interfaces\repositories\TrainInterface $repository
 */
interface TrainInterface extends CrudInterface {
	
	public function allTrain($botId, $trainPercent = 100);
	public function allTest($botId, $trainPercent = 100);
	
}
