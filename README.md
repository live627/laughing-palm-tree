# smf Rector

This project contains [Rector rules](https://github.com/rectorphp/rector) for [smf CMS](https://github.com/smf/smf) upgrades.

## Install

Install rector and smf rector via composer to your project:

```bash
composer require rector/rector --dev
composer require smf/smf-rector --dev
```

## Use Sets

To add a set to your config, use `smf\Rector\Set\SymfonySetList` and `smf\Rector\Set\smfLevelSetList`
class and pick one of constants:

```php
use smf\Rector\Set\smfLevelSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
	$rectorConfig->sets([
		smfLevelSetList::UP_TO_smf_25,
	]);
};
```
