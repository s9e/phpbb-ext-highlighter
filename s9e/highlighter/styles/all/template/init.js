(function(document)
{
	var nodes = document.querySelectorAll('pre>code[data-lang]'),
		i     = nodes.length;
	if (!i)
	{
		return;
	}

	function createScript(path)
	{
		var script   = document.createElement('script');
		script.async = false;
		script.type  = 'text/javascript';
		script.src   = url + path;

		return head.appendChild(script);
	}

	function highlightBlocks(lang)
	{
		if (typeof window['hljs'] === 'undefined')
		{
			return;
		}

		var i = nodes.length;
		while (--i >= 0)
		{
			if (nodes[i].getAttribute('data-lang') === lang)
			{
				window['hljs']['highlightBlock'](nodes[i]);
			}
		}
	}

	function processBlock(block)
	{
		var alias = block.getAttribute('data-lang').toLowerCase(),
			lang  = map[alias] || '';

		// Set the data-lang attribute to the canonical lang or an empty string
		block.setAttribute('data-lang', lang);
		if (!lang)
		{
			return;
		}

		var script = createScript('languages/' + lang + '.min.js');
		script.onload = script.onerror = function()
		{
			highlightBlocks(lang);
		};
	}

	var head = document.getElementsByTagName('head')[0],
		link = document.createElement('link'),
		url  = 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/',
		map  = {"1c":"1c","abnf":"abnf","accesslog":"accesslog","actionscript":"actionscript","ada":"ada","ado":"stata","adoc":"asciidoc","ahk":"autohotkey","applescript":"applescript","arduino":"arduino","arm":"armasm","armasm":"armasm","as":"actionscript","asciidoc":"asciidoc","aspectj":"aspectj","autohotkey":"autohotkey","autoit":"autoit","avrasm":"avrasm","awk":"awk","axapta":"axapta","basic":"basic","bat":"dos","bf":"brainfuck","bind":"dns","bnf":"bnf","brainfuck":"brainfuck","cal":"cal","capnp":"capnproto","capnproto":"capnproto","ceylon":"ceylon","clean":"clean","clj":"clojure","clojure":"clojure","clojure-repl":"clojure-repl","cmake":"cmake","cmake.in":"cmake","cmd":"dos","coq":"coq","cos":"cos","cr":"crystal","craftcms":"twig","crm":"crmsh","crmsh":"crmsh","crystal":"crystal","csp":"csp","d":"d","dart":"dart","dcl":"clean","delphi":"delphi","dfm":"delphi","django":"django","dns":"dns","do":"stata","docker":"dockerfile","dockerfile":"dockerfile","dos":"dos","dpr":"delphi","dsconfig":"dsconfig","dst":"dust","dts":"dts","dust":"dust","ebnf":"ebnf","elixir":"elixir","elm":"elm","erb":"erb","erl":"erlang","erlang":"erlang","erlang-repl":"erlang-repl","excel":"excel","f90":"fortran","f95":"fortran","feature":"gherkin","fix":"fix","flix":"flix","fortran":"fortran","freepascal":"delphi","fs":"fsharp","fsharp":"fsharp","gams":"gams","gauss":"gauss","gcode":"gcode","gherkin":"gherkin","glsl":"glsl","gms":"gams","go":"go","golang":"go","golo":"golo","gradle":"gradle","graph":"roboconf","groovy":"groovy","gss":"gauss","haml":"haml","handlebars":"handlebars","haskell":"haskell","haxe":"haxe","hbs":"handlebars","hs":"haskell","hsp":"hsp","html.handlebars":"handlebars","html.hbs":"handlebars","htmlbars":"htmlbars","hx":"haxe","hy":"hy","hylang":"hy","i7":"inform7","icl":"clean","inform7":"inform7","instances":"roboconf","irpf90":"irpf90","jboss-cli":"jboss-cli","jinja":"django","jldoctest":"julia-repl","julia":"julia","julia-repl":"julia-repl","k":"q","kdb":"q","kotlin":"kotlin","lasso":"lasso","lassoscript":"lasso","lazarus":"delphi","ldif":"ldif","leaf":"leaf","less":"less","lfm":"delphi","lisp":"lisp","livecodeserver":"livecodeserver","livescript":"livescript","llvm":"llvm","lpr":"delphi","ls":"livescript","lsl":"lsl","lua":"lua","m":"mercury","mathematica":"mathematica","matlab":"matlab","maxima":"maxima","mel":"mel","mercury":"mercury","mikrotik":"routeros","mips":"mipsasm","mipsasm":"mipsasm","mizar":"mizar","ml":"sml","mma":"mathematica","mojolicious":"mojolicious","monkey":"monkey","moo":"mercury","moon":"moonscript","moonscript":"moonscript","n1ql":"n1ql","nc":"gcode","nim":"nimrod","nimrod":"nimrod","nix":"nix","nsis":"nsis","ocaml":"ocaml","openscad":"openscad","osascript":"applescript","oxygene":"oxygene","p21":"step21","parser3":"parser3","pas":"delphi","pascal":"delphi","pb":"purebasic","pbi":"purebasic","pcmk":"crmsh","pf":"pf","pf.conf":"pf","pony":"pony","powershell":"powershell","pp":"puppet","processing":"processing","profile":"profile","prolog":"prolog","protobuf":"protobuf","ps":"powershell","puppet":"puppet","purebasic":"purebasic","q":"q","qml":"qml","qt":"qml","r":"r","rib":"rib","roboconf":"roboconf","routeros":"routeros","rs":"rust","rsl":"rsl","ruleslanguage":"ruleslanguage","rust":"rust","scad":"openscad","scala":"scala","scheme":"scheme","sci":"scilab","scilab":"scilab","scss":"scss","smali":"smali","smalltalk":"smalltalk","sml":"sml","sqf":"sqf","st":"smalltalk","stan":"stan","stata":"stata","step":"step21","step21":"step21","stp":"step21","styl":"stylus","stylus":"stylus","subunit":"subunit","sv":"verilog","svh":"verilog","swift":"swift","taggerscript":"taggerscript","tao":"xl","tap":"tap","tcl":"tcl","tex":"tex","thrift":"thrift","tk":"tcl","tp":"tp","ts":"typescript","twig":"twig","typescript":"typescript","v":"verilog","vala":"vala","vb":"vbnet","vbnet":"vbnet","vbs":"vbscript","vbscript":"vbscript","vbscript-html":"vbscript-html","verilog":"verilog","vhdl":"vhdl","vim":"vim","wildfly-cli":"jboss-cli","x86asm":"x86asm","xl":"xl","xls":"excel","xlsx":"excel","xpath":"xquery","xq":"xquery","xquery":"xquery","yaml":"yaml","yml":"yaml","zep":"zephir","zephir":"zephir","zone":"dns"};

	createScript('highlight.min.js').onload = function()
	{
		highlightBlocks('');
	};

	link.type = 'text/css';
	link.rel  = 'stylesheet';
	link.href = url + 'styles/github-gist.min.css';
	head.appendChild(link);

	while (--i >= 0)
	{
		processBlock(nodes[i]);
	}
})(document);