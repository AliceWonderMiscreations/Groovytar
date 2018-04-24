<?php
declare(strict_types=1);

/**
 * Under normal circumstances this file is only called when by a
 * .htaccess rewrite rule.
 *
 * @package AWonderPHP/Groovytar
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/Groovytar
 */

// fixme w/ autoloader
require_once(dirname(__FILE__) . '/LOADER.php');

use \AWonderPHP\SimpleCacheAPCu\SimpleCacheAPCu as SimpleCache;
use \AWonderPHP\FileWrapper\FileWrapper as FileWrapper;

$apcu = new SimpleCache('groovytar');

// should be false in production
$develMode = false;
// should be false in production
$exampleMode = true;

// CSS pixels
$size = 240;
if (isset($_GET['s'])) {
    $gets = $_GET['s'];
    if (is_numeric($gets)) {
        $gets = intval($gets);
        $gets = abs($gets);
        if ($gets >= 32) {
            $size = $gets;
        }
    }
}

// rating - only matters for user uploaded avatars
$rating = 'g';
if (isset($_GET['r'])) {
    $getr = $_GET['r'];
    $getr = trim(strtolower($getr));
    if (in_array($getr, array('pg', 'r', 'x'))) {
        $rating = $getr;
    }
}

$requested = strtolower($_SERVER['REQUEST_URI']);
$arr = explode('/', $requested);
$ghash = end($arr);
$arr = explode('?', $ghash);
$ghash = $arr[0];

if (ctype_xdigit($ghash)) {
    if (strlen($ghash) === 32) {
        $hash = $ghash;
    }
}

if (isset($hash)) {
    $a = 'b';
    // TODO - look for link of hash to registered user
    // $sql = 'SELECT userlink FROM hashdsb WHERE hash=?'
    // okay, probably cache query before sql query
    // point is, when found, act on it.
} else {
    // This shouldn't happen but may as well prepare for it
    $raw = random_bytes(8);
    $hash = base64_encode($raw);
}

$getd = 'default';
if (isset($_GET['d'])) {
    $getd = $_GET['d'];
    $getd = trim(strtolower($getd));
}

switch ($getd) {
    case 'identicon':
        $variant = 'confetti';
        break;
    case 'confetti':
        $variant = 'confetti';
        break;
    case 'wavatar':
        $variant = 'amphibious';
        break;
    case 'amphibious':
        $variant = 'amphibious';
        break;
    case 'monsterid':
        $variant = 'ogre';
        break;
    case 'ogre':
        $variant = 'ogre';
        break;
    case 'retro':
        $variant = 'simplebit';
        break;
    case 'simplebit':
        $variant = 'simplebit';
        break;
    case 'robohash':
        $variant = 'automaton';
        break;
    case 'automaton':
        $variant = 'automaton';
        break;
    case 'mm':
        $variant = 'pictoglyph';
        break;
    case 'pictoglyph':
        $variant = 'pictoglyph';
        break;
    default:
        $variant = 'pictoglyph';
}

// okay not really finished but usable..
$finished = array('confetti', 'pictoglyph');

if (! in_array($variant, $finished)) {
    $variant = 'pictoglyph';
}

// for identicon generators that do things differently for small CSS pixel size
$smallArray = array(
    'pictoglyph' => 129
);

$smallLimit = 0;
if (isset($smallArray[$variant])) {
    $smallLimit = $smallArray[$variant];
}

$topdir = dirname(dirname(__FILE__)) . '/generated/' . $variant;
//var_dump($topdir); exit;

$writeFileBool = true;
if (strlen($hash) === 32) {
    $dir_exists = file_exists($topdir);
    if (! $dir_exists) {
        $dir_exists = mkdir($topdir, 0755, false);
    }
    if ($dir_exists) {
        $sizeModifier = '';
        if ($size < $smallLimit) {
            $sizeModifier = '-small';
        }
        $svgfile = $topdir . '/' . $hash . $sizeModifier . '.svg';
        $test = $apcu->get($svgfile);
        if(is_null($test)) {
            if (file_exists($svgfile)) {
                // todo - verify file is valid SVG before serving, important since
                //  web server has write access
            
                // serve the file
                $obj = new FileWrapper($svgfile, null, 'image/svg+xml', 1209600);
                $obj->sendfile();
                exit;
            } else {
                // prevent it from being served from file for five seconds
                $apcu->set($svgfile, 1, 5);
            }
        } else {
            //another process is already writing it
            $writeFileBool = false;
        }
    }
}
// we didn't have a cached copy on the filesystem to serve, so generate and serve
switch ($variant) {
    case 'confetti':
        $groovy = new \AWonderPHP\Groovytar\Confetti($hash, $size, $develMode, $exampleMode);
        if (isset($svgfile)) {
            $groovy->writeFile($svgfile);
        }
        $groovy->sendContent();
        break;
    case 'pictoglyph':
        $groovy = new \AWonderPHP\Groovytar\PictoGlyph($hash, $size, $develMode, $exampleMode);
        if (isset($svgfile)) {
            if ($writeFileBool) {
                $groovy->writeFile($svgfile);
            }
        }
        $groovy->sendContent();
        break;
    default:
        //something went wrong
        http_response_code(500);
        break;
}
exit;


?>