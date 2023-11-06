<?php

declare (strict_types=1);

namespace smf\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\{Expr, Identifier, Name\FullyQualified, Scalar};
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class InfoVarsToClassVarsArrayAccess extends AbstractRector
{
	public function getRuleDefinition(): RuleDefinition
	{
		return new RuleDefinition('Remove loop with no body', [new CodeSample(<<<'CODE_SAMPLE'
$options['sample']
CODE_SAMPLE
, <<<'CODE_SAMPLE'
Theme::$current->options['sample']
CODE_SAMPLE
)]);
	}

	public function getNodeTypes(): array
	{
		return [Expr\ArrayDimFetch::class];
	}

	public function refactor(Node $node): ?Node
	{
		// array keys are fully qualified class names
		// array values are associative arrays where keys are globals, values are class vars
		$map = [
			'SMF\Theme' => ['options' => 'current', 'settings' => 'current'],
		];
		foreach ($map as $fqcn => $vals)
			foreach ($vals as $global => $class_var)
			if ($node->var instanceof Expr\Variable && $node->var->name == $global && $node->dim instanceof Scalar\String_)
				return new Expr\ArrayDimFetch(new Expr\PropertyFetch(new Expr\StaticPropertyFetch(new FullyQualified($fqcn), new Identifier($class_var)), new Identifier($global)), $node->dim);

		return null;
	}
}