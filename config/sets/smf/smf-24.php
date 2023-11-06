<?php

return static function (\Rector\Config\RectorConfig $rectorConfig): void
{
	$rectorConfig->rule(smf\Rector\Rector\RenameBirthdayEmailsTxttars::class);
	$rectorConfig->rule(smf\Rector\Rector\TopicInfoToContext::class);
};
