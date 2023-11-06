<?php

declare (strict_types=1);

namespace smf\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\{Expr, Name\FullyQualified, Stmt};
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RenameBirthdayEmailsTxttars extends AbstractRector
{
	public function getNodeTypes(): array
	{
		return [Expr\Variable::class];
	}

	public function getRuleDefinition(): RuleDefinition
	{
		return new RuleDefinition(
			'rename $birthdayEmails to $txtBirthdayEmails',
			[]
		);
	}

	public function refactor(Node $node): ?Node
	{
		if (!$this->isName($node, 'birthdayEmails'))
			// return null to skip it
			return null;

		$node->name = 'txtBirthdayEmails';
		return $node;
	}
}