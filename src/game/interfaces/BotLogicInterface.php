<?php

namespace yii2lab\ai\game\interfaces;

use yii2lab\ai\game\entities\UnitEntity;

interface BotLogicInterface {
	
	public function getPoint(UnitEntity $UnitEntity);
	
}