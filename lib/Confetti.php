<?php
declare(strict_types=1);

/**
 * Generates a confetti avatar SVG file. This will be the default Groovytar that
 * is served when the server does not understand the requested type of identicon
 * and the user does not have a custom identicon they want to use.
 *
 * @package AWonderPHP/Groovytar
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/Groovytar
 */

namespace AWonderPHP\Groovytar;

/**
 * Confetti Generation
 */
class Confetti
{
    /**
     * The parameters, an array of 16 sets of integers between 0 and 255 inclusive
     *
     * @var array
     */
    protected $parameters = array();
    
    /**
     * Half a hex bite, determines the order the confetti is splattered on the image.
     * Set by the hashToParameters method, used by constructor when constructing the
     * SVG.
     */
    protected $order;
    
    /**
     * The dom object. Created by the constructor.
     *
     */
    protected $dom;
    
    /**
     * The root SVG node. Created by the constructor.
     *
     */
    protected $svg;
    
    /**
     * Generate a set of parameters from a given hex hash.
     *
     * @param string $hash The hex hash to generate parameters from.
     *
     * @return void
     */
    protected function hashToParameters(string $hash): void
    {
        if (! ctype_xdigit($hash)) {
            // like should never happen in use but...
            $hash = md5($hash);
        }
        $raw = hex2bin($hash);
        // using ripemd160 because why the frack not?
        $raw = hash('ripemd160', $raw, true);
        // using tiger because why the frack not?
        $hash128bit=hash('tiger128,4', $raw, false);
        $this->parameters = array();
        for ($i=0; $i<16; $i++) {
            $n = 2 * $i;
            $this->parameters[] = hexdec(substr($hash128bit, $n, 2));
        }
        // determine order of confetti splattering
        $secondHash = hash('ripemd128', $raw, false);
        $this->order = substr($secondHash, 8, 1);
    }//end hashToParameters()

    /**
     * Generates the frame around the image
     *
     * @return void
     */
    protected function addFrame(): void
    {
        $path = $this->dom->createElement('path');
        $pathString = 'M0,0 l800,0 l0,800 l-7,-7 l0,-786 l-786,0z';
        $path->setAttribute('d', $pathString);
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', 'rgb(123,123,123');
        $this->svg->appendChild($path);
        $path = $this->dom->createElement('path');
        $pathString = 'M0,0 l0,800 l800,0 l-7,-7 l-786,0 l0,-786z';
        $path->setAttribute('d', $pathString);
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', 'rgb(80,80,80');
        $this->svg->appendChild($path);
    }//end addFrame()

    /**
     * Modular math, adds $incr to $input.
     *
     * @param int $input The integer to be added to.
     * @param int $incr  The integer to add.
     *
     * @return int The result of the addition mod 16
     */
    protected function wrapAdd(int $input, int $incr): int
    {
        $rs = $input + $incr;
        if ($rs > 15) {
            $rs = $rs % 16;
        }
        return intval($rs);
    }//end wrapAdd()

    /**
     * Generates an SVG RGB string.
     *
     * @param int $start The position in the parameters array to use as starting point.
     *
     * @return string The SVG compliant RGB string.
     */
    protected function setRgbString($start)
    {
        $string = 'rgb(';
        $string .= $this->parameters[$this->wrapAdd($start, 4)];
        $string .= ',';
        $string .= $this->parameters[$this->wrapAdd($start, 6)];
        $string .= ',';
        $string .= $this->parameters[$this->wrapAdd($start, 9)];
        $string .= ')';
        return $string;
    }//end setRgbString()

    /**
     * Generates a circle of specified radius.
     *
     * @param int $radius The radius of the circle, well, a hint - actual radius
     *                    may differ.
     * @param int $start  The position in the parameters array to use as starting point.
     *
     * @return void
     */
    protected function addCircle(int $radius, int $start): void
    {
        if ($start < 0) {
            $start = abs($start);
        }
        if ($radius < 0) {
            $radius = abs($radius);
        }
        if ($start > 16) {
            $start = $start % 16;
        }
        if ($radius > 150) {
            $radius = $radius % 150;
        }
        $radius = $radius + 50;
        $swidth = intval(0.08 * $radius);
        
        // get the XY coordinate
        $centerX = intdiv($this->parameters[$start], 16);
        $centerY = $this->parameters[$start] % 16;
        
        $centerX = intval(round($centerX * (800 / 16)));
        $centerY = intval(round($centerY * (800 / 16)));
        
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $centerX);
        $circle->setAttribute('cy', (string) $centerY);
        $circle->setAttribute('r', (string) $radius);
        $circle->setAttribute('stroke-width', (string) $swidth);
        $circle->setAttribute('stroke-opacity', '0.4');
        $circle->setAttribute('stroke', $this->setRgbString($start));
        $circle->setAttribute('fill-opacity', '0.6');
        $circle->setAttribute('fill', $this->setRgbString(($start + 9)));
        $this->svg->appendChild($circle);
    }//end addCircle()

    /**
     * Generates a diamond shape.
     *
     * @param int $radius Misleading, not actually a radius. Does impact the size however.
     * @param int $start  The position in the parameters array to use as starting point.
     *
     * @return void
     */
    protected function addDiamond(int $radius, int $start): void
    {
        // logic bug that sometimes results in the diamond off screen, that's okay
        $degrees = $this->parameters[$this->wrapAdd($start, 12)];
        $degrees = $degrees % 90;
        if ($radius < 0) {
            $radius = abs($start);
        }
        if ($radius > 150) {
            $radius = $radius % 150;
        }
        $short = $radius + 120;
        $long = $short + ($this->parameters[$start] % 60);
        $swidth = intval(0.12 * $radius);
        
        // get the XY coordinate
        $centerX = intdiv($this->parameters[$start], 16);
        $centerY = $this->parameters[$start] % 16;
        
        $centerX = intval(round($centerX * (800 / 16)));
        $centerY = intval(round($centerY * (800 / 16)));
        
        while ($centerX < 150) {
            $centerX = $centerX + 200;
        }
        while ($centerX > 650) {
            $centerX = $centerX - 200;
        }
        while ($centerY < 150) {
            $centerY = $centerY + 200;
        }
        while ($centerY > 650) {
            $centerY = $centerY - 200;
        }
        
        $pathString = 'M' . ($centerX) . ',' . ($centerY) . ' ';
        $pathString = $pathString . 'l' . $short . ',' . $long . ' ';
        $pathString = $pathString . 'l' . $short . ',-' . $long . ' ';
        $pathString = $pathString . 'l-' . $short . ',-' . $long . ' ';
        $pathString = $pathString . 'l-' . $short . ',' . $long . 'z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke-width', (string) $swidth);
        $path->setAttribute('stroke-opacity', '0.4');
        $path->setAttribute('stroke', $this->setRgbString($start));
        $path->setAttribute('fill', $this->setRgbString(($start + 9)));
        $path->setAttribute('fill-opacity', '0.6');
        $path->setAttribute('d', $pathString);
        $path->setAttribute('transform', 'rotate(' . $degrees . ')');
        $this->svg->appendChild($path);
    }//end addDiamond()

    /**
     * Generates a triangle shape.
     *
     * @param int $radius Misleading, not actually a radius. Does impact the size however.
     * @param int $start  The position in the parameters array to use as starting point.
     *
     * @return void
     */
    protected function addTriange(int $radius, int $start): void
    {
        $degrees = $this->parameters[$this->wrapAdd($start, 12)];
        $degrees = $degrees % 180;
        if ($radius < 0) {
            $radius = abs($radius);
        }
        if ($radius > 120) {
            $radius = $radius % 120;
        }
        $radius = $radius + 90;
        $swidth = intval(0.08 * $radius);
        
        // get the XY coordinate
        $centerX = intdiv($this->parameters[$start], 16);
        $centerY = $this->parameters[$start] % 16;
        
        $centerX = intval(round($centerX * (800 / 16)));
        $centerY = intval(round($centerY * (800 / 16)));
        
        while ($centerX < 150) {
            $centerX = $centerX + 200;
        }
        while ($centerX > 650) {
            $centerX = $centerX - 200;
        }
        while ($centerY < 150) {
            $centerY = $centerY + 200;
        }
        while ($centerY > 650) {
            $centerY = $centerY - 200;
        }
        
        $pathString = 'M' . ($centerX) . ',' . ($centerY - ($radius / 2)) . ' ';
        // first leg
        $variation = $this->parameters[$start] % 7;
        if (($this->parameters[$start] % 2) === 1) {
            $angle = 30 + $variation;
        } else {
            $angle = 30 - $variation;
        }
        // find new x and y to move to
        $length = intval((2 * $radius) * 1.319507);
        $x = intval(sin(deg2rad($angle)) * $length);
        $y = intval(cos(deg2rad($angle)) * $length);
        $pathString = $pathString . 'l' . abs($x) . ',' . abs($y) . ' ';
        // find new x and y to move to
        $variation = $this->parameters[$start] % 11;
        if (($this->parameters[$start] % 2) === 1) {
            $angle = (2 * $angle) + $variation;
        } else {
            $angle = (2 * $angle) - $variation;
        }
        $length = intval((2 * $radius) * 1.2);
        $x = intval(sin(deg2rad($angle)) * $length);
        $y = intval(cos(deg2rad($angle)) * $length);
        $pathString = $pathString . 'l-' . abs($x) . ',-' . abs($y) . 'z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke-width', (string) $swidth);
        $path->setAttribute('stroke-opacity', '0.4');
        $path->setAttribute('stroke', $this->setRgbString($start));
        $path->setAttribute('fill', $this->setRgbString(($start + 2)));
        $path->setAttribute('fill-opacity', '0.6');
        $path->setAttribute('d', $pathString);
        $path->setAttribute('transform', 'rotate(' . $degrees . ')');
        $this->svg->appendChild($path);
    }//end addTriange()

    /**
     * Generates a regular polygon shape.
     *
     * @param int $radius Misleading, not actually a radius but close. Does impact the size however.
     * @param int $start  The position in the parameters array to use as starting point.
     *
     * @return void
     */
    protected function addRegularPolygon(int $radius, int $start): void
    {
        $sides = 5 + $this->parameters[$start] % 7;
        $angle = (360.0 / (float) $sides);
        
        $shift = ($this->parameters[$this->wrapAdd($start, 7)] % 12);
        $points = array();
        
        if ($radius < 0) {
            $radius = abs($radius);
        }
        if ($radius > 160) {
            $radius = $radius % 160;
        }
        $radius = $radius + 70;
        $swidth = intval(0.05 * $radius);
        
        // get the XY coordinate
        $centerX = intdiv($this->parameters[$start], 16);
        $centerY = $this->parameters[$start] % 16;
        
        $centerX = intval(round($centerX * (800 / 16)));
        $centerY = intval(round($centerY * (800 / 16)));
        
        while ($centerX < 110) {
            $centerX = $centerX + 200;
        }
        while ($centerX > 690) {
            $centerX = $centerX - 200;
        }
        while ($centerY < 110) {
            $centerY = $centerY + 200;
        }
        while ($centerY > 690) {
            $centerY = $centerY - 200;
        }
        
        for ($i=0; $i<$sides; $i++) {
            if (! isset($currentAngle)) {
                $currentAngle = $shift;
            }
            $x = intval(sin(deg2rad($currentAngle)) * $radius);
            $y = intval(cos(deg2rad($currentAngle)) * $radius);
            $pp = array();
            $pp['x'] = $x;
            $pp['y'] = $y;
            $points[] = $pp;
            $currentAngle = $currentAngle + $angle;
        }
        // now we have an array full of points relative to our center
        $pathString = '';
        for ($i=0; $i<$sides; $i++) {
            if ($i === 0) {
                $pathString = 'M' . ($centerX + $points[0]['x']) . ',' . ($centerY + $points[0]['y']) . ' ';
            } else {
                $diffX = ($points[$i]['x'] - $points[($i - 1)]['x']);
                $diffY = ($points[$i]['y'] - $points[($i - 1)]['y']);
                $pathString = $pathString . 'l' . $diffX . ',' . $diffY . ' ';
            }
        }
        $pathString = trim($pathString) . 'z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke-width', (string) $swidth);
        $path->setAttribute('stroke-opacity', '0.4');
        $path->setAttribute('stroke', $this->setRgbString($start + 4));
        $path->setAttribute('fill', $this->setRgbString(($start + 14)));
        $path->setAttribute('fill-opacity', '0.6');
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
    }//end addRegularPolygon()

    /**
     * Writes the SVG to the specified file.
     *
     * @param string $path The path to where the file is to be written.
     *
     * @return void
     */
    public function writeFile($path): void
    {
        $string = $this->dom->saveXML();
        $fp = fopen($path, 'w');
        fwrite($fp, $string);
        fclose($fp);
    }//end writeFile()

    /**
     * Sends generated SVG to requesting client.
     *
     * @return void
     */
    public function sendContent(): void
    {
        $tstamp = time();
        // The next request should get it from the filesystem and use
        // the filesystem info for expires etc.
        $expires = $tstamp + (90);
        header("HTTP/1.1 200 OK");
        header('Content-Type: image/svg+xml; charset=utf-8');
        header_remove('X-Powered-By');
        $content = $this->dom->saveXML();
        print($content);
    }//end sendContent()

    /**
     * The constructor function. The intent is for the $hash to be a hexadecimal number
     * representing a 128 bit hash but it actually doesn't matter what it is.
     *
     * @param string $hash The string to use to create the SVG file.
     */
    public function __construct(string $hash = '')
    {
        $this->dom = new \DOMDocument("1.0", "UTF-8");
        // @codingStandardsIgnoreLine
        $docstring = '<?xml version="1.0"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="800" height="800" viewBox="0 0 800 800"/>';
        $this->dom->loadXML($docstring);
        $this->svg = $this->dom->getElementsByTagName('svg')->item(0);
        $this->hashToParameters($hash);
        
        switch ($this->order) {
            case '0':
                $this->addRegularPolygon(50, 7);
                $this->addTriange(110, 14);
                $this->addCircle(40, 13);
                $this->addDiamond(40, 1);
                $this->addDiamond(30, 11);
                $this->addTriange(90, 9);
                $this->addDiamond(70, 8);
                $this->addCircle(90, 2);
                $this->addCircle(70, 4);
                $this->addDiamond(65, 15);
                $this->addRegularPolygon(42, 10);
                $this->addCircle(110, 6);
                $this->addRegularPolygon(30, 5);
                $this->addRegularPolygon(66, 12);
                $this->addTriange(120, 0);
                $this->addTriange(140, 3);
                // second round
                $this->addRegularPolygon(42, 8);
                $this->addRegularPolygon(50, 15);
                $this->addCircle(40, 7);
                $this->addCircle(90, 6);
                $this->addDiamond(40, 1);
                $this->addCircle(70, 4);
                $this->addDiamond(65, 10);
                $this->addRegularPolygon(66, 13);
                $this->addTriange(120, 3);
                $this->addDiamond(70, 14);
                $this->addTriange(90, 9);
                $this->addTriange(140, 12);
                $this->addDiamond(30, 0);
                $this->addTriange(110, 11);
                $this->addCircle(110, 2);
                $this->addRegularPolygon(30, 5);
                break;
            case '1':
                $this->addCircle(90, 4);
                $this->addRegularPolygon(42, 1);
                $this->addDiamond(40, 15);
                $this->addTriange(140, 8);
                $this->addRegularPolygon(30, 5);
                $this->addDiamond(30, 11);
                $this->addTriange(110, 3);
                $this->addCircle(40, 14);
                $this->addTriange(90, 12);
                $this->addCircle(110, 13);
                $this->addDiamond(70, 7);
                $this->addCircle(70, 6);
                $this->addDiamond(65, 0);
                $this->addRegularPolygon(66, 9);
                $this->addTriange(120, 10);
                $this->addRegularPolygon(50, 2);
                // second round
                $this->addCircle(110, 0);
                $this->addCircle(40, 1);
                $this->addTriange(120, 5);
                $this->addDiamond(40, 12);
                $this->addTriange(140, 6);
                $this->addDiamond(65, 3);
                $this->addCircle(70, 11);
                $this->addCircle(90, 14);
                $this->addRegularPolygon(66, 2);
                $this->addDiamond(70, 7);
                $this->addTriange(90, 8);
                $this->addTriange(110, 9);
                $this->addRegularPolygon(30, 10);
                $this->addRegularPolygon(42, 15);
                $this->addDiamond(30, 4);
                $this->addRegularPolygon(50, 13);
                break;
            case '2':
                $this->addDiamond(40, 15);
                $this->addDiamond(65, 1);
                $this->addTriange(140, 7);
                $this->addCircle(70, 4);
                $this->addTriange(90, 8);
                $this->addTriange(110, 3);
                $this->addDiamond(30, 9);
                $this->addRegularPolygon(50, 12);
                $this->addCircle(110, 10);
                $this->addRegularPolygon(30, 0);
                $this->addCircle(90, 11);
                $this->addTriange(120, 5);
                $this->addCircle(40, 13);
                $this->addRegularPolygon(42, 14);
                $this->addDiamond(70, 6);
                $this->addRegularPolygon(66, 2);
                // second round
                $this->addDiamond(30, 1);
                $this->addCircle(90, 12);
                $this->addRegularPolygon(50, 4);
                $this->addCircle(110, 6);
                $this->addDiamond(70, 0);
                $this->addTriange(140, 2);
                $this->addCircle(70, 9);
                $this->addRegularPolygon(42, 8);
                $this->addRegularPolygon(30, 15);
                $this->addDiamond(40, 14);
                $this->addDiamond(65, 3);
                $this->addTriange(120, 7);
                $this->addCircle(40, 13);
                $this->addRegularPolygon(66, 5);
                $this->addTriange(90, 11);
                $this->addTriange(110, 10);
                break;
            case '3':
                $this->addCircle(70, 15);
                $this->addDiamond(40, 14);
                $this->addCircle(110, 3);
                $this->addTriange(110, 0);
                $this->addTriange(120, 1);
                $this->addRegularPolygon(42, 4);
                $this->addDiamond(65, 12);
                $this->addCircle(90, 9);
                $this->addRegularPolygon(50, 7);
                $this->addCircle(40, 2);
                $this->addRegularPolygon(30, 13);
                $this->addDiamond(30, 6);
                $this->addTriange(140, 8);
                $this->addDiamond(70, 10);
                $this->addRegularPolygon(66, 5);
                $this->addTriange(90, 11);
                // second round
                $this->addCircle(90, 4);
                $this->addRegularPolygon(30, 3);
                $this->addCircle(110, 2);
                $this->addDiamond(70, 10);
                $this->addCircle(70, 7);
                $this->addTriange(90, 8);
                $this->addRegularPolygon(50, 1);
                $this->addCircle(40, 12);
                $this->addDiamond(65, 9);
                $this->addTriange(110, 13);
                $this->addDiamond(40, 6);
                $this->addDiamond(30, 11);
                $this->addTriange(120, 5);
                $this->addRegularPolygon(66, 14);
                $this->addRegularPolygon(42, 0);
                $this->addTriange(140, 15);
                break;
            case '4':
                $this->addCircle(40, 11);
                $this->addDiamond(65, 1);
                $this->addRegularPolygon(42, 6);
                $this->addRegularPolygon(50, 10);
                $this->addDiamond(30, 9);
                $this->addDiamond(70, 0);
                $this->addTriange(90, 5);
                $this->addTriange(120, 3);
                $this->addCircle(70, 4);
                $this->addTriange(110, 7);
                $this->addTriange(140, 2);
                $this->addCircle(110, 14);
                $this->addDiamond(40, 12);
                $this->addRegularPolygon(30, 8);
                $this->addCircle(90, 15);
                $this->addRegularPolygon(66, 13);
                // second round
                $this->addCircle(70, 5);
                $this->addTriange(90, 1);
                $this->addRegularPolygon(30, 2);
                $this->addTriange(110, 10);
                $this->addTriange(140, 6);
                $this->addCircle(110, 12);
                $this->addDiamond(30, 0);
                $this->addRegularPolygon(42, 11);
                $this->addRegularPolygon(66, 13);
                $this->addDiamond(40, 7);
                $this->addDiamond(65, 3);
                $this->addDiamond(70, 4);
                $this->addCircle(40, 15);
                $this->addCircle(90, 8);
                $this->addTriange(120, 14);
                $this->addRegularPolygon(50, 9);
                break;
            case '5':
                $this->addCircle(90, 9);
                $this->addTriange(120, 7);
                $this->addDiamond(30, 6);
                $this->addDiamond(65, 13);
                $this->addCircle(40, 5);
                $this->addRegularPolygon(66, 8);
                $this->addDiamond(70, 15);
                $this->addTriange(140, 10);
                $this->addTriange(90, 1);
                $this->addTriange(110, 3);
                $this->addDiamond(40, 14);
                $this->addRegularPolygon(42, 0);
                $this->addRegularPolygon(30, 2);
                $this->addCircle(70, 12);
                $this->addRegularPolygon(50, 4);
                $this->addCircle(110, 11);
                // second round
                $this->addDiamond(65, 5);
                $this->addTriange(140, 11);
                $this->addTriange(120, 0);
                $this->addCircle(90, 9);
                $this->addTriange(90, 1);
                $this->addDiamond(30, 3);
                $this->addTriange(110, 10);
                $this->addCircle(40, 13);
                $this->addRegularPolygon(30, 4);
                $this->addRegularPolygon(66, 6);
                $this->addCircle(110, 15);
                $this->addDiamond(70, 2);
                $this->addCircle(70, 7);
                $this->addDiamond(40, 14);
                $this->addRegularPolygon(42, 12);
                $this->addRegularPolygon(50, 8);
                break;
            case '6':
                $this->addCircle(70, 12);
                $this->addRegularPolygon(42, 8);
                $this->addCircle(40, 6);
                $this->addCircle(110, 0);
                $this->addCircle(90, 9);
                $this->addRegularPolygon(50, 7);
                $this->addDiamond(70, 10);
                $this->addRegularPolygon(30, 1);
                $this->addDiamond(30, 2);
                $this->addTriange(110, 13);
                $this->addDiamond(65, 11);
                $this->addTriange(90, 3);
                $this->addTriange(140, 4);
                $this->addRegularPolygon(66, 14);
                $this->addTriange(120, 15);
                $this->addDiamond(40, 5);
                // second round
                $this->addRegularPolygon(30, 4);
                $this->addCircle(40, 0);
                $this->addCircle(70, 5);
                $this->addDiamond(40, 3);
                $this->addTriange(90, 1);
                $this->addDiamond(70, 15);
                $this->addRegularPolygon(42, 12);
                $this->addCircle(90, 9);
                $this->addTriange(120, 10);
                $this->addDiamond(65, 8);
                $this->addTriange(110, 11);
                $this->addCircle(110, 2);
                $this->addRegularPolygon(66, 14);
                $this->addRegularPolygon(50, 6);
                $this->addDiamond(30, 13);
                $this->addTriange(140, 7);
                break;
            case '7':
                $this->addDiamond(40, 14);
                $this->addCircle(90, 15);
                $this->addRegularPolygon(50, 5);
                $this->addDiamond(30, 13);
                $this->addRegularPolygon(42, 6);
                $this->addTriange(110, 7);
                $this->addDiamond(65, 2);
                $this->addTriange(140, 9);
                $this->addCircle(70, 1);
                $this->addTriange(90, 4);
                $this->addDiamond(70, 8);
                $this->addCircle(110, 11);
                $this->addTriange(120, 0);
                $this->addRegularPolygon(66, 12);
                $this->addCircle(40, 3);
                $this->addRegularPolygon(30, 10);
                // second round
                $this->addCircle(70, 11);
                $this->addTriange(140, 6);
                $this->addRegularPolygon(30, 1);
                $this->addDiamond(65, 12);
                $this->addDiamond(40, 8);
                $this->addTriange(110, 2);
                $this->addRegularPolygon(42, 14);
                $this->addCircle(40, 13);
                $this->addCircle(90, 5);
                $this->addDiamond(30, 15);
                $this->addDiamond(70, 10);
                $this->addRegularPolygon(66, 3);
                $this->addTriange(120, 4);
                $this->addRegularPolygon(50, 0);
                $this->addTriange(90, 9);
                $this->addCircle(110, 7);
                break;
            case '8':
                $this->addRegularPolygon(66, 0);
                $this->addCircle(40, 2);
                $this->addCircle(90, 6);
                $this->addRegularPolygon(30, 13);
                $this->addDiamond(65, 12);
                $this->addCircle(110, 10);
                $this->addCircle(70, 7);
                $this->addDiamond(40, 5);
                $this->addRegularPolygon(42, 1);
                $this->addTriange(120, 8);
                $this->addTriange(90, 14);
                $this->addRegularPolygon(50, 4);
                $this->addDiamond(30, 9);
                $this->addTriange(110, 15);
                $this->addTriange(140, 11);
                $this->addDiamond(70, 3);
                // second round
                $this->addTriange(110, 11);
                $this->addCircle(90, 7);
                $this->addCircle(110, 9);
                $this->addTriange(140, 12);
                $this->addCircle(40, 6);
                $this->addDiamond(30, 4);
                $this->addDiamond(65, 14);
                $this->addRegularPolygon(42, 5);
                $this->addRegularPolygon(50, 3);
                $this->addRegularPolygon(30, 2);
                $this->addDiamond(70, 0);
                $this->addRegularPolygon(66, 15);
                $this->addCircle(70, 13);
                $this->addTriange(120, 1);
                $this->addDiamond(40, 8);
                $this->addTriange(90, 10);
                break;
            case '9':
                $this->addTriange(110, 5);
                $this->addDiamond(30, 10);
                $this->addRegularPolygon(50, 11);
                $this->addCircle(90, 7);
                $this->addRegularPolygon(42, 3);
                $this->addCircle(70, 2);
                $this->addRegularPolygon(66, 0);
                $this->addCircle(40, 1);
                $this->addTriange(90, 8);
                $this->addDiamond(40, 13);
                $this->addDiamond(65, 6);
                $this->addCircle(110, 14);
                $this->addTriange(120, 12);
                $this->addDiamond(70, 4);
                $this->addTriange(140, 9);
                $this->addRegularPolygon(30, 15);
                // second round
                $this->addTriange(120, 12);
                $this->addRegularPolygon(30, 15);
                $this->addDiamond(40, 5);
                $this->addCircle(40, 0);
                $this->addDiamond(70, 2);
                $this->addRegularPolygon(50, 3);
                $this->addCircle(70, 6);
                $this->addTriange(140, 10);
                $this->addCircle(110, 9);
                $this->addTriange(90, 4);
                $this->addRegularPolygon(66, 14);
                $this->addCircle(90, 7);
                $this->addDiamond(65, 13);
                $this->addRegularPolygon(42, 11);
                $this->addDiamond(30, 8);
                $this->addTriange(110, 1);
                break;
            case 'a':
                $this->addRegularPolygon(30, 1);
                $this->addTriange(120, 8);
                $this->addRegularPolygon(42, 13);
                $this->addDiamond(30, 7);
                $this->addCircle(40, 10);
                $this->addTriange(140, 9);
                $this->addCircle(90, 12);
                $this->addCircle(110, 6);
                $this->addTriange(90, 15);
                $this->addRegularPolygon(50, 2);
                $this->addDiamond(70, 14);
                $this->addRegularPolygon(66, 3);
                $this->addDiamond(65, 5);
                $this->addDiamond(40, 11);
                $this->addCircle(70, 4);
                $this->addTriange(110, 0);
                // second round
                $this->addRegularPolygon(42, 0);
                $this->addDiamond(70, 15);
                $this->addRegularPolygon(50, 1);
                $this->addCircle(110, 12);
                $this->addTriange(90, 11);
                $this->addCircle(40, 2);
                $this->addTriange(120, 9);
                $this->addTriange(110, 8);
                $this->addCircle(90, 14);
                $this->addDiamond(65, 4);
                $this->addTriange(140, 5);
                $this->addRegularPolygon(30, 13);
                $this->addRegularPolygon(66, 10);
                $this->addDiamond(30, 3);
                $this->addCircle(70, 6);
                $this->addDiamond(40, 7);
                break;
            case 'b':
                $this->addTriange(90, 6);
                $this->addTriange(140, 4);
                $this->addDiamond(70, 15);
                $this->addDiamond(30, 3);
                $this->addRegularPolygon(30, 0);
                $this->addTriange(120, 5);
                $this->addRegularPolygon(66, 2);
                $this->addRegularPolygon(42, 10);
                $this->addDiamond(40, 9);
                $this->addCircle(70, 1);
                $this->addCircle(90, 8);
                $this->addRegularPolygon(50, 12);
                $this->addTriange(110, 14);
                $this->addCircle(110, 7);
                $this->addCircle(40, 13);
                $this->addDiamond(65, 11);
                // second round
                $this->addRegularPolygon(50, 8);
                $this->addCircle(40, 7);
                $this->addDiamond(30, 1);
                $this->addRegularPolygon(42, 14);
                $this->addDiamond(40, 4);
                $this->addTriange(110, 6);
                $this->addTriange(120, 12);
                $this->addDiamond(70, 0);
                $this->addCircle(90, 9);
                $this->addTriange(140, 3);
                $this->addCircle(110, 2);
                $this->addRegularPolygon(66, 13);
                $this->addDiamond(65, 10);
                $this->addRegularPolygon(30, 11);
                $this->addCircle(70, 15);
                $this->addTriange(90, 5);
                break;
            case 'c':
                $this->addTriange(120, 8);
                $this->addCircle(110, 6);
                $this->addDiamond(70, 14);
                $this->addRegularPolygon(42, 1);
                $this->addTriange(140, 0);
                $this->addRegularPolygon(66, 4);
                $this->addDiamond(40, 13);
                $this->addDiamond(30, 12);
                $this->addCircle(90, 15);
                $this->addDiamond(65, 9);
                $this->addTriange(110, 2);
                $this->addRegularPolygon(30, 10);
                $this->addTriange(90, 11);
                $this->addRegularPolygon(50, 5);
                $this->addCircle(40, 7);
                $this->addCircle(70, 3);
                // second round
                $this->addTriange(90, 2);
                $this->addRegularPolygon(66, 10);
                $this->addTriange(140, 1);
                $this->addTriange(110, 15);
                $this->addDiamond(30, 11);
                $this->addRegularPolygon(30, 3);
                $this->addTriange(120, 7);
                $this->addDiamond(65, 5);
                $this->addCircle(110, 9);
                $this->addRegularPolygon(42, 4);
                $this->addCircle(90, 0);
                $this->addDiamond(70, 8);
                $this->addDiamond(40, 13);
                $this->addCircle(70, 6);
                $this->addCircle(40, 14);
                $this->addRegularPolygon(50, 12);
                break;
            case 'd':
                $this->addDiamond(40, 5);
                $this->addDiamond(70, 7);
                $this->addTriange(90, 3);
                $this->addRegularPolygon(42, 2);
                $this->addCircle(40, 12);
                $this->addRegularPolygon(30, 4);
                $this->addCircle(110, 0);
                $this->addDiamond(65, 8);
                $this->addDiamond(30, 14);
                $this->addCircle(90, 1);
                $this->addRegularPolygon(66, 10);
                $this->addCircle(70, 13);
                $this->addTriange(110, 9);
                $this->addTriange(140, 15);
                $this->addRegularPolygon(50, 6);
                $this->addTriange(120, 11);
                // second round
                $this->addRegularPolygon(30, 6);
                $this->addCircle(90, 4);
                $this->addTriange(90, 14);
                $this->addDiamond(65, 9);
                $this->addTriange(140, 13);
                $this->addCircle(110, 3);
                $this->addDiamond(40, 2);
                $this->addDiamond(30, 1);
                $this->addRegularPolygon(50, 5);
                $this->addRegularPolygon(66, 15);
                $this->addCircle(40, 11);
                $this->addDiamond(70, 7);
                $this->addTriange(110, 0);
                $this->addRegularPolygon(42, 8);
                $this->addTriange(120, 10);
                $this->addCircle(70, 12);
                break;
            case 'e':
                $this->addDiamond(65, 8);
                $this->addTriange(110, 4);
                $this->addCircle(40, 3);
                $this->addDiamond(30, 7);
                $this->addCircle(110, 13);
                $this->addTriange(140, 10);
                $this->addRegularPolygon(30, 14);
                $this->addTriange(90, 2);
                $this->addDiamond(70, 12);
                $this->addRegularPolygon(50, 5);
                $this->addTriange(120, 1);
                $this->addDiamond(40, 11);
                $this->addCircle(90, 6);
                $this->addRegularPolygon(42, 15);
                $this->addRegularPolygon(66, 9);
                $this->addCircle(70, 0);
                // second round
                $this->addCircle(70, 0);
                $this->addTriange(140, 5);
                $this->addTriange(90, 1);
                $this->addRegularPolygon(50, 2);
                $this->addRegularPolygon(66, 3);
                $this->addDiamond(65, 6);
                $this->addCircle(90, 11);
                $this->addDiamond(40, 4);
                $this->addDiamond(70, 10);
                $this->addDiamond(30, 8);
                $this->addTriange(110, 9);
                $this->addTriange(120, 12);
                $this->addCircle(40, 7);
                $this->addRegularPolygon(30, 13);
                $this->addRegularPolygon(42, 14);
                $this->addCircle(110, 15);
                break;
            default:
                $this->addCircle(40, 9);
                $this->addTriange(140, 10);
                $this->addCircle(70, 11);
                $this->addCircle(110, 14);
                $this->addDiamond(70, 8);
                $this->addTriange(110, 1);
                $this->addRegularPolygon(30, 5);
                $this->addRegularPolygon(50, 12);
                $this->addDiamond(65, 6);
                $this->addRegularPolygon(66, 3);
                $this->addDiamond(30, 0);
                $this->addTriange(90, 2);
                $this->addCircle(90, 4);
                $this->addDiamond(40, 15);
                $this->addRegularPolygon(42, 7);
                $this->addTriange(120, 13);
                // second round
                $this->addCircle(70, 10);
                $this->addRegularPolygon(42, 0);
                $this->addTriange(140, 4);
                $this->addTriange(110, 5);
                $this->addRegularPolygon(66, 12);
                $this->addTriange(90, 11);
                $this->addDiamond(30, 1);
                $this->addDiamond(65, 7);
                $this->addCircle(40, 6);
                $this->addDiamond(40, 3);
                $this->addTriange(120, 9);
                $this->addRegularPolygon(50, 13);
                $this->addCircle(110, 2);
                $this->addDiamond(70, 15);
                $this->addRegularPolygon(30, 14);
                $this->addCircle(90, 8);
        }
        $this->addFrame();
    }//end __construct()
}//end class

?>