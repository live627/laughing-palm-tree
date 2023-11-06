<?php

declare(strict_types=1);

namespace smf\Rector\Set;

use Rector\Set\Contract\SetListInterface;

final class smfLevelSetList implements SetListInterface
{
	/**
	 * @var string
	 */
	final public const UP_TO_smf_24 = __DIR__ . '/../../config/sets/smf/level/up-to-smf-24.php';

	/**
	 * @var string
	 */
	final public const UP_TO_smf_25 = __DIR__ . '/../../config/sets/smf/level/up-to-smf-25.php';
}
