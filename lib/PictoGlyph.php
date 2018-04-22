<?php
declare(strict_types=1);

/**
 * Generates a PictoGlyph avatar SVG file.
 *
 * @package AWonderPHP/Groovytar
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/Groovytar
 */

namespace AWonderPHP\Groovytar;

// fixme w/ autoloader
require_once(dirname(__FILE__) . '/IdenticonIface.php');
require_once(dirname(__FILE__) . '/Identicon.php');
require_once(dirname(__FILE__) . '/WcagColor.php');

use \AWonderPHP\Groovytar\WcagColor as WcagColor;

/**
 * PictoGlyph Generation.
 * color selection currently often results in poor colors, I'm working on that...
 */
class PictoGlyph extends Identicon implements IdenticonIface
{
    /**
     * Are we running in devel / test mode ???
     *
     * @var boolean
     */
    protected $devel = false;
    
    /**
     * The background color
     *
     * @var array
     */
    protected $background = array(69, 2, 113);
    
    /**
     * Background color as SVG string
     *
     * @var string
     */
    protected $backgroundRgb = 'rgb(69, 2, 113)';
    
    /**
     * The Foreground color
     *
     * @var array
     */
    protected $foreground = array(225, 178, 89);
    
    /**
     * Foreground color as SVG string
     *
     * @var string
     */
    protected $foregroundRgb = 'rgb(225, 178, 89)';

    /**
     * Generate a set of parameters from a given hex hash.
     *
     * @param string $hash    The hex hash to generate parameters from.
     * @param bool   $example When true, the parameters are set to be random but such that the
     *                        same mod32 result is never repeated. For the purpose of generating
     *                        example avatars showing 16 different glyphs.
     *
     * @return void
     */
    protected function hashToParameters(string $hash, bool $example = false): void
    {
        if (! ctype_xdigit($hash)) {
            // like should never happen in use but...
            $hash = md5($hash);
        }
        $raw = hex2bin($hash);
        // using sha384 because why the frack not?
        $raw = hash('sha384', $raw, true);
        // using tiger because why the frack not?
        $hash128bit=hash('tiger128,3', $raw, false);
        $this->parameters = array();
        for ($i=0; $i<16; $i++) {
            $n = 2 * $i;
            $this->parameters[] = hexdec(substr($hash128bit, $n, 2));
        }
        if ($example) {
            $used = array();
            $count = 0;
            while ($count < 17) {
                $n = random_int(0, 255);
                $mod = $n % 32;
                if (! in_array($mod, $used)) {
                    $this->parameters[$count] = $n;
                    $used[] = $mod;
                    $count = count($used);
                }
            }
        }
        $sum = 17; //because I like 17
        for ($i=0; $i<16; $i++) {
            $sum = $sum + $this->parameters[$i];
        }
        $n = $sum % 256;
        $colorScheme = WcagColor::selectColorCombo($n);
        $this->backgroundRgb = $this->setRgbString($colorScheme['background'][0], $colorScheme['background'][1], $colorScheme['background'][2]);
        $this->foregroundRgb = $this->setRgbString($colorScheme['foreground'][0], $colorScheme['foreground'][1], $colorScheme['foreground'][2]);
    }//end hashToParameters()

    /**
     * Used only in testing, will be nuked when I have 32 pictographs
     *
     * @param int $x The X coordinate for the center of the square the glyph is placed in.
     * @param int $y The Y coordinate for the center of the square the glyph is placed in.
     *
     * @return void
     */
    protected function simpleCircle($x, $y): void
    {
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $x);
        $circle->setAttribute('cy', (string) $y);
        $circle->setAttribute('r', '75');
        $circle->setAttribute('stroke', 'none');
        $circle->setAttribute('fill', $this->foregroundRgb);
        $this->svg->appendChild($circle);
    }//end simpleCircle()

    /**
     * Triquetra knot, often symbolizes earth, air, and water or life, death, and rebirth.
     * Also symbolizes the Triple Goddess - Maiden, Mother, Crone.
     *
     * Nutshell - Everything important comes in threes.
     *
     * @param int $x The X coordinate for the center of the square the glyph is placed in.
     * @param int $y The Y coordinate for the center of the square the glyph is placed in.
     *
     * @return void
     */
    protected function simpleTriquetra($x, $y): void
    {
        $spath = 'a 66,66 0 0 1 130,0 ';
        $spath .= 'a 66,66 0 0 1 -65,-113 ';
        $spath .= 'a 66,66 0 0 1 -65,113z';
        
        $this->svgStrokePath(($x - 65), ($y + 54), $spath, $this->foregroundRgb, 11);
    }//end simpleTriquetra()

    /**
     * Fertility Rune
     *
     * @param int $x The X coordinate for the center of the square the glyph is placed in.
     * @param int $y The Y coordinate for the center of the square the glyph is placed in.
     *
     * @return void
     */
    protected function simpleFertility($x, $y): void
    {
        $spath = 'l60,83 l-60,83';
        $this->svgStrokePath(($x - 30), ($y - 83), $spath, $this->foregroundRgb, 9);
        
        $spath = 'l-60,83 l60,83';
        $this->svgStrokePath(($x + 30), ($y - 83), $spath, $this->foregroundRgb, 11);
    }//end simpleFertility()

    /**
     * Yin and Yang - Opposite and often seemingly contrary forces are often actually complimentary
     * to each other.
     *
     * @param int $x The X coordinate for the center of the square the glyph is placed in.
     * @param int $y The Y coordinate for the center of the square the glyph is placed in.
     *
     * @return void
     */
    protected function simpleYinYang($x, $y): void
    {
        //Draw the light
        $spath = 'a37,37 0 0 1 0,74 a37,37 0 0 0 0,74 a74,74 0 0 1 0,-148z';
        $this->svgFillPath($x, ($y - 74), $spath, $this->foregroundRgb, 1);
        
        //draw the outer circle
        $spath = 'a 75,75 0 0 0 150,0 a 75,75 0 0 0 -150,0z';
        $this->svgStrokePath(($x - 75), $y, $spath, $this->foregroundRgb, 4);
        
        // add the dots
        $this->svgFilledCircle($x, ($y - 37), 8, $this->backgroundRgb);
        $this->svgFilledCircle($x, ($y + 37), 8, $this->foregroundRgb);
    }//end simpleYinYang()

    /**
     * Asase Ye Duru - The Divinity of Mother Earth.
     * https://www.nps.gov/afbg/learn/historyculture/asase-ye-duru.htm
     *
     * It emphasizes the importance of Earth and it's preservation.
     *
     * @param int $x The X coordinate for the center of the square the glyph is placed in.
     * @param int $y The Y coordinate for the center of the square the glyph is placed in.
     *
     * @return void
     */
    protected function simpleAsaseYeDuru($x, $y): void
    {
        $spath = 'q-16.5,-9.75 -30,-22.5 ';
        $spath .= 'q-9.75,-8.25 -18,-24 ';
        $spath .= 'a20.25,21.75 0 0 1 9.75,-27 ';
        $spath .= 'a20.25,20.25 0 0 1 -8,-27 ';
        $spath .= 'q3,-8.25 16.5,-24 ';
        $spath .= 'q16.5,-16.5 30,-25.5 ';
        $spath .= 'q21,13.5 33,25.5 ';
        $spath .= 'q6,5.25 13.5,18.75 ';
        $spath .= 'a21.75,21.75 0 0 1 -8.25,30 ';
        $spath .= 'a20.25,21 0 0 1 7.5,30 ';
        $spath .= 'q-11.25,18.75 -24,30 ';
        $spath .= 'q-11.25,9.75 -21,15.75z';
        $this->svgFillPath($x, ($y + 75), $spath, $this->foregroundRgb);

        $spath = 'q21.75,15 33.75,30 ';
        $spath .= 'q6.75,7.5 8.25,18 ';
        $spath .= 'a15,14.25 0 0 1 -11.25,14.25 ';
        $spath .= 'q-12,1.5 -17.25,-3.75 ';
        $spath .= 'q-5.25,-5.25 -5.25,-8.25 ';
        $spath .= 'q0.75,-5.25 7.5,-6 ';
        $spath .= 'q3,-0.75 5.25,0.75 ';
        $spath .= 'q1.5,2.25 2.25,6 ';
        $spath .= 'a4.5,4.5 0 0 0 7.875,-1.5 ';
        $spath .= 'a7.5,7.5 0 0 0 -1.125,-8.25 ';
        $spath .= 'q-2.25,-3.75 -8.25,-6 ';
        $spath .= 'a14.25,14.25 0 0 0 -8.25,1.5 ';
        $spath .= 'q-7.5,3 -12,7.5 ';
        $spath .= 'a18.75,18.75 0 0 0 -16.5,-9.75 ';
        $spath .= 'q-6,-0.375 -14.25,8.625 ';
        $spath .= 'q-2.25,3.75 -2.25,6.75 ';
        $spath .= 'a3,3 0 0 0 3.75,2.25 ';
        $spath .= 'q3,0 4.875,-2.625 ';
        $spath .= 'a7.5,9 0 0 1 8.25,-6 ';
        $spath .= 'a9,9.75 0 0 1 8.25,7.5 ';
        $spath .= 'q0,10.5 -15,10.5 ';
        $spath .= 'a15.75,15 0 0 1 -16.5,-12 ';
        $spath .= 'q-0.375,-3.375 1.5,-7.5 ';
        $spath .= 'q4.5,-11.25 15,-22.5 ';
        $spath .= 'q16.875,-18 21.375,-19.5z';
        $this->svgFillPath(($x - 1), ($y - 65), $spath, $this->backgroundRgb);

        $spath = 'q-21.75,-15 -33.75,-30 ';
        $spath .= 'q-6.75,-7.5 -8.25,-18 ';
        $spath .= 'a-15,-14.25 0 0 1 11.25,-14.25 ';
        $spath .= 'q12,-1.5 17.25,3.75 ';
        $spath .= 'q5.25,5.25 5.25,8.25 ';
        $spath .= 'q-0.75,5.25 -7.5,6 ';
        $spath .= 'q-3,0.75 -5.25,-0.75 ';
        $spath .= 'q-1.5,-2.25 -2.25,-6 ';
        $spath .= 'a-4.5,-4.5 0 0 0 -7.875,1.5 ';
        $spath .= 'a-7.5,-7.5 0 0 0 1.125,8.25 ';
        $spath .= 'q2.25,3.75 8.25,6 ';
        $spath .= 'a-14.25,-14.25 0 0 0 8.25,-1.5 ';
        $spath .= 'q7.5,-3 12,-7.5 ';
        $spath .= 'a-18.75,-18.75 0 0 0 16.5,9.75 ';
        $spath .= 'q6,0.375 14.25,-8.625 ';
        $spath .= 'q2.25,-3.75 2.25,-6.75 ';
        $spath .= 'a-3,-3 0 0 0 -3.75,-2.25 ';
        $spath .= 'q-3,0 -4.875,2.625 ';
        $spath .= 'a-7.5,-9 0 0 1 -8.25,6 ';
        $spath .= 'a-9,-9.75 0 0 1 -8.25,-7.5 ';
        $spath .= 'q0,-10.5 15,-10.5 ';
        $spath .= 'a-15.75,-15 0 0 1 16.5,12 ';
        $spath .= 'q0.375,3.375 -1.5,7.5 ';
        $spath .= 'q-4.5,11.25 -15,22.5 ';
        $spath .= 'q-16.875,18 -21.375,19.5z';
        $this->svgFillPath(($x + 1), ($y + 66), $spath, $this->backgroundRgb);
        
        $spath = 'a21,21 0 0 0 6.5,-6 ';
        $spath .= 'a18,18 0 0 0 8,7.5 ';
        $spath .= 'a21,21 0 0 0 -6.5,6 ';
        $spath .= 'a18,18 0 0 0 -8,-7.5z';
        $this->svgFillPath(($x - 7), $y, $spath, $this->backgroundRgb);
    }//end simpleAsaseYeDuru()
    
    /**
     * Elvin Star - Many different meanings, 7 has lots of significance in historic cultures.
     *
     * @param int $x The X coordinate for the center of the square the glyph is placed in.
     * @param int $y The Y coordinate for the center of the square the glyph is placed in.
     *
     * @return void
     */
    protected function simpleElvinStar($x, $y): void
    {
        // 51.428571429 degrees = 360/7 = 0.897597901 radians
        // 25.714285714 degrees = 360/14 = 0.448798951 radians
        
        // Point Ax = $x + + r * (sin 25.714285714)
        // Point Ay = $y + * r * (cos 25.714285714)
      
        $rad07 = 0.897597901;
        $rad14 = 0.448798951;
      
        $r = 75;
      
        $Ax = $x - ($r * (sin($rad14)));
        $Ay = $y + ($r * (cos($rad14)));
        $Bx = $x - ($r * (sin($rad14 + (3 * $rad07))));
        $By = $y + ($r * (cos($rad14 + (3 * $rad07))));
        $Cx = $x - ($r * (sin($rad14 + (6 * $rad07))));
        $Cy = $y + ($r * (cos($rad14 + (6 * $rad07))));
        $Dx = $x - ($r * (sin($rad14 + (9 * $rad07))));
        $Dy = $y + ($r * (cos($rad14 + (9 * $rad07))));
        $Ex = $x - ($r * (sin($rad14 + (12 * $rad07))));
        $Ey = $y + ($r * (cos($rad14 + (12 * $rad07))));
        $Fx = $x - ($r * (sin($rad14 + (15 * $rad07))));
        $Fy = $y + ($r * (cos($rad14 + (15 * $rad07))));
        $Gx = $x - ($r * (sin($rad14 + (18 * $rad07))));
        $Gy = $y + ($r * (cos($rad14 + (18 * $rad07))));
      
        $spath = 'l' . ($Bx - $Ax) . ',' . ($By - $Ay) . ' ';
        $spath = $spath . 'l' . ($Cx - $Bx) . ',' . ($Cy - $By) . ' ';
        $spath = $spath . 'l' . ($Dx - $Cx) . ',' . ($Dy - $Cy) . ' ';
        $spath = $spath . 'l' . ($Ex - $Dx) . ',' . ($Ey - $Dy) . ' ';
        $spath = $spath . 'l' . ($Fx - $Ex) . ',' . ($Fy - $Ey) . ' ';
        $spath = $spath . 'l' . ($Gx - $Fx) . ',' . ($Gy - $Fy) . ' ';
        $spath = $spath . 'l' . ($Ax - $Gx) . ',' . ($Ay - $Gy) . 'z';
        
        $this->svgStrokePath($Ax, $Ay, $spath, $this->foregroundRgb, 5);
    }//end simpleElvinStar()

    /**
     * Hawaiian Turtle Glyph - Means Good Luck
     *
     * @param int $x The X coordinate for the center of the square the glyph is placed in.
     * @param int $y The Y coordinate for the center of the square the glyph is placed in.
     *
     * @return void
     */
    protected function simpleTurtle(int $x, int $y): void
    {
        $spath = 'a24.5,47 0 0 0 -44.5,13.5 ';
        $spath .= 'a20,27.5 0 0 1 9.5,-47.75 ';
        $spath .= 'a87.5,75 0 0 1 0,-73 ';
        $spath .= 'a10,10 0 0 0 -3,-4 ';
        $spath .= 'a10,10 0 0 0 -6.5,-0.5 ';
        $spath .= 'a40,40 0 0 0 -26.5,30 ';
        $spath .= 'a6,40 0 0 1 -4,9.25 ';
        $spath .= 'a10,15 0 0 1 -9,-7.5 ';
        $spath .= 'a50,50 0 0 1 15,-40.5 ';
        $spath .= 'a35,40 0 0 1 31.5,-3.5 ';
        $spath .= 'l 10,4 ';
        $spath .= 'a15,20 0 0 0 10.5,-0.5 ';
        $spath .= 'a27.5,20 0 0 1 10,-2.75 ';
        $spath .= 'c1,-8 -7,-15 -7,-25 ';
        $spath .= 'a14,15 0 0 1 28,0 ';
        $spath .= 'c0,10 -8,17 -7,25 ';
        $spath .= 'a27.5,20 0 0 1 10,2.75 ';
        $spath .= 'a15,20 0 0 0 10.5,0.5 ';
        $spath .= 'l 10,-4 ';
        $spath .= 'a35,40 0 0 1 32,3.5 ';
        $spath .= 'a50,50 0 0 1 16,40.5 ';
        $spath .= 'a10,15 0 0 1 -9.5,8 ';
        $spath .= 'a6,40 0 0 1 -4,-9.25 ';
        $spath .= 'a40,40 0 0 0 -26.5,-30.5 ';
        $spath .= 'a10,10 0 0 0 -7,0.5 ';
        $spath .= 'a10,10 0 0 0 -3.5,4 ';
        $spath .= 'a87.5,75 0 0 1 0,73 ';
        $spath .= 'a20,27.5 0 0 1 9.5,47.75 ';
        $spath .= 'a24.5,47 0 0 0 -44.5,-13.5z';
        $this->svgFillPath($x, ($y + 75), $spath, $this->foregroundRgb);

        $spath = 'a30,20 0 0 0 50,0z';
        $this->svgFillPath(($x - 25), ($y + 51.5), $spath, $this->foregroundRgb);

        $spath = 'l 0,78 ';
        $spath .= 'c0,6.5 -7.5,5 -11,3 ';
        $spath .= 'a37.5,45 0 0 1 0,-84 ';
        $spath .= 'c3.5,-2 11,-3.5 11,3z';
        $this->svgFillPath(($x - 2.75), ($y - 33.75), $spath, $this->backgroundRgb, 0.7);

        $spath = 'l 0,78 ';
        $spath .= 'c0,6.5 7.5,5 11,3 ';
        $spath .= 'a37.5,45 0 0 0 0,-84 ';
        $spath .= 'c-3.5,-2 -11,-3.5 -11,3z';
        $this->svgFillPath(($x + 2.75), ($y - 33.75), $spath, $this->backgroundRgb, 0.7);
    }//end simpleTurtle()
    
    /**
     * Boa Me Na Me Mmoa Wo - Adinkra "Help me and let me help you"
     * Symbolizes cooperation and interdependence.
     *
     * @param int $x The X coordinate for the center of the square the glyph is placed in.
     * @param int $y The Y coordinate for the center of the square the glyph is placed in.
     *
     * @return void
     */
    protected function simpleBoaMeNaMeMmoaWo(int $x, int $y): void
    {
        $backgroundRgb = $this->setRgbString($this->background[0], $this->background[1], $this->background[2]);
        $foregroundRgb = $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]);
        
        $pathString = 'M' . ($x - 34) . ',' . ($y + 50) . ' ';
        $pathString .= 'a390,150 0 0 1 0,-100 ';
        $pathString .= 'l 11,14 ';
        $pathString .= 'a 700,175 0 0 0 0,72 ';
        $pathString .= 'l -11,14z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . ($x + 34) . ',' . ($y + 50) . ' ';
        $pathString .= 'a 390,150 0 0 0 0,-100 ';
        $pathString .= 'l -11,14 ';
        $pathString .= 'a 700,175 0 0 1 0,72 ';
        $pathString .= 'l 11,14z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . $x . ',' . $y . ' ';
        $pathString .= 'l -47.5,61.5 ';
        $pathString .= 'l 95,0 ';
        $pathString .= 'l -47.5,-61.5z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . $x . ',' . $y . ' ';
        $pathString .= 'l -47.5,-61.5 ';
        $pathString .= 'l 95,0 ';
        $pathString .= 'l -47.5,61.5z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . ($x - 14) . ',' . ($y + 59) . ' ';
        $pathString .= 'l 0,28 ';
        $pathString .= 'l 28,0 ';
        $pathString .= 'l 0,-28';
        $pathString .= 'l -28,0z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('stroke', 'none');
        $circle->setAttribute('fill', $foregroundRgb);
        $circle->setAttribute('cx', (string) $x);
        $cy = ($y - 71.5);
        $circle->setAttribute('cy', (string) $cy);
        $circle->setAttribute('r', '16');
        $this->svg->appendChild($circle);
        
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('stroke', 'none');
        $circle->setAttribute('fill', $backgroundRgb);
        $circle->setAttribute('cx', (string) $x);
        $cy = ($y + 38.5);
        $circle->setAttribute('cy', (string) $cy);
        $circle->setAttribute('r', '13');
        $this->svg->appendChild($circle);
        
        $pathString = 'M' . ($x - 11.5) . ',' . ($y - 50) . ' ';
        $pathString .= 'l 0,23 ';
        $pathString .= 'l 23,0 ';
        $pathString .= 'l 0,-23 ';
        $pathString .= 'l -23,0z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
    }//end simpleBoaMeNaMeMmoaWo()
    
    /**
     * Māori Koru - The appearance of a new unfurling silver fern frond.
     * Symbolizes new life, growth, strength, peace.
     *
     * @param int $x The X coordinate for the center of the square the glyph is placed in.
     * @param int $y The Y coordinate for the center of the square the glyph is placed in.
     *
     * @return void
     */
    protected function simpleKoru(int $x, int $y): void
    {
        return;
    }//end simpleKoru()

    /**
     * Taino Coqui frog.
     *
     * @param int $x The X coordinate for the center of the square the glyph is placed in.
     * @param int $y The Y coordinate for the center of the square the glyph is placed in.
     *
     * @return void
     */
    protected function simpleCoqui(int $x, int $y): void
    {
        $foregroundRgb = $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]);
        $adjustX = 0;
        $adjustY = 0;
        
        $startMainX = ($x - 15.5) + $adjustX;
        $startLeftEyeX = ($x - 45) + $adjustX;
        $startRightEyeX = ($x + 25.25) + $adjustX;
        $startMainY = ($y + 23) + $adjustY;
        $startLeftEyeY = ($y - 55) + $adjustY;
        $startRightEyeY = ($y - 63) + $adjustY;
        
        $pathString = 'M' . $startMainX . ',' . $startMainY . ' ';
        $pathString .= 'l5.5,-42 ';
        $pathString .= 'a80,90 0 0 1 -53.75,-34.5 ';
        $pathString .= 'l-5,1.5 ';
        $pathString .= 'c-7.5,2.25 -13.5,1.5 -8,-8 ';
        $pathString .= 'c3.25,-5.613635 2.5,-8.5 1.25,-11.5 ';
        $pathString .= 'c-2.5,-6 0,-8 0.5,-8.75 ';
        $pathString .= 'c2,-3 3,-3.5 6.5,-1.75 ';
        $pathString .= 'c3.5,1.75 5,4 7,5.5 ';
        $pathString .= 'c1,0.75 2,1 4,-0.5 ';
        $pathString .= 'c5,-3.75 11.5,-3.5 6.5,10 ';
        
        $pathString .= 'c-1.5,5 -2,8 -1.75,8.25 ';
        $pathString .= 'c15,16.25 31.25,22.5 42.5,22.25 ';
        $pathString .= 'c1,-0.75 3.75,-12.5 4,-15 ';
        $pathString .= 'c0.25,-2.5 2.25,-11.75 2.5,-12.5 ';
        $pathString .= 'c1.75,-5.25 5,-9 7.25,-9 ';
        $pathString .= 'c4,0 5.25,5 6,6.75 ';
        $pathString .= 'c1,2.333333 1.75,8.75 1.5,11 ';
        $pathString .= 'c-0.5,8.666667 -1,15 -1.5,18.75 ';
        $pathString .= 'c-0.25,5.041667 4.25,4.5 5,4.75 ';
        $pathString .= 'c3.75,1.25 38.25,-5 42.5,-11.75 ';
        
        $pathString .= 'c0.75,-4 1,-8 1.25,-10.5 ';
        $pathString .= 'c0.5,-5 3.75,-6.5 6.75,-3 ';
        $pathString .= 'c1.75,3.5 4.25,3.75 6.75,0.5 ';
        $pathString .= 'c1.25,-1.625 2,-2.25 5.75,-0.75 ';
        $pathString .= 'c3.25,2.383333 4.5,2 4.75,4.5 ';
        $pathString .= 'c0.25,2.5 -3,5.5 -4.5,6 ';
        $pathString .= 'c-4.5,1.5 -4.75,4.75 1.25,6 ';
        $pathString .= 'c1.25,0.260418 3.25,0.75 3.5,3.75 ';
        $pathString .= 'c0.25,3 -1.5,3.25 -2.25,4 ';
        $pathString .= 'c-0.5,0.5 -1.25,2 -4.25,1.25 ';
        
        $pathString .= 'c-3,-0.75 -6,-0.75 -7.25,0 ';
        $pathString .= 'c-20.25,7 -35,13 -62,16 ';
        $pathString .= 'l-3.5,32.5 ';
        $pathString .= 'c-0.375,3.482143 1.75,5.75 5,5 ';
        $pathString .= 'c28.75,-6.634615 62.5,7.5 27.5,55 ';
        $pathString .= 'c-1.25,1.696428 -3,2 -3.75,4.5 ';
        $pathString .= 'c-0.75,2.5 -5,5.25 -8.75,5.5 ';
        $pathString .= 'c-5,-0.333333 -6.75,-2 -6.75,-12.5 ';
        $pathString .= 'c0,-4.5 -4.25,-3.75 -5,-3.25 ';
        $pathString .= 'c-3.75,2.5 -6,-2.5 -0.5,-6.5 ';
        
        $pathString .= 'c3.5,-2.545455 8.75,-5 10.5,-10 ';
        $pathString .= 'c0.625,-1.785715 4,-2.5 5,-5.5 ';
        $pathString .= 'c2.25,-3 3.5,-14.5 -7.5,-14.5 ';
        $pathString .= 'c-7.5,0 -17.5,7.5 -28.75,25 ';
        $pathString .= 'c-6.75,10.5 -12,3.75 -12.5,-2.5 ';
        $pathString .= 'c-1,-12.5 -2.75,-12.5 -3.75,-13.75 ';
        $pathString .= 'c-3,-3.75 -15,0 -21.75,10 ';
        $pathString .= 'c-1.5,2.222222 -2.5,4.5 -3.5,5 ';
        $pathString .= 'c-1,0.5 -2.75,3 -3,5 ';
        $pathString .= 'c-0.25,2 -1,4.5 -2,5 ';
        
        $pathString .= 'c-0.5,1 -0.55,2 -0.5,4 ';
        $pathString .= 'c-0.25,1.25 -0.5,5 -1.25,6 ';
        $pathString .= 'c-2,2.6666667 -2.75,3.25 -7.5,0 ';
        $pathString .= 'c-2.5,-1.710528 -5,-1.75 -10,0 ';
        $pathString .= 'c-1.75,0.6125 -3.5,1.5 -5.25,1.5 ';
        $pathString .= 'c-1.5,0 -4,-1 -3.25,-3.5 ';
        $pathString .= 'c11.25,-37.5 45,-52.5 68.5,-53.25z';

        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $ellipse = $this->dom->createElement('ellipse');
        $ellipse->setAttribute('cx', (string) $startLeftEyeX);
        $ellipse->setAttribute('cy', (string) $startLeftEyeY);
        $ellipse->setAttribute('rx', '8');
        $ellipse->setAttribute('ry', '5.5');
        $ellipse->setAttribute('fill', $foregroundRgb);
        $ellipse->setAttribute('transform', 'rotate(24 ' . $x . ' ' . $y . ')');
        $this->svg->appendChild($ellipse);
        
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $startRightEyeX);
        $circle->setAttribute('cy', (string) $startRightEyeY);
        $circle->setAttribute('r', '5.25');
        $circle->setAttribute('fill', $foregroundRgb);
        $this->svg->appendChild($circle);
    }//end simpleCoqui()

    /**
     * Native North American Sun - Not sure which tribe(s).
     *
     * @param int $x The X coordinate for the center of the square the glyph is placed in.
     * @param int $y The Y coordinate for the center of the square the glyph is placed in.
     *
     * @return void
     */
    protected function simpleSun(int $x, int $y): void
    {
        $backgroundRgb = $this->setRgbString($this->background[0], $this->background[1], $this->background[2]);
        $foregroundRgb = $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]);
        
        // vertical bars
        $pathString = 'M' . ($x - 21.5) . ',' . ($y + 68) . ' ';
        $pathString .= 'l0,-136 ';
        $pathString .= 'c0,-2.25 3,-3 4,-3 ';
        $pathString .= 'c1,0 4,0.75 4,3 ';
        $pathString .= 'l0,136 ';
        $pathString .= 'c0,2.25 -3,3 -4,3 ';
        $pathString .= 'c-1,0 -4,-0.75 -4,-4z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . ($x + 13.5) . ',' . ($y + 68) . ' ';
        $pathString .= 'l0,-136 ';
        $pathString .= 'c0,-2.25 3,-3 4,-3 ';
        $pathString .= 'c1,0 4,0.75 4,3 ';
        $pathString .= 'l0,136 ';
        $pathString .= 'c0,2.25 -3,3 -4,3 ';
        $pathString .= 'c-1,0 -4,-0.75 -4,-4z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . ($x - 9.833333) . ',' . ($y + 82.5) . ' ';
        $pathString .= 'l0,-165 ';
        $pathString .= 'c0,-2.25 3,-3 4,-3 ';
        $pathString .= 'c1,0 4,0.75 4,3 ';
        $pathString .= 'l0,165 ';
        $pathString .= 'c0,2.25 -3,3 -4,3 ';
        $pathString .= 'c-1,0 -4,-0.75 -4,-3z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . ($x + 1.833333) . ',' . ($y + 82.5) . ' ';
        $pathString .= 'l0,-165 ';
        $pathString .= 'c0,-2.25 3,-3 4,-3 ';
        $pathString .= 'c1,0 4,0.75 4,3 ';
        $pathString .= 'l0,165 ';
        $pathString .= 'c0,2.25 -3,3 -4,3 ';
        $pathString .= 'c-1,0 -4,-0.75 -4,-3z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        // horizontal bars
        $pathString = 'M' . ($x + 68) . ',' . ($y - 21.5) . ' ';
        $pathString .= 'l-136,0 ';
        $pathString .= 'c-2.25,0 -3,3 -3,4 ';
        $pathString .= 'c0,1 0.75,4 3,4 ';
        $pathString .= 'l136,0 ';
        $pathString .= 'c2.25,0 3,-3 3,-4 ';
        $pathString .= 'c 0,-1 -0.75,-4 -3,-4z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . ($x + 68) . ',' . ($y + 13.5) . ' ';
        $pathString .= 'l-136,0 ';
        $pathString .= 'c-2.25,0 -3,3 -3,4 ';
        $pathString .= 'c0,1 0.75,4 3,4 ';
        $pathString .= 'l136,0 ';
        $pathString .= 'c2.25,0 3,-3 3,-4 ';
        $pathString .= 'c 0,-1 -0.75,-4 -3,-4z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . ($x + 82.5) . ',' . ($y - 9.833333) . ' ';
        $pathString .= 'l-165,0 ';
        $pathString .= 'c-2.25,0 -3,3 -3,4 ';
        $pathString .= 'c0,1 0.75,4 3,4 ';
        $pathString .= 'l165,0 ';
        $pathString .= 'c2.25,0 3,-3 3,-4 ';
        $pathString .= 'c 0,-1 -0.75,-4 -3,-4z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . ($x + 82.5) . ',' . ($y + 1.833333) . ' ';
        $pathString .= 'l-165,0 ';
        $pathString .= 'c-2.25,0 -3,3 -3,4 ';
        $pathString .= 'c0,1 0.75,4 3,4 ';
        $pathString .= 'l165,0 ';
        $pathString .= 'c2.25,0 3,-3 3,-4 ';
        $pathString .= 'c 0,-1 -0.75,-4 -3,-4z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $x);
        $circle->setAttribute('cy', (string) $y);
        $circle->setAttribute('r', '36');
        $circle->setAttribute('fill', $foregroundRgb);
        $this->svg->appendChild($circle);
        
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $x);
        $circle->setAttribute('cy', (string) $y);
        $circle->setAttribute('r', '28');
        $circle->setAttribute('fill', $backgroundRgb);
        $this->svg->appendChild($circle);
    }//end simpleSun()
    
    /**
     * Creates path for scale around the Wagyl eye.
     *
     * Seven scales are placed around each eye.
     *
     * @param int|float $x  The x coordinate for center of eye.
     * @param int|float $y  The y coordinate for center of eye.
     * @param int|float $r  The radius of the eye circle.
     * @param int|float $rx The x radius of the ellipse that bounds the scales.
     * @param int|float $ry The y radius of the ellipse that bounds the scales.
     * @param int       $n  An integer intended to be from set [0,6] - which of the seven
     *                      scales is being drawn, with 0 corresponding to the scale at
     *                      bottom left of the y axis, then going clockwise with 6 ending
     *                      the scale at bottom right of the y axis.
     *
     * @return string       The SVG d parameter path string to return for the scale.
     */
    protected function addScalesToEye($x, $y, $r, $rx, $ry, int $n): string
    {
        //we have 10 degrees between each scale
        $StartDegrees = (95 + ($n * (360 / 7)));
        $m = $n + 1;
        $EndDegrees = (85 + ($m * (360 / 7)));
        $StartRadians = deg2rad($StartDegrees);
        $EndRadians = deg2rad($EndDegrees);
        
        $gapR = ($r + 1);
        
        // get the start point
        $StartX = ($x + ($gapR * cos($StartRadians)));
        $StartY = ($y + ($gapR * sin($StartRadians)));
        $pathString = 'M' . $StartX . ',' . $StartY . ' ';
        
        $px = $StartX;
        $py = $StartY;
        
        $newX = ($x + ($rx * cos($StartRadians)));
        $newY = ($y + ($ry * sin($StartRadians)));
        $pathString = $pathString . 'l' . ($newX - $px) . ',' . ($newY - $py) . ' ';
        $px = $newX;
        $py = $newY;
        $newX = ($x + ($rx * cos($EndRadians)));
        $newY = ($y + ($ry * sin($EndRadians)));
        $pathString = $pathString . 'a' . $rx . ',' . $ry . ' 0 0 1 ' . ($newX - $px) . ',' . ($newY - $py) . ' ';
        $px = $newX;
        $py = $newY;
        $newX = ($x + ($gapR * cos($EndRadians)));
        $newY = ($y + ($gapR * sin($EndRadians)));
        $pathString = $pathString . 'l' . ($newX - $px) . ',' . ($newY - $py) . ' ';
        $px = $newX;
        $py = $newY;
        $pathString = $pathString . 'a' . $gapR . ',' . $gapR . ' 0 0 0 ';
        $pathString = $pathString . ($StartX - $px) . ',' . ($StartY - $py) . 'z';
        return $pathString;
    }//end addScalesToEye()

    /**
     * Waugal of the Noongar (Southwest Australia Indigenous People) Dreamtime.
     *
     * The Waugal created the Swan and Canning Rivers and other waterways around present day Perth and SW of
     * Western Australia.
     *
     * @param int $x The X coordinate for the center of the square the glyph is placed in.
     * @param int $y The Y coordinate for the center of the square the glyph is placed in.
     *
     * @return void
     */
    protected function simpleWaugal(int $x, int $y): void
    {
        $backgroundRgb = $this->setRgbString($this->background[0], $this->background[1], $this->background[2]);
        $foregroundRgb = $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]);
        
        //body
        $pathString = 'M' . ($x - 69.75) . ',' . ($y + 70) . ' ';
        $pathString .= 'c3.75,2.25 21.25,4.625 23.75,4.75 ';
        $pathString .= 'l55,2.75 ';
        $pathString .= 'c42.5,2.125 47.5,-4.75 52.5,-9 ';
        $pathString .= 'c7.5,-6.375 7.5,-19.625 0,-26 ';
        $pathString .= 'c-2.5,-2.125 -5,-3.31052625 -8.5,-4.25 ';
        $pathString .= 'l-47.5,-12.75 ';
        $pathString .= 'c-1.75,-0.46973675 -5.25,-1.5 -5.25,-3 ';
        $pathString .= 'c0,-1.5 2.25,-2.239130435 5.25,-3.25 ';
        $pathString .= 'l23,-7.75 ';
        $pathString .= 'c9.5,-3.201086956 9.5,-19.798913043 0,-23 ';
        $pathString .= 'c-0.741935484,-0.25 -11.25,-2.5 -10.5,-12.75 ';
        $pathString .= 'c0.5,-6.833333333 2.25,-17.5 2.5,-27.25 ';
        $pathString .= 'c0.25,-9.75 -2.25,-12.5 -4,-14.25 ';
        $pathString .= 'c-2,-2 -5.5,-4.5 -7.75,-5.25 ';
        
        $pathString .= 'l0,-6.5 ';
        $pathString .= 'l4.25,-4.5 ';
        $pathString .= 'l-1.75,-1.5 ';
        $pathString .= 'l-3.75,3.625 ';
        $pathString .= 'l-3.75,-3.625 ';
        $pathString .= 'l-1.75,1.5 ';
        $pathString .= 'l4,4 ';
        $pathString .= 'l0,7 ';
        
        $pathString .= 'c-2.25,0.5 -6.25,2.5 -9,4.75 ';
        $pathString .= 'c-2.25,1.840909091 -3.5,7.5 -4,10 ';
        $pathString .= 'c-2.25,11.25 -1.75,24.5 0,32.75 ';
        $pathString .= 'c1.25,5.892857143 3,7.5 3.75,11.25 ';
        $pathString .= 'c1,5 -2.5,7 -7.5,8.5 ';
        $pathString .= 'c-7.5,2.25 -16.25,4.5 -25,10 ';
        $pathString .= 'c-12.5,7.857142857 -11.25,27.5 3.75,34.5 ';
        $pathString .= 'c1.875,0.875 5.25,2.0125 7,2.5 ';
        $pathString .= 'l35,9.75 ';
        $pathString .= 'c2.5,0.696428572 3.25,1.75 3.25,2.5 ';
        $pathString .= 'c0,1.75 -1,2.885416667 -3.5,3.25 ';
        $pathString .= 'l-60,8.75 ';
        $pathString .= 'c-6.5,0.947916667 -13,2.25 -19.5,2.5z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        // add color to tip of tongue
        $pathString = 'M' . ($x + 8.041666667) . ',' . ($y - 76.75) . ' ';
        $pathString .= 'l4.958333333,-5.25 ';
        $pathString .= 'l-1.75,-1.5 ';
        $pathString .= 'l-3.75,3.625 ';
        $pathString .= 'l-3.75,-3.625 ';
        $pathString .= 'l-1.75,1.5 ';
        $pathString .= 'l5.25,5.25 ';
        $pathString .= 'c0.25,0.25 0.5,0.352941177 0.791666667,0z ';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $path->setAttribute('fill-opacity', '0.7');
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        // left nostril
        $sx = $x + 5;
        $sy = $y - 66.5;
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $sx);
        $circle->setAttribute('cy', (string) $sy);
        $circle->setAttribute('r', '0.75');
        $circle->setAttribute('fill', $backgroundRgb);
        $circle->setAttribute('fill-opacity', '0.6');
        $this->svg->appendChild($circle);
        // right nostril
        $sx = $x + 9;
        $sy = $y - 66.25;
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $sx);
        $circle->setAttribute('cy', (string) $sy);
        $circle->setAttribute('r', '0.75');
        $circle->setAttribute('fill', $backgroundRgb);
        $circle->setAttribute('fill-opacity', '0.6');
        $this->svg->appendChild($circle);
        
        // left eye
        $sx = $x - 0.25;
        $sy = $y - 47.25;
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $sx);
        $circle->setAttribute('cy', (string) $sy);
        $circle->setAttribute('r', '2.5');
        $circle->setAttribute('fill', $backgroundRgb);
        $this->svg->appendChild($circle);
        
        for ($i=0; $i<7; $i++) {
            $scalePath = $this->addScalesToEye($sx, $sy, 2.5, 5.5, 8.5, $i);
            $path = $this->dom->createElement('path');
            $path->setAttribute('stroke', 'none');
            $path->setAttribute('fill', $backgroundRgb);
            $path->setAttribute('fill-opacity', '0.5');
            $path->setAttribute('d', $scalePath);
            $this->svg->appendChild($path);
        }
        // right eye
        $sx = $x + 12.5;
        $sy = $y - 47;
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $sx);
        $circle->setAttribute('cy', (string) $sy);
        $circle->setAttribute('r', '2.5');
        $circle->setAttribute('fill', $backgroundRgb);
        $this->svg->appendChild($circle);
        
        for ($i=0; $i<7; $i++) {
            $scalePath = $this->addScalesToEye($sx, $sy, 2.5, 5.5, 8.5, $i);
            $path = $this->dom->createElement('path');
            $path->setAttribute('stroke', 'none');
            $path->setAttribute('fill', $backgroundRgb);
            $path->setAttribute('fill-opacity', '0.5');
            $path->setAttribute('d', $scalePath);
            $this->svg->appendChild($path);
        }
        
        // first hole
        $pathString = 'M' . ($x + 2) . ',' . ($y - 12) . ' ';
        $pathString .= 'l-1.2,-4 ';
        $pathString .= 'c-0.1875,-0.5 0.5,-0.504032258 0.75,-0.5 ';
        $pathString .= 'l15.5,0.25 ';
        $pathString .= 'c1.5,2.75 2,3.5 3.5,4.25 ';
        $pathString .= 'l-18.25,0z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $path->setAttribute('fill-opacity', '0.7');
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        // second hole
        $pathString = 'M' . ($x - 39.25) . ',' . ($y + 16.25) . ' ';
        $pathString .= 'c7.5,-10 12.5,-13.5 23.75,-15.5 ';
        $pathString .= 'c4.5,-0.8 10.75,-2 13.5,-5 ';
        $pathString .= 'l5,-5 ';
        $pathString .= 'l22.5,0 ';
        $pathString .= 'c4,0 7.25,6.75 7.25,8.75 ';
        $pathString .= 'c0,7.5 -6.25,10 -9.75,11.25 ';
        $pathString .= 'l-17,5.5 ';
        $pathString .= 'a250,250 0 0 1 -45.25,0z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $path->setAttribute('fill-opacity', '0.7');
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        // patch mistake in second hole
        $pathString = 'M' . ($x - 29.75) . ',' . ($y + 6) . ' ';
        $pathString .= 'c6.25,-2 13.75,-5 22.25,-7 ';
        $pathString .= 'l-2,-1.75 ';
        $pathString .= 'l-20,7 ';
        $pathString .= 'l-0.25,1.75z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        // third hole
        $pathString = 'M' . ($x - 37.75) . ',' . ($y + 19) . ' ';
        $pathString .= 'l30.75,0.5 ';
        $pathString .= 'c2.25,0.036585366 1.5,1.75 1.25,2.25 ';
        $pathString .= 'c-0.75,1.5 -2.25,1.978070175 -3.5,2 ';
        $pathString .= 'l-28.5,-0.5 ';
        $pathString .= 'c-0.5,-0.00877193 -1.25,-1.5 -1.25,-2 ';
        $pathString .= 'c0,0.5 0.25,-2.233739837 1.25,-2.25z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $path->setAttribute('fill-opacity', '0.7');
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        // fourth hole
        $pathString = 'M' . ($x + 21.25) . ',' . ($y + 50.5) . ' ';
        
        $pathString .= 'l 37.5,0.125 ';
        $pathString .= 'c 1.25,0.004166667 2.25,1.5 2.25,2.25 ';
        $pathString .= 'c 0,1 -1,2.494604317 -2.5,2.5 ';
        $pathString .= 'l -34.75,-0.125 ';
        $pathString .= 'c -1,-0.003597122 -3.25,-1.5 -3.25,-3 ';

        $pathString .= 'c 0,-0.5 0.25,-1.748333333 0.75,-1.75z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $path->setAttribute('fill-opacity', '0.7');
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        // fifth hole
        $pathString = 'M' . ($x - 51.25) . ',' . ($y + 70.75) . ' ';
        
        $pathString .= 'c 25,-5 45,-7.5 63.75,-10 ';
        $pathString .= 'l 3.25,-3.25 ';
        $pathString .= 'l 42.5,0.25 ';
        $pathString .= 'l 3,3 ';
        $pathString .= 'l -3.25,3.25 ';
        $pathString .= 'l -0.5,-0.5 ';
        $pathString .= 'a 50,50 0 0 1 -10.75,10.5 ';
        $pathString .= 'c -17.5,2.25 -50,1.25 -85,0 ';

        $pathString .= 'l -0.5,0.5 ';

        $pathString .= 'l -12.5,-3.75z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $path->setAttribute('fill-opacity', '0.7');
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);

        // The lines - lots and lots and fucking lots of lines to add
        $this->svgStrokePath(($x - 42.25), ($y + 17.75), 'l15.75,-15', $foregroundRgb, '1');
        $this->svgStrokePath(($x - 34), ($y + 17.75), 'l20.25,-19', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x - 25.25), ($y + 17.75), 'l29.5,-28', $foregroundRgb, '1');
        $this->svgStrokePath(($x - 17), ($y + 18.25), 'l30.25,-28.75', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x - 7), ($y + 17.75), 'l29.25,-27.75', $foregroundRgb, '1');
        
        $this->svgStrokePath(($x + 2), ($y + 17.75), 'l27.25,-25.5', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x + 15), ($y + 14), 'l6,-5.625', $foregroundRgb, '0.75');
        $this->svgStrokePath(($x + 21.5), ($y + 8.875), 'l11.375,-11.125', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x - 30.75), ($y + 18), 'l-7.25,-6.75', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x - 22.5), ($y + 18.25), 'l-12,-11.5', $foregroundRgb, '1');
        
        $this->svgStrokePath(($x - 13.75), ($y + 18.25), 'l-15,-14.25', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x - 5.5), ($y + 18.25), 'l-17.75,-17', $foregroundRgb, '1');
        $this->svgStrokePath(($x + 3), ($y + 18.25), 'l-20.25,-19.25', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x + 10), ($y + 16), 'l-20,-19', $foregroundRgb, '1');
        $this->svgStrokePath(($x + 17), ($y + 13.75), 'l-20,-19', $foregroundRgb, '1.5');
        
        $this->svgStrokePath(($x + 23.75), ($y + 11.25), 'l-21.5,-20.5', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x + 30), ($y + 8.75), 'l-20,-19', $foregroundRgb, '1');
        $this->svgStrokePath(($x + 33.75), ($y + 3.25), 'l-14.5,-13.75', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x - 29.75), ($y + 75), 'l11.5,-11', $foregroundRgb, '1');
        $this->svgStrokePath(($x - 21.75), ($y + 75.375), 'l12.75,-13', $foregroundRgb, '1.5');

        $this->svgStrokePath(($x - 14), ($y + 75.75), 'l15.75,-15', $foregroundRgb, '1');
        $this->svgStrokePath(($x - 5.5), ($y + 76), 'l16.75,-16.25', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x + 2.75), ($y + 76.25), 'l20.75,-20', $foregroundRgb, '1');
        $this->svgStrokePath(($x + 11.5), ($y + 76.5), 'l20.75,-20', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x + 20.25), ($y + 76.5), 'l20.75,-20', $foregroundRgb, '1.5');
        
        $this->svgStrokePath(($x + 28.75), ($y + 76.5), 'l20.75,-20', $foregroundRgb, '1');
        $this->svgStrokePath(($x + 37.55), ($y + 76), 'l20.75,-19.5', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x - 15), ($y + 75.5), 'l-11,-10.75', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x - 6.75), ($y + 76), 'l-11.5,-12', $foregroundRgb, '1');
        $this->svgStrokePath(($x + 1.75), ($y + 76.25), 'l-13.5,-13.5', $foregroundRgb, '1.5');
        
        $this->svgStrokePath(($x + 1.75), ($y + 69), 'l-7,-7.25', $foregroundRgb, '1');
        $this->svgStrokePath(($x + 10.5), ($y + 76.5), 'l-7.75,-8', $foregroundRgb, '1');
        $this->svgStrokePath(($x + 10.5), ($y + 69), 'l-8.25,-8.5', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x + 19.25), ($y + 76.75), 'l-8.25,-8.5', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x + 23.5), ($y + 73.5), 'l-12.5,-13.75', $foregroundRgb, '1.5');
        
        $this->svgStrokePath(($x + 28), ($y + 76.5), 'l-4,-4.25', $foregroundRgb, '1');
        $this->svgStrokePath(($x + 23.25), ($y + 65.25), 'l-8.25,-8.5', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x + 35.75), ($y + 76), 'l-11.5,-12', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x + 32.25), ($y + 65), 'l-8.25,-8.5', $foregroundRgb, '1');
        $this->svgStrokePath(($x + 43.75), ($y + 75.25), 'l-10.75,-11.25', $foregroundRgb, '1');
        
        $this->svgStrokePath(($x + 36), ($y + 61.25), 'l-4.5,-4.75', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x + 50), ($y + 73), 'l-12.5,-13', $foregroundRgb, '1.5');
        $this->svgStrokePath(($x + 54.5), ($y + 69.5), 'l-12.5,-13', $foregroundRgb, '1');
        $this->svgStrokePath(($x + 59.5), ($y + 66.25), 'l-9,-9.5', $foregroundRgb, '1');
        $this->svgStrokePath(($x - 34.75), ($y + 72.25), 'l6.75,-6.75', $foregroundRgb, '1');
        
        //only four in this cluster
        $this->svgStrokePath(($x - 32.275), ($y + 74.725), 'l-7,-7', $foregroundRgb, '0.75');
        $this->svgStrokePath(($x - 35.75), ($y + 71.75), 'l-4.5,-4.5', $foregroundRgb, '0.75');
        $this->svgStrokePath(($x - 22.75), ($y + 75.25), 'l-7.875,-7.375', $foregroundRgb, '0.75');
        $this->svgStrokePath(($x - 27), ($y + 72), 'l-6.5,-6.087301587', $foregroundRgb, '1');
        
        //special path - fill, not stroke
        $pathString = 'M' . ($x - 40) . ',' . ($y + 74.5) . ' ';
        $pathString .= 'l3.8125,-3.8125 ';
        $pathString .= 'l1.25,1.25 ';
        $pathString .= 'l-2.5625,2.5625 ';
        $pathString .= 'l-1.25,0.375 ';
        $pathString .= 'l-1.25,-0.375z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
    }//end simpleWaugal()
    
    /**
     * The Constructor. Creates the SVG that is to be served.
     *
     * @param string $hash    Intended to be a hex representation of a 128-bit hash but any string will do.
     * @param int    $size    The requested CSS pixel width/height for display. If less than 120 it gets
     *                        redefined as 600 svg pixels here, otherwise redefined as 800 svg pixels here.
     * @param bool   $devel   For testing purposes.
     * @param bool   $example When true, $hash is ignored and the 16 parameters are randomly generated
     *                        but to never repeat the same mod 32 result (so no glyphs repeat).
     *
     * @psalm-suppress PossiblyNullPropertyAssignmentValue
     */
    public function __construct(string $hash, int $size, bool $devel = false, bool $example = false)
    {
        if ($size < 120) {
            $size = 600;
        } else {
            $size = 800;
        }
        if ($devel) {
            $this->devel = true;
        }
        $this->dom = new \DOMDocument("1.0", "UTF-8");
        //$this->dom->formatOutput = true;
        if ($size === 600) {
            // @codingStandardsIgnoreLine
            $docstring = '<?xml version="1.0"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="600" height="600" viewBox="0 0 600 600"/>';
        } else {
            // @codingStandardsIgnoreLine
            $docstring = '<?xml version="1.0"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="800" height="800" viewBox="0 0 800 800"/>';
        }
        $this->dom->loadXML($docstring);
        $this->svg = $this->dom->getElementsByTagName('svg')->item(0);
        
        $this->hashToParameters($hash, $example);
        
        //$this->backgroundRgb = $this->setRgbString($this->background[0], $this->background[1], $this->background[2]);
        //$this->foregroundRgb = $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]);
        
        $this->drawCanvas($size, $this->backgroundRgb);
        
        // The sixteen squares
        for ($i=0; $i<4; $i++) {
            for ($j=0; $j<4; $j++) {
                $byteN = (4 * $j) + $i;
                $byte = $this->parameters[$byteN];
                $x = (200 * $i) + 100;
                $y = (200 * $j) + 100;
                $mod = $byte % 32;
                if ($example) {
                    // we only have ten glyphs at present
                    $mod = $byte % 10;
                }
                $addGlyph = true;
                if ($size === 600) {
                    if (($i === 3) || ($j === 3)) {
                        $addGlyph = false;
                    }
                }
                if ($addGlyph) {
                    switch ($mod) {
                        case 0:
                            $this->simpleTriquetra($x, $y);
                            break;
                        case 1:
                            $this->simpleFertility($x, $y);
                            break;
                        case 2:
                            $this->simpleYinYang($x, $y);
                            break;
                        case 3:
                            $this->simpleAsaseYeDuru($x, $y);
                            break;
                        case 4:
                            $this->simpleElvinStar($x, $y);
                            break;
                        case 5:
                            $this->simpleTurtle($x, $y);
                            break;
                        case 6:
                            $this->simpleBoaMeNaMeMmoaWo($x, $y);
                            break;
                        case 7:
                            $this->simpleCoqui($x, $y);
                            break;
                        case 8:
                            $this->simpleSun($x, $y);
                            break;
                        case 9:
                            $this->simpleWaugal($x, $y);
                            break;
                        default:
                            // placeholder for glyphs not yet created
                            //$this->simpleCircle($x, $y);
                            $this->simpleTurtle($x, $y);
                            break;
                    }
                }
            }
        }
        $this->addFrame($size, 'rgb(123,123,123)', 'rgb(80,80,80)');
        $this->addGenerationDateComment();
    }//end __construct()
}//end class

// uncomment this block for testing file as standalone program
$foo = random_bytes(32);
$foo = base64_encode($foo);
//$bar = new PictoGlyph($foo, 120, true);
$bar = new PictoGlyph($foo, 120, false, true);
$bar->sendContent();
exit;

?>