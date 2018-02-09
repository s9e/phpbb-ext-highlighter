<?php

$extPath = __DIR__ . '/../ext/highlight.js/';

$map = [];
foreach (glob($extPath . 'src/languages/*.js') as $filepath)
{
	$file = file_get_contents($filepath);
	$lang = basename($filepath, '.js');
	if (preg_match('(category:\\s*common)i', $file))
	{
		// Skip default languages
		continue;
	}
	$map[$lang] = $lang;

	if (!preg_match('(aliases:\\s*\\[([^]]++))', $file, $m))
	{
		continue;
	}

	preg_match_all("('([^']++)')", strtolower($m[1]), $m);
	foreach ($m[1] as $alias)
	{
		$map[$alias] = $lang;
	}
}
ksort($map);

$package = json_decode(file_get_contents($extPath . 'package.json'));
$version = $package->version;

$filepath = realpath(__DIR__ . '/../s9e/highlighter/styles/all/template/init.js');

$js = file_get_contents($filepath);
$js = preg_replace('(highlight.js/\\K\\d+\\.\\d+\\.\\d+)', $version, $js, -1, $cnt);
if (!$cnt)
{
	die("Cannot patch $filepath\n");
}

$js = preg_replace_callback(
	'((map\\s*=\\s*)\\{.*\\})',
	function ($m) use ($map)
	{
		return $m[1] . json_encode($map);
	},
	$js
);

file_put_contents($filepath, $js);
die("Done.\n");