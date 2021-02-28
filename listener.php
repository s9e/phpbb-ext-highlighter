<?php

/**
* @package   s9e\highlighter
* @copyright Copyright (c) 2015-2021 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\highlighter;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbb\cache\service as cache;
use phpbb\template\twig\twig;
use s9e\TextFormatter\Bundles\Forum;

class listener implements EventSubscriberInterface
{
	protected $cache;
	protected $hasCode = false;
	protected $template;

	public function __construct(cache $cache, twig $template)
	{
		$this->cache    = $cache;
		$this->template = $template;
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.text_formatter_s9e_configure_after' => 'onConfigure',
			'core.text_formatter_s9e_render_before'   => 'onRender'
		];
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

	public function onRender($event)
	{
		if ($this->hasCode || strpos($event['xml'], '<CODE') === false)
		{
			return;
		}

		$this->hasCode = true;
		$parameters    = $this->cache->get('s9e_highlighter_parameters');
		if (!$parameters)
		{
			$parameters = $this->getTemplateParameters();
			$this->cache->put('s9e_highlighter_parameters', $parameters);
		}

		foreach ($parameters as $k => $v)
		{
			$this->template->assign_var('s9e_highlighter_' . $k, $v);
		}
	}

	protected function getTemplateParameters(): array
	{
		$parameters = [
			'hljs_options'     => '',
			'hljs_style'       => 'github-gist',
			'hljs_url'         => 'https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@10.6.0/build/',
			'needs_loader'     => 1,
			'script_integrity' => 'sha384-0R2eSjxhS3nwxyF5hfN2eeJ2m+X1s6yA8sb5pK7+/haZVPDRqEZIAQvSK4isiB5K',
			'script_src'       => 'https://cdn.jsdelivr.net/gh/s9e/hljs-loader@1.0.19/loader.min.js'
		];

		$html = Forum::render('<r><CODE></CODE></r>');
		preg_match_all('((\\w++)="([^"]++))', $html, $m);
		$values = array_map('htmlspecialchars_decode', array_combine($m[1], $m[2]));

		$map = [
			'integrity' => 'script_integrity',
			'options'   => 'hljs_options',
			'src'       => 'script_src',
			'url'       => 'hljs_url'
		];
		foreach (array_intersect_key($values, $map) as $k => $v)
		{
			$parameters[$map[$k]] = $v;
		}

		return $parameters;
	}
}