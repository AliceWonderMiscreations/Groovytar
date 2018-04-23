<html>
<head>
<title>Groovytar Identicon Example Page</title>
</head>
<body>
<h1>Groovytar Identicon Example Page</h1>
<p>Excuse the poor web design, I will make something better later.</p>
<?php
$foo = random_bytes(10);
$hash = md5($foo, false);
?>
<p>The hash being used for this example: <code><?php echo $hash;?></code></p>
<h2>PictoGlyph Identicon</h2>
<p>The following PictoGlyph identicon is generated from the defined hash, shown displayed at various sizes:</p>
<p>96 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=pictoglyph&s=96" style="width: 96px;" />';
echo($str); 
?>
<p>128 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=pictoglyph&s=128" style="width: 128px;" />';
echo($str); 
?>
<p>192 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=pictoglyph&s=192" style="width: 192px;" />';
echo($str); 
?>
<p>256 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=pictoglyph&s=256" style="width: 256px;" />';
echo($str); 
?>

<h2>Confetti</h2>
<p>The following Confetti identicon is generated from the defined hash, shown displayed at various sizes:</p>
<p>96 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=confetti&s=96" style="width: 96px;" />';
echo($str); 
?>
<p>128 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=confetti&s=128" style="width: 128px;" />';
echo($str); 
?>
<p>192 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=confetti&s=192" style="width: 192px;" />';
echo($str); 
?>
<p>256 CSS pixels wide:</p>
<?php
$str = '<img src="/avatar/' . $hash . '?d=confetti&s=256" style="width: 256px;" />';
echo($str); 
?>

</body>
</html>