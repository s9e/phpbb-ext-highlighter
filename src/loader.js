(
	function(document)
	{
		if (!document.getElementsByTagName("pre").length)
		{
			return;
		}

		var head = document.getElementsByTagName("head")[0],
			script = document.createElement("script"),
			link = document.createElement("link");

		script.type = "text/javascript";
		script.onload = function()
		{
			hljs.initHighlighting();
		};
		script.async = true;
		script.src = "//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/highlight.min.js";
		head.appendChild(script);

		link.type = "text/css";
		link.rel = "stylesheet";
		link.href = "//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/styles/github-gist.min.css";
		head.appendChild(link);
	}
)(document);