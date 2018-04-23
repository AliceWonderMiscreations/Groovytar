<html>
<head>
<title>Groovytar Identicon Example Page</title>
</head>
<body>
<h1>Groovytar Identicon Example Page</h1>
<p>Excuse the poor web design, I will make something better later.</p>
<p>The github for this project has better details: <a href="https://github.com/AliceWonderMiscreations/Groovytar" target="_blank">https://github.com/AliceWonderMiscreations/Groovytar</a></p>
<?php
$foo = random_bytes(1);
$hash = md5($foo, false);
?>
<p>The hash being used for this example: <code><?php echo $hash;?></code></p>
<p>I am cheating with this a bit, there is a problem where sometimes when a hash is not yet cached but is requested multiple times, a race condition exists where the file it is cached to is read before it is being finished written to resulting in a broken SVG being served. I am cheating by only having 256 different possible different hashes for this example, giving a high liklihood that the images are already long ago generated and only served from the file cache avoiding that issue.</p>
<p>That is an issue I will attempt to resolve though in real world it will rarely be an issue.</p>
<p>What I might do is if it isn't cached to file, put a quickly expiring APCu lock on the hash so that that it doesn't try to serve it from file cache until the cache lock expires. But anyway...</p>
<h2>PictoGlyph Identicon</h2>
<p>This identicon is not yet finished, right now only ten different glyphs have been generated (there will be 32) and only about 60 color combinations have chosen, some of which will be rejected (there will be 128).</p>
<p>For small display, there are three rows of three. For large display, there are four rows of four.</p>
<p>The following PictoGlyph identicon is generated from the defined hash, shown displayed at various sizes:</p>
<p>96 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=pictoglyph&s=96" style="width: 96px;" /><hr />';
echo($str); 
?>
<p>128 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=pictoglyph&s=128" style="width: 128px;" /><p><a href="/avatar/' . $hash . '?d=pictoglyph&s=128" target="_blank">Link to Full View</a></p><hr />';
echo($str); 
?>
<p>192 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=pictoglyph&s=192" style="width: 192px;" /><hr />';
echo($str); 
?>
<p>256 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=pictoglyph&s=256" style="width: 256px;" /><p><a href="/avatar/' . $hash . '?d=pictoglyph&s=256" target="_blank">Link to Full View</a></p><hr />';
echo($str); 
?>

<h2>Confetti</h2>
<p>Other than maybe improving the generated frame, this one is done. The same image is used large or small.</p>
<p>The following Confetti identicon is generated from the defined hash, shown displayed at various sizes:</p>
<p>96 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=confetti&s=96" style="width: 96px;" /><hr />';
echo($str); 
?>
<p>128 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=confetti&s=128" style="width: 128px;" /><hr />';
echo($str); 
?>
<p>192 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=confetti&s=192" style="width: 192px;" /><hr />';
echo($str); 
?>
<p>256 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=confetti&s=256" style="width: 256px;" /><p><a href="/avatar/' . $hash . '?d=confetti&s=256" target="_blank">Link to Full View</a></p><hr />';
echo($str); 
?>

</body>
</html>