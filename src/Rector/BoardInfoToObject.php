<?php

declare (strict_types=1);

namespace smf\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\{Expr, Name\FullyQualified, Stmt};
use PhpParser\NodeTraverser;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class BoardInfoToObject extends AbstractRector
{
	public function getRuleDefinition(): RuleDefinition
	{
		return new RuleDefinition('Remove loop with no body', [new CodeSample(<<<'CODE_SAMPLE'
$modSettings['sample']
CODE_SAMPLE
, <<<'CODE_SAMPLE'
Config::$modSettings['sample']
CODE_SAMPLE
)]);
	}

	public function getNodeTypes(): array
	{
		return [Expr\Variable::class];
	}

	public function refactor(Node $node): ?Node
	{
		$parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
		//~ var_dump (array_keys($node->getAttributes()) );
		if ($parentNode instanceof Stmt\Global_)
			return null;

		if ($node->name == 'board_info')
			return new Expr\StaticPropertyFetch(new FullyQualified('SMF\Board'), new Expr\PropertyProperty($node, 'info'));

		if (in_array($node->name, ['board', 'boards']))
			return new Expr\StaticPropertyFetch(new FullyQualified('SMF\Board'), $node->name);

		return null;
	}
}