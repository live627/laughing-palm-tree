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

final class GlobalsToConfigClass extends AbstractRector
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

		$globals = ['maintenance', 'mtitle', 'mmessage', 'mbname', 'language', 'boardurl', 'webmaster_email', 'cookiename', 'db_type', 'db_port', 'db_server', 'db_name', 'db_user', 'db_passwd', 'ssi_db_user', 'ssi_db_passwd', 'db_prefix', 'db_persist', 'db_error_send', 'db_mb4', 'cache_accelerator', 'cache_enable', 'cache_memcached', 'cachedir', 'cachedir_sqlite', 'image_proxy_enabled', 'image_proxy_secret', 'image_proxy_maxsize', 'boarddir', 'sourcedir', 'packagesdir', 'tasksdir', 'db_character_set', 'db_show_debug', 'db_last_error', 'modSettings', 'scripturl', 'auth_secret']
		if ($node->name ==  'txt'){
			while ($parentNode instanceof Expr\ArrayDimFetch)
				$parentNode = $parentNode->getAttribute(AttributeKey::PARENT_NODE);
		 //~ var_dump ($node->name,$parentNode->getAttribute(AttributeKey::EXPRESSION_DEPTH),$parentNode::class);}

			if ($parentNode instanceof Expr\Assign && $parentNode->getAttribute(AttributeKey::EXPRESSION_DEPTH) === 0)
				return null;
		}

		if (in_array($node->name, $globals))
			return new Expr\StaticPropertyFetch(new FullyQualified('SMF\Config'), $node->name);

		return null;
	}
}