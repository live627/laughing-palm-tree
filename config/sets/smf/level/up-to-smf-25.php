<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use smf\Rector\Set\smfLevelSetList;
use smf\Rector\Set\smfSetList;

return static function (RectorConfig $rectorConfig): void {
	$rectorConfig->sets([smfSetList::smf_25, smfLevelSetList::UP_TO_smf_24]);
};
