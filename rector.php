<?php

declare(strict_types=1);
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;

$code = <<<'CODE'
<?php

		Utils::$context['num_views'] = Lang::numberFormat(Topic::$info->num_views);
	if ($topic != 0 && (allowedTo('view_topic_participants_any') || ($context['topicinfo']['sample']['sample']['sample'] == $user_info['id'] && allowedTo('view_topic_participants_own')))){}
CODE;

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$dumper = new NodeDumper;
echo $dumper->dump($ast) . "\n";

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use PhpParser\NodeVisitor\NodeConnectingVisitor;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

return static function (RectorConfig $rectorConfig): void {
	$rectorConfig->paths([__DIR__ . '/../smf-mods']);

	//~ $rectorConfig->sets([
		//~ LevelSetList::UP_TO_PHP_81,
		//~ SetList::CODE_QUALITY,
		//~ SetList::DEAD_CODE,
		//~ SetList::NAMING,
	//~ ]);

	//~ $rectorConfig->rule(smf\Rector\Rector\RemoveGlobals::class);
	//~ $rectorConfig->rule(smf\Rector\Rector\GlobalsToConfigClass::class);
	//~ $rectorConfig->rule(smf\Rector\Rector\BoardInfoToObject::class);
	$rectorConfig->rule(smf\Rector\Rector\InfoVarsToClassVarsArrayAccess::class);

	$rectorConfig->singleton(NodeConnectingVisitor::class);
	$rectorConfig->tag(NodeConnectingVisitor::class, ScopeResolverNodeVisitorInterface::class);
};
