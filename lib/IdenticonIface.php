<?php
declare(strict_types = 1);

/**
 * Interface specification for creating Groovytar compatible identicon classes.
 *
 * @package AWonderPHP/Groovytar
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/Groovytar
 */

namespace AWonderPHP\Groovytar;

/**
 * The Interface defines three public methods that must exist.
 */
interface IdenticonIface
{
    /**
     * Writes the SVG to the specified file.
     *
     * @param string $path The path to where the file is to be written.
     *
     * @return void
     */
    public function writeFile(string $path): void;
    
    /**
     * Sends generated SVG to requesting client.
     *
     * @return void
     */
    public function sendContent(): void;
    
    /**
     * The constructor function interface.
     *
     * @param string $hash    A 16 byte hex encoded hash (0-9a-f) is expected but the class
     *                        should handle any string input and create a hash to use from
     *                        a string that is not a hash.
     * @param int    $size    An integer describing the requested CSS pixel size. All avatars
     *                        are square so only one dimension needs to be specified.
     * @param bool   $devel   Should default to false but when set to true, the class may
     *                        optionally trigger some non-production dev related things to take
     *                        place.
     * @param bool   $example Should default to false. When set to true, the class can
     *                        optionally ignore the $hash input and craft its own parameters
     *                        to make a demo image.
     */
    public function __construct(string $hash, int $size, bool $devel, bool $example);
}//end interface

?>