<?php
declare(strict_types=1);

/**
 * Generates a Simplebit avatar SVG file.
 *
 * @package AWonderPHP/Groovytar
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/Groovytar
 */

/**
 * save for later
 *
 * B/F
 * 8e6948 / c4ac87
 * 132052 / 939689
 *
 */ 

namespace AWonderPHP\Groovytar;

/**
 * Simplebit Generation.
 * Six bytes are used for the color, 10 for the pattern.
 */
class Simplebit
{
    /**
     * The parameters, an array of 16 sets of integers between 0 and 255 inclusive
     *
     * @var array
     */
    protected $parameters = array();
    
    /**
     * The background color
     *
     * @var array
     */
    protected $background = array(69, 2, 113);
    
    /**
     * The Foreground color
     *
     * @var array
     */
    protected $foreground = array(225, 178, 89);
    
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
        // using sha384 because why the frack not?
        $raw = hash('sha384', $raw, true);
        // using tiger because why the frack not?
        $hash128bit=hash('tiger128,3', $raw, false);
        $this->parameters = array();
        for ($i=0; $i<16; $i++) {
            $n = 2 * $i;
            $this->parameters[] = hexdec(substr($hash128bit, $n, 2));
        }
        // Background Red Index
        $oneLight = false;
        if($this->parameters[0] > 115) {
            $oneLight = true;
        }
        $this->background[0] = $this->parameters[0];
        // Background Green Index
        if($this->parameters[1] <= 115) {
            $this->background[1] = $this->parameters[1];
        } else {
            if($oneLight) {
                $value = $this->parameters[1];
                while($value > 110) {
                    $value = $value - 27;
                }
                $this->background[1] = $value;
            } else {
                $oneLight = true;
                $this->background[1] = $this->parameters[1];
            }
        }
        // Background Blue Index
        if($this->parameters[2] <= 115) {
            $this->background[2] = $this->parameters[2];
        } else {
            if($oneLight) {
                $value = $this->parameters[2];
                while($value > 110) {
                    $value = $value - 23;
                }
                $this->background[2] = $value;
            } else {
                $this->background[2] = $this->parameters[2];
            }
        }
        // Foreground Blue Index
        $oneDark = false;
        if($this->parameters[3] < 140) {
            $oneDark = true;
        }
        $this->foreground[2] = $this->parameters[3];
        // Foreground Green Index
        if($this->parameters[4] >= 140) {
            $this->foreground[1] = $this->parameters[4];
        } else {
            if($oneDark) {
                $value = $this->parameters[4];
                while($value < 140) {
                    $value = $value + 37;
                }
                $this->foreground[1] = $value;
            } else {
                $oneDark = true;
                $this->foreground[1] = $this->parameters[4];
            }
        }
        // Foreground Red Index
        if($this->parameters[5] >= 140) {
            $this->foreground[0] = $this->parameters[5];
        } else {
            if($oneDark) {
                $value = $this->parameters[5];
                while($value < 140) {
                    $value = $value + 13;
                }
                $this->foreground[0] = $value;
            } else {
                $this->foreground[0] = $this->parameters[5];
            }
        }
        // okay we now have contrasting foreground and background
    }
    
    /**
     * Generates an SVG RGB string.
     *
     * @param int $r The red component.
     * @param int $g The green component.
     * @param int $b The blue component.
     *
     * @return string The SVG compliant RGB string.
     */
    protected function setRgbString($r, $g, $b)
    {
        $string = 'rgb(' . $r . ',' . $g . ',' . $b . ')';
        return $string;
    }
    
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
        $path->setAttribute('fill-opacity', '0.6');
        $path->setAttribute('fill', 'rgb(123,123,123');
        $this->svg->appendChild($path);
        $path = $this->dom->createElement('path');
        $pathString = 'M0,0 l0,800 l800,0 l-7,-7 l-786,0 l0,-786z';
        $path->setAttribute('d', $pathString);
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill-opacity', '0.6');
        $path->setAttribute('fill', 'rgb(80,80,80');
        $this->svg->appendChild($path);
    }//end addFrame()
    
    /**
     * Draw the background canvas
     *
     * @return void
     */
    protected function drawCanvas(): void
    {
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $this->setRgbString($this->background[0], $this->background[1], $this->background[2]));
        $pathString = 'M0,0 l800,0 l0,800 l-800,0 l0,-800z';
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
    }
    
    protected function simpleCircle($x, $y): void
    {
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $x);
        $circle->setAttribute('cy', (string) $y);
        $circle->setAttribute('r', '75');
        $circle->setAttribute('stroke', 'none');
        $circle->setAttribute('fill', $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]));
        $this->svg->appendChild($circle);
    }
    
    protected function simpleHeart($x, $y): void
    {
        $startX = $x;
        $startY = $y + 75;
        $pathString = 'M' . $startX . ',' . $startY . ' ';
        
    }
    
    protected function simpleTriquetra($x, $y): void
    {
        $startX = $x - 65;
        $startY = $y + 54;
        $pathString = 'M' . $startX . ',' . $startY . ' ';
        // first arc
        $pathString = $pathString . 'a 66,66 0 0 1 130,0 ';
        $pathString = $pathString . 'a 66,66 0 0 1 -65,-113 ';
        $pathString = $pathString . 'a 66,66 0 0 1 -65,113z';
        
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('fill', 'none');
        $path->setAttribute('stroke-width', '11');
        $path->setAttribute('stroke', $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]));
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
    }
    
    protected function simpleFertility($x, $y): void
    {
        $xWidth = 60;
        $yHeight = 166;
        $startX = $x - ($xWidth / 2);
        $startY = $y - ($yHeight / 2);
        
        $pathString = 'M' . $startX . ',' . $startY . ' ';
        $pathString = $pathString . 'l' . $xWidth . ',' . ($yHeight / 2) . ' ';
        $pathString = $pathString . 'l-' . $xWidth . ',' . ($yHeight / 2);
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('fill', 'none');
        $path->setAttribute('stroke-width', '9');
        $path->setAttribute('stroke', $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]));
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $startX = $x + ($xWidth / 2);
        $pathString = 'M' . $startX . ',' . $startY . ' ';
        $pathString = $pathString . 'l-' . $xWidth . ',' . ($yHeight / 2) . ' ';
        $pathString = $pathString . 'l' . $xWidth . ',' . ($yHeight / 2);
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('fill', 'none');
        $path->setAttribute('stroke-width', '11');
        $path->setAttribute('stroke', $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]));
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
    }
    
    protected function simpleYinYang($x, $y): void
    {
        //Draw the light
        $pathString = 'M' . $x . ',' . ($y - 74) . ' ';
        $pathString .= 'a37,37 0 0 1 0,74 a37,37 0 0 0 0,74 a74,74 0 0 1 0,-148z';
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]));
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        //draw the outer circle
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $x);
        $circle->setAttribute('cy', (string) $y);
        $circle->setAttribute('r', '75');
        $circle->setAttribute('stroke-width', '3');
        $circle->setAttribute('stroke', $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]));
        $circle->setAttribute('fill', 'none');
        $this->svg->appendChild($circle);
        
        // add the dots
        $yUpper = $y - 37;
        $yLower = $y + 37;
        
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $x);
        $circle->setAttribute('cy', (string) $yUpper);
        $circle->setAttribute('r', '8');
        $circle->setAttribute('stroke', 'none');
        $circle->setAttribute('fill', $this->setRgbString($this->background[0], $this->background[1], $this->background[2]));
        $this->svg->appendChild($circle);
        
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $x);
        $circle->setAttribute('cy', (string) $yLower);
        $circle->setAttribute('r', '8');
        $circle->setAttribute('stroke', 'none');
        $circle->setAttribute('fill', $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]));
        $this->svg->appendChild($circle);
        
    }
    
    /**
     * Asase Ye Duru - The Divinity of Mother Earth
     * https://www.nps.gov/afbg/learn/historyculture/asase-ye-duru.htm
     */
    protected function simpleAsaseYeDuru($x, $y): void
    {
        $startX = $x;
        $startY = $y + 75;
        
        $startX = $x;
        $startY = $y + 75;
        
        $pathString = 'M' . $x . ',' . $startY . ' ';
        $pathString .= 'q-16.5,-9.75 -30,-22.5 ';
        $pathString .= 'q-9.75,-8.25 -18,-24 ';
        $pathString .= 'a20.25,21.75 0 0 1 9.75,-27 ';
        $pathString .= 'a20.25,20.25 0 0 1 -8,-27 ';
        $pathString .= 'q3,-8.25 16.5,-24 ';
        $pathString .= 'q16.5,-16.5 30,-25.5 ';
        $pathString .= 'q21,13.5 33,25.5 ';
        $pathString .= 'q6,5.25 13.5,18.75 ';
        $pathString .= 'a21.75,21.75 0 0 1 -8.25,30 ';
        $pathString .= 'a20.25,21 0 0 1 7.5,30 ';
        $pathString .= 'q-11.25,18.75 -24,30 ';
        $pathString .= 'q-11.25,9.75 -21,15.75z ';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]));
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $startX = $x -1;
        $startY = $y -65;
        
        $pathString = 'M' . $startX. ',' . $startY . ' ';
        $pathString .= 'q21.75,15 33.75,30 ';
        $pathString .= 'q6.75,7.5 8.25,18 ';
        $pathString .= 'a15,14.25 0 0 1 -11.25,14.25 ';
        $pathString .= 'q-12,1.5 -17.25,-3.75 ';
        $pathString .= 'q-5.25,-5.25 -5.25,-8.25 ';
        $pathString .= 'q0.75,-5.25 7.5,-6 ';
        $pathString .= 'q3,-0.75 5.25,0.75 ';
        $pathString .= 'q1.5,2.25 2.25,6 ';
        $pathString .= 'a4.5,4.5 0 0 0 7.875,-1.5 ';
        $pathString .= 'a7.5,7.5 0 0 0 -1.125,-8.25 ';
        $pathString .= 'q-2.25,-3.75 -8.25,-6 ';
        $pathString .= 'a14.25,14.25 0 0 0 -8.25,1.5 ';
        $pathString .= 'q-7.5,3 -12,7.5 ';
        $pathString .= 'a18.75,18.75 0 0 0 -16.5,-9.75 ';
        $pathString .= 'q-6,-0.375 -14.25,8.625 ';
        $pathString .= 'q-2.25,3.75 -2.25,6.75 ';
        $pathString .= 'a3,3 0 0 0 3.75,2.25 ';
        $pathString .= 'q3,0 4.875,-2.625 ';
        $pathString .= 'a7.5,9 0 0 1 8.25,-6 ';
        $pathString .= 'a9,9.75 0 0 1 8.25,7.5 ';
        $pathString .= 'q0,10.5 -15,10.5 ';
        $pathString .= 'a15.75,15 0 0 1 -16.5,-12 ';
        $pathString .= 'q-0.375,-3.375 1.5,-7.5 ';
        $pathString .= 'q4.5,-11.25 15,-22.5 ';
        $pathString .= 'q16.875,-18 21.375,-19.5z ';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $this->setRgbString($this->background[0], $this->background[1], $this->background[2]));
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
    }
    
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
    
    public function __construct($hash)
    {
        $this->dom = new \DOMDocument("1.0", "UTF-8");
        // @codingStandardsIgnoreLine
        $docstring = '<?xml version="1.0"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="800" height="800" viewBox="0 0 800 800"/>';
        $this->dom->loadXML($docstring);
        $this->svg = $this->dom->getElementsByTagName('svg')->item(0);
        $this->hashToParameters($hash);
        $this->drawCanvas();
        
        // The sixteen shapes
        for($i=0; $i<4; $i++) {
            for($j=0; $j<4; $j++) {
                $byteN = (4 * $j) + $i;
                $byte = $this->parameters[$byteN];
                $x = (200 * $i) + 100;
                $y = (200 * $j) + 100;
                $mod = $byte % 4;
                switch($mod) {
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
                    default:
                        //$this->simpleCircle($x, $y);
                        $this->simpleAsaseYeDuru($x, $y);
                        break;
                }
            }
        }
        $this->addFrame();
    }
}

// testing testing 123

$foo = random_bytes(32);
$foo = base64_encode($foo);
$bar = new Simplebit($foo);
$bar->sendContent();
exit;


?>