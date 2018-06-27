<?php

if (! function_exists('world_config'))
{
    function world_config()
	{
		$countries = [];
		$dir = __DIR__.'/../../config/wobe';
		foreach (scandir($dir) as $countryFile) {
			if (is_file($dir.'/'.$countryFile)) {
				list($key, $ext) = explode('.', $countryFile);
				$countries[$key] = require($dir.'/'.$countryFile);
			}
		}
		return $countries;
	}
}