<?php

namespace yii2lab\ai\game\interfaces;

use yii2lab\ai\game\entities\UnitCellEntity;

interface BotLogicInterface {
	
	public function getPoint(UnitCellEntity $unitCellEntity);
	
}