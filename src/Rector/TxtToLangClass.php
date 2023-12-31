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

final class TxtToLangClass extends AbstractRector
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
		if ($parentNode instanceof Stmt\Global_)
			return null;

		$globals = ['txt', 'tztxt', 'editortxt', 'helptxt', 'txtBirthdayEmails', 'forum_copyright']
		if (in_array($node->name, $globals))
		{
			// Try to ignore language files, which involves some extra work.
			// $txt entries may declare any number of array dimensions, which
			// are rerpresented in the AST generated by nikic/PHP-Parser as
			// multiple nested levels of PhpParser\Node\Expr\ArrayDimFetch.
			while ($parentNode instanceof Expr\ArrayDimFetch)
				$parentNode = $parentNode->getAttribute(AttributeKey::PARENT_NODE);

			if ($parentNode instanceof Expr\Assign && $parentNode->getAttribute(AttributeKey::EXPRESSION_DEPTH) === 0)
				return null;
		}

		return new Expr\StaticPropertyFetch(new FullyQualified('SMF\Lang'), $node->name);
	}
}