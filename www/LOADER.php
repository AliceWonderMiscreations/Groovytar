<?php
declare(strict_types=1);

/**
 * This file can be replaced by a PS-R autoloader.
 *
 * @package AWonderPHP/Groovytar
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/Groovytar
 */

// PSR-16 interface
require_once(dirname(dirname(__FILE__)) . '/vendor/psr/simple-cache/src/CacheException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/psr/simple-cache/src/InvalidArgumentException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/psr/simple-cache/src/CacheInterface.php');

// SimpleCache
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecache/lib/InvalidArgumentException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecache/lib/StrictTypeException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecache/lib/InvalidSetupException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecache/lib/SimpleCache.php');

// SimpleCacheAPCu
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/simplecacheapcu/lib/SimpleCacheAPCu.php');

// FileWrapper
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/filewrapper/lib/InvalidArgumentException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/filewrapper/lib/NullPropertyException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/filewrapper/lib/TypeErrorException.php');
require_once(dirname(dirname(__FILE__)) . '/vendor/awonderphp/filewrapper/lib/FileWrapper.php');

// The Identicon Supporting classes
require_once(dirname(dirname(__FILE__)) . '/lib/IdenticonIface.php');
require_once(dirname(dirname(__FILE__)) . '/lib/Identicon.php');
require_once(dirname(dirname(__FILE__)) . '/lib/WcagColor.php');

// The identicon Classes
require_once(dirname(dirname(__FILE__)) . '/lib/Confetti.php');
require_once(dirname(dirname(__FILE__)) . '/lib/PictoGlyph.php');

?>