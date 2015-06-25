#!/bin/bash

tmpdir="/tmp"
rootdir="$(realpath $(dirname $(dirname $0)))"
cd "$rootdir"

rm -rf "$tmpdir/s9e"
mkdir -p "$tmpdir/s9e/highlighter/styles/prosilver/template/event"

files="
	LICENSE
	README.md
	composer.json
	styles/prosilver/template/event/overall_footer_body_after.html
";
for file in $files;
do
	cp "$file" "$tmpdir/s9e/highlighter/$file"
done

cd "$tmpdir"
rm "$tmpdir/highlighter.zip"
kzip -r -y "$tmpdir/highlighter.zip" s9e
advzip -z4 "$tmpdir/highlighter.zip"

rm -rf "$tmpdir/s9e"