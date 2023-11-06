<?php

declare (strict_types=1);

namespace smf\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\{Expr, Identifier, Name\FullyQualified, Scalar};
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class InfoVarsToClassVars extends AbstractRector
{
	public function getRuleDefinition(): RuleDefinition
	{
		return new RuleDefinition('Remove loop with no body', [new CodeSample(<<<'CODE_SAMPLE'
$user_info['sample']
$context['topicinfo']['sample']
$settings['sample']
CODE_SAMPLE
, <<<'CODE_SAMPLE'
User::$me->sample
Topic::$info->sample
Theme::$current->settings['sample']
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
			'object_access' => [
				'SMF\Board' => ['board_info' => 'info'],
				'SMF\Topic' => ['topicinfo' => 'info'],
				'SMF\User' => ['user_info' => 'me'],
			],
			'array_access' => [
				'SMF\Theme' => ['options' => 'current', 'settings' => 'current'],
			],
		];

		// Convert $context['topicinfo']['sample'] to Topic::$info->sample
		foreach ($map['object_access'] as $fqcn => $vals)
			foreach ($vals as $old_global => $class_var)
			if ($node->var instanceof Expr\Variable && $node->var->name == $old_global && $node->dim instanceof Scalar\String_)
				return new Expr\PropertyFetch(new Expr\StaticPropertyFetch(new FullyQualified($fqcn), new Identifier($class_var)), new Identifier($node->dim->value));

		// Convert $context['topicinfo']['sample'] to Topic::$info->sample
		if ($node->var instanceof Expr\ArrayDimFetch && $node->var->var instanceof Expr\Variable && $node->var->var->name == 'context' && $node->var->dim instanceof Scalar\String_ && $node->var->dim->value == 'topicinfo')
			return new Expr\PropertyFetch(new Expr\StaticPropertyFetch(new FullyQualified('SMF\Topic'), new Identifier('info')), new Identifier($node->var->dim->value));

		// Convert $settings['sample'] to Theme::$current->settings['sample']
		foreach ($map['array_access'] as $fqcn => $vals)
			foreach ($vals as $global => $class_var)
			if ($node->var instanceof Expr\Variable && $node->var->name == $global && $node->dim instanceof Scalar\String_)
				return new Expr\ArrayDimFetch(new Expr\PropertyFetch(new Expr\StaticPropertyFetch(new FullyQualified($fqcn), new Identifier($class_var)), new Identifier($global)), $node->dim);

		return null;
	}
}