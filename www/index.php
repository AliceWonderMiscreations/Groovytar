<html>
<head>
<title>Groovytar Identicon Example Page</title>
</head>
<body>
<h1>Groovytar Identicon Example Page</h1>
<p>Excuse the poor web design, I will make something better later.</p>
<?php
$foo = rand_bytes(10);
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
</body>
</html>