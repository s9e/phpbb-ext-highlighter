<?php

/**
* @package   s9e\highlighter
* @copyright Copyright (c) 2015-2020 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\highlighter;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return ['core.text_formatter_s9e_configure_after' => 'onConfigure'];
	}

	public function onConfigure($event)
	{
		$configurator = $event['configurator'];
		if (!isset($configurator->tags['CODE'], $configurator->BBCodes['CODE']))
		{
			return;
		}

		$configurator->BBCodes['CODE']->defaultAttribute = 'lang';
		if (!isset($configurator->tags['CODE']->attributes['lang']))
		{
			$attribute = $configurator->tags['CODE']->attributes->add('lang');
			$attribute->required = false;
			$attribute->filterChain->append('#identifier');
		}

		$attribute = $configurator->tags['CODE']->attributes['lang'];
		$attribute->filterChain->prepend('#map')->setMap(
			[
				'c#'  => 'csharp',
				'c++' => 'cpp',
				'f#'  => 'fsharp'
			],
			false
		);
		$attribute->filterChain->prepend('strtolower');

		$dom = $configurator->tags['CODE']->template->asDOM();
		foreach ($dom->getElementsByTagName('code') as $code)
		{
			$code->setAttribute('class', trim($code->getAttribute('class') . ' language-{@lang}'));
		}
		foreach ($dom->getElementsByTagName('pre') as $pre)
		{
			$pre->setAttribute('data-s9e-livepreview-hash', '');
			$pre->setAttribute('data-s9e-livepreview-onupdate', "if(typeof hljsLoader!=='undefined')hljsLoader.highlightBlocks(this)");
		}
		$dom->saveChanges();
	}
}