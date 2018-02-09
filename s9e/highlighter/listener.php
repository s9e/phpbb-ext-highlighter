<?php

/**
* @package   s9e\highlighter
* @copyright Copyright (c) 2015-2018 The s9e Authors
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
		if (!isset($configurator->tags['CODE']))
		{
			return;
		}

		$dom = $configurator->tags['CODE']->template->asDOM();
		foreach ($dom->getElementsByTagName('code') as $code)
		{
			$code->setAttribute('data-lang', '{@lang}');
		}
		$dom->saveChanges();
	}
}