<?php

declare (strict_types=1);

namespace smf\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\{Expr, Scalar};
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class TopicInfoToContext extends AbstractRector
{
	public function getRuleDefinition(): RuleDefinition
	{
		return new RuleDefinition('Remove loop with no body', [new CodeSample(<<<'CODE_SAMPLE'
$topicinfo['sample']
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$context['topicinfo']['sample']
CODE_SAMPLE
)]);
	}

	public function getNodeTypes(): array
	{
		return [Expr\ArrayDimFetch::class];
	}

	public function refactor(Node $node): ?Node
	{
		if ($node->var->name == 'topicinfo')
			return new Expr\ArrayDimFetch(new Expr\ArrayDimFetch(new Expr\Variable('context'), new Scalar\String_('topicinfo')), $node->dim);

		return null;
	}
}