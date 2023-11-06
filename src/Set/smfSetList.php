<?php

declare(strict_types=1);

namespace smf\Rector\Set;

use Rector\Set\Contract\SetListInterface;

final class smfSetList implements SetListInterface
{
	/**
	 * @var string
	 */
	final public const smf_24 = __DIR__ . '/../../config/sets/smf/smf-24.php';

	/**
	 * @var string
	 */
	final public const smf_25 = __DIR__ . '/../../config/sets/smf/smf-25.php';
}
