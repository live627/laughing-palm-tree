<?php

declare (strict_types=1);

namespace smf\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt\Global_;
use PhpParser\NodeTraverser;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveGlobals extends AbstractRector
{
	public function getRuleDefinition(): RuleDefinition
	{
		return new RuleDefinition('Remove loop with no body', [new CodeSample(<<<'CODE_SAMPLE'
global context, myCustomglobal, modSettings, scripturl;
global topic, topicinfo, txt, user_info;
CODE_SAMPLE
, <<<'CODE_SAMPLE'
global myCustomglobal;
CODE_SAMPLE
)]);
	}

	public function getNodeTypes(): array
	{
		return [Global_::class];
	}

	public function refactor(Node $node): Node|int
	{
		$globals = ['maintenance', 'mtitle', 'mmessage', 'mbname', 'language', 'boardurl', 'webmaster_email', 'cookiename', 'db_type', 'db_port', 'db_server', 'db_name', 'db_user', 'db_passwd', 'ssi_db_user', 'ssi_db_passwd', 'db_prefix', 'db_persist', 'db_error_send', 'db_mb4', 'cache_accelerator', 'cache_enable', 'cache_memcached', 'cachedir', 'cachedir_sqlite', 'image_proxy_enabled', 'image_proxy_secret', 'image_proxy_maxsize', 'sourcedir', 'packagesdir', 'tasksdir', 'db_character_set', 'db_show_debug', 'db_last_error', 'auth_secret', 'board', 'boarddir', 'boardurl', 'context', 'memberContext', 'modSettings', 'scripturl', 'settings', 'smcFunc', 'topic', 'topicinfo', 'txt', 'tztxt', 'editortxt', 'helptxt', 'txtBirthdayEmails', 'forum_copyright', 'user_info'];
		$list = [];
		foreach ($node->vars as $var)
			if (!in_array($var->name, $globals))
				$list[] = $var;

		if ($list != [])
			return new Global_($list);
		else
			return NodeTraverser::REMOVE_NODE;
	}
}