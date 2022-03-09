<?php

namespace Inforge\HideGALite\Option;

use XF\Option\AbstractOption;
use XF\Util\File;

use function count;

class GALiteSource extends AbstractOption
{
	private const DEFAULT_PATH = 'js/nulumia/ga-lite/vendor/ga-lite/src/ga-lite.min.js';

	public static function verifyOption(&$value, \XF\Entity\Option $option)
	{
		$defaultValue = $option->default_value;

		if (empty(trim($value)))
			$value = $defaultValue;
		$value = trim($value);

		$prevValue = $option->getPreviousValue('option_value');
		if ($value == $prevValue)
			return true;
		if (strpos($value, 'http') !== false)
			return false;

		if ($value != $defaultValue && !File::copyFile($defaultValue, $value, false))
			return false;

		if ($prevValue == $defaultValue)
			return true;

		File::deleteDirectory(dirname($prevValue));
		for ($dir = dirname(dirname($prevValue));
			is_readable($dir) && is_dir($dir) && is_writable($dir) && count(scandir($dir)) == 2;
			$dir = dirname($dir))
			File::deleteDirectory($dir);

		return true;
	}
}
