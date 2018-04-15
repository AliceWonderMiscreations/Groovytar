<?php
declare(strict_types=1);

// fixme w/ autoloader
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/filewrapper/lib/InvalidArgumentException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/filewrapper/lib/NullPropertyException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/filewrapper/lib/TypeErrorException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/filewrapper/lib/FileWrapper.php');

// The Identicons

require_once(dirname(dirname(__FILE__)) . '/lib/Confetti.php');
require_once(dirname(dirname(__FILE__)) . '/lib/PictoGlyph.php');


use \AWonderPHP\FileWrapper\FileWrapper as FileWrapper;

/**
 * Under normal circumstances this file is only called when by a
 * .htaccess rewrite rule.
 *
 * @package AWonderPHP/Groovytar
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/Groovytar
 */

if (isset($_GET['hash'])) {
    $ghash = $_GET['hash'];
    $ghash = trim(strtolower($ghash));
    if (ctype_xdigit($ghash)) {
        if (strlen($ghash) === 32) {
            $hash = $ghash;
        }
    }
}

if (isset($hash)) {
    $a = 'b';
    // TODO - look for link of hash to registered user
    // $sql = 'SELECT userlink FROM hashdsb WHERE hash=?'
    // okay, probably cache query before sql query
    // point is, when found, act on it.
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
    $rating = 'g';
    if (isset($_GET['r'])) {
        $getr = $_GET['r'];
        $getr = trim(strtolower($getr));
        if (in_array($getr, array('pg', 'r', 'x'))) {
            $rating = $getr;
        }
    }
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
        $variant = 'confetti';
}

$finished = array('confetti', 'pictoglyph');

if (! in_array($variant, $finished)) {
    $variant = 'confetti';
}

$topdir = dirname(dirname(__FILE__)) . '/generated/' . $variant;

if (strlen($hash) === 32) {
    $svgfile = $topdir . '/' . $hash . '.svg';
    if (file_exists($svgfile)) {
        // serve the file
        $obj = new FileWrapper($svgfile, null, 'image/svg+xml', 1209600);
        $obj->sendfile();
        exit;
    }
}
switch ($variant) {
    case 'confetti':
        $groovy = new \AWonderPHP\Groovytar\Confetti($hash);
        if (isset($svgfile)) {
            $groovy->writeFile($svgfile);
        }
        $groovy->sendContent();
        break;
    case 'pictoglyph':
        $groovy = new \AWonderPHP\Groovytar\PictoGlyph($hash);
        if (isset($svgfile)) {
            $groovy->writeFile($svgfile);
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