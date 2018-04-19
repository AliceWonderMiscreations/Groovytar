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

/**
 * PictoGlyph Generation.
 * color selection currently often results in poor colors, I'm working on that...
 */
class PictoGlyph
{
    /**
     * Are we running in devel / test mode ???
     *
     * @var boolean
     */
    protected $devel = false;
     
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
     * Not yet used, will be an array of 128 different color combinations.
     * TODO: check for color combos that are too similar
     *
     * @param int $input A number between 0 and 255.
     *
     * @return void
     */
    protected function selectColorCombos(int $input): void
    {
        // WCAG Large Text Tests
        $colorCombos = array();
        /*
        // 1-16
        $colorCombos[] = array('861d57', '9fc464'); // WCAG PASS AAA
        $colorCombos[] = array('132052', '939689'); // WCAG PASS AAA
        $colorCombos[] = array('8d343a', 'a1e35f'); // WCAG PASS AAA
        $colorCombos[] = array('2303c8', 'a4ad58'); // WCAG PASS AAA
        $colorCombos[] = array('0e10ca', 'd3a21f'); // WCAG PASS AAA
        $colorCombos[] = array('c3155e', '87fffa'); // WCAG PASS AAA
        $colorCombos[] = array('98161a', '4bd8e2'); // WCAG PASS AA FAIL AAA
        $colorCombos[] = array('691f4b', 'ed9439'); // WCAG PASS AAA
        $colorCombos[] = array('00505c', 'ecaf5f'); // WCAG PASS AAA
        $colorCombos[] = array('4c4da4', '8efb7f'); // WCAG PASS AAA
        $colorCombos[] = array('202c17', '3aaefc'); // WCAG PASS AAA
        $colorCombos[] = array('421225', 'eded0f'); // WCAG PASS AAA
        $colorCombos[] = array('85345f', '97d912'); // WCAG PASS AAA
        $colorCombos[] = array('961d07', 'b8c5fa'); // WCAG PASS AAA
        $colorCombos[] = array('0f0f3d', 'c8a12a'); // WCAG PASS AAA
        $colorCombos[] = array('082f4f', 'ec4ff8'); // WCAG PASS AAA
        // 17-32
        $colorCombos[] = array('b1336d', 'd1fde2'); // WCAG PASS AAA
        $colorCombos[] = array('a80b10', 'a7d57b'); // WCAG PASS AAA
        $colorCombos[] = array('96381d', 'b5f803'); // WCAG PASS AAA
        $colorCombos[] = array('025f53', 'd4cc82'); // WCAG PASS AAA
        $colorCombos[] = array('1b5535', 'bcfd86'); // WCAG PASS AAA
        $colorCombos[] = array('3f2e22', 'd8c8b0'); // WCAG PASS AAA
        $colorCombos[] = array('0c3113', 'f8a0c9'); // WCAG PASS AAA
        $colorCombos[] = array('245e21', 'f6f5ed'); // WCAG PASS AAA
        $colorCombos[] = array('663838', 'c2f65a'); // WCAG PASS AAA
        $colorCombos[] = array('8a310a', 'd5f99a'); // WCAG PASS AAA
        $colorCombos[] = array('640c4d', '6dd8f8'); // WCAG PASS AAA
        $colorCombos[] = array('193e19', 'eeb8ef'); // WCAG PASS AAA
        $colorCombos[] = array('0c402e', 'fcc373'); // WCAG PASS AAA
        $colorCombos[] = array('5b4429', 'd0ece6'); // WCAG PASS AAA
        $colorCombos[] = array('2a4735', 'b7a3dc'); // WCAG PASS AAA
        $colorCombos[] = array('ae0f2c', 'b3e3a8'); // WCAG PASS AAA
        // 33-48
        $colorCombos[] = array('9a4c4d', 'efe880'); // WCAG PASS AAA
        $colorCombos[] = array('67527a', '8be3c3'); // WCAG PASS AAA
        $colorCombos[] = array('590d60', 'd1ef85'); // WCAG PASS AAA
        $colorCombos[] = array('d89f36', '35355f'); // WCAG PASS AAA
        $colorCombos[] = array('590e2c', '8eb829'); // WCAG PASS AAA
        $colorCombos[] = array('6f5006', 'f2c57d'); // WCAG PASS AAA
        $colorCombos[] = array('144b5c', 'ecbc7b'); // WCAG PASS AAA
        $colorCombos[] = array('4b5f0d', '95f24f'); // WCAG PASS AAA
        $colorCombos[] = array('0c5f3b', 'b5dda6'); // WCAG PASS AAA
        $colorCombos[] = array('634f56', 'f9c56c'); // WCAG PASS AAA
        $colorCombos[] = array('6d2a1c', 'afa4f4'); // WCAG PASS AAA
        $colorCombos[] = array('7b033c', '4fcd8f'); // WCAG PASS AAA
        $colorCombos[] = array('2733af', '65fdf8'); // WCAG PASS AAA
        $colorCombos[] = array('3700e9', '8fe474'); // WCAG PASS AAA
        $colorCombos[] = array('751a5b', 'd5b67b'); // WCAG PASS AAA
        $colorCombos[] = array('103c88', 'f3ed69'); // WCAG PASS AAA
        // 49-64
        $colorCombos[] = array('a24320', 'a9fc4a'); // WCAG PASS AAA
        $colorCombos[] = array('1d3987', 'd494f9'); // WCAG PASS AAA
        $colorCombos[] = array('1c2410', 'b49183'); // WCAG PASS AAA
        $colorCombos[] = array('834458', '8ce800'); // WCAG PASS AAA
        $colorCombos[] = array('96305a', '97e0e8'); // WCAG PASS AAA
        $colorCombos[] = array('c44703', 'f5f5f5'); // WCAG PASS AAA
        $colorCombos[] = array('5cd417', '70149f'); // WCAG PASS AAA
        $colorCombos[] = array('453513', 'bd91de'); // WCAG PASS AAA
        $colorCombos[] = array('294216', '95bd1e'); // WCAG PASS AAA
        $colorCombos[] = array('8a0261', 'f5bdb8'); // WCAG PASS AAA
        $colorCombos[] = array('814d04', '84e5a3'); // WCAG PASS AAA
        $colorCombos[] = array('674410', 'b8b7f0'); // WCAG PASS AAA
        $colorCombos[] = array('7b016b', 'b8b432'); // WCAG PASS AAA
        $colorCombos[] = array('b92736', 'e7ee96'); // WCAG PASS AAA
        $colorCombos[] = array('39062a', 'a36fce'); // WCAG PASS AAA
        $colorCombos[] = array('2efa44', '97279a'); // WCAG PASS AAA
        */
        
        // sorted color combos
        
        //Pink:
        $colorCombos[] = array('c3155e', '87fffa'); // MediumVioletRed || Aquamarine
        $colorCombos[] = array('b1336d', 'd1fde2'); // MediumVioletRed || LightCyan
        
        //Red:
        $colorCombos[] = array('961d07', 'b8c5fa'); // DarkRed         || LightSteelBlue
        
        $colorCombos[] = array('98161a', '4bd8e2'); // FireBrick       || MediumTurquoise
        
          // These three very similar
        //$colorCombos[] = array('a80b10', 'a7d57b'); // FireBrick       || DarkKhaki
        //$colorCombos[] = array('b92736', 'e7ee96'); // FireBrick       || Khaki
        //$colorCombos[] = array('ae0f2c', 'b3e3a8'); // FireBrick       || PaleGoldenrod
                
        //Orange:
        $colorCombos[] = array('c44703', 'f5f5f5'); // OrangeRed       || WhiteSmoke
        
        //Brown:
        $colorCombos[] = array('691f4b', 'ed9439'); // Brown           || Peru
        $colorCombos[] = array('96305a', '97e0e8'); // Brown           || PowderBlue
        $colorCombos[] = array('8d343a', 'a1e35f'); // Brown           || YellowGreen
        $colorCombos[] = array('7b033c', '4fcd8f'); // Maroon          || MediumAquamarine
        $colorCombos[] = array('421225', 'eded0f'); // Maroon          || Yellow
        $colorCombos[] = array('590e2c', '8eb829'); // Maroon          || YellowGreen
        $colorCombos[] = array('d89f36', '35355f'); // Peru            || DarkSlateGray

        /* These all differ enough */
        $colorCombos[] = array('96381d', 'b5f803'); // SaddleBrown     || GreenYellow
        $colorCombos[] = array('834458', '8ce800'); // SaddleBrown     || LawnGreen || Maroon SaddleBrown
        $colorCombos[] = array('814d04', '84e5a3'); // SaddleBrown     || LightGreen
        $colorCombos[] = array('674410', 'b8b7f0'); // SaddleBrown     || LightSteelBlue
        $colorCombos[] = array('6d2a1c', 'afa4f4'); // SaddleBrown     || LightSteelBlue || Maroon SaddleBrown
        $colorCombos[] = array('8a310a', 'd5f99a'); // SaddleBrown     || PaleGoldenrod
        $colorCombos[] = array('6f5006', 'f2c57d'); // SaddleBrown     || Tan
        $colorCombos[] = array('9a4c4d', 'efe880'); // Sienna          || Khaki
        $colorCombos[] = array('a24320', 'a9fc4a'); // Sienna          || GreenYellow
        
        
        //Green:
        $colorCombos[] = array('202c17', '3aaefc'); // Dark Green      || DodgerBlue
        $colorCombos[] = array('0c3113', 'f8a0c9'); // Dark Green      || LightPink
        $colorCombos[] = array('1c2410', 'b49183'); // Dark Green      || RosyBrown
        $colorCombos[] = array('193e19', 'eeb8ef'); // Dark Green      || Thistle
        $colorCombos[] = array('5b4429', 'd0ece6'); // DarkOliveGreen  || Gainsboro       ||Browner
        $colorCombos[] = array('4b5f0d', '95f24f'); // DarkOliveGreen  || GreenYellow     ||Greener
        $colorCombos[] = array('663838', 'c2f65a'); // DarkOliveGreen  || GreenYellow     ||Redder
        $colorCombos[] = array('245e21', 'f6f5ed'); // ForestGreen     || WhiteSmoke
        /* These two are very similar */
//        $colorCombos[] = array('5cd417', '70149f'); // LimeGreen       || DarkMagenta
//        $colorCombos[] = array('2efa44', '97279a'); // LimeGreen       || DarkOrchid
        
        //Blue:
        $colorCombos[] = array('3700e9', '8fe474'); // Blue            || LightGreen
        $colorCombos[] = array('2303c8', 'a4ad58'); // MediumBlue      || DarkKhaki
        $colorCombos[] = array('0e10ca', 'd3a21f'); // MediumBlue      || Peru
        $colorCombos[] = array('132052', '939689'); // MidnightBlue    || DarkSeaGreen
        $colorCombos[] = array('082f4f', 'ec4ff8'); // MidnightBlue    || Orchid
        $colorCombos[] = array('0f0f3d', 'c8a12a'); // MidnightBlue    || Peru
        
        //Purple:
        $colorCombos[] = array('2733af', '65fdf8'); // DarkSlateBlue   || Aquamarine
        $colorCombos[] = array('103c88', 'f3ed69'); // DarkSlateBlue   || Khaki
        $colorCombos[] = array('4c4da4', '8efb7f'); // DarkSlateBlue   || LightGreen
        $colorCombos[] = array('1d3987', 'd494f9'); // DarkSlateBlue   || Plum
        $colorCombos[] = array('590d60', 'd1ef85'); // Indigo          || Khaki
        $colorCombos[] = array('640c4d', '6dd8f8'); // Indigo          || LightSkyBlue
        $colorCombos[] = array('861d57', '9fc464'); // Purple          || DarkKhaki
        $colorCombos[] = array('8a0261', 'f5bdb8'); // Purple          || LightPink
        $colorCombos[] = array('751a5b', 'd5b67b'); // Purple          || Tan
        $colorCombos[] = array('7b016b', 'b8b432'); // Purple          || YellowGreen
        
        //Gray:
        $colorCombos[] = array('39062a', 'a36fce'); // Black           || MediumPurple
        $colorCombos[] = array('0c402e', 'fcc373'); // DarkSlateGray   || LightSalmon
        $colorCombos[] = array('2a4735', 'b7a3dc'); // DarkSlateGray   || LightSteelBlue
        $colorCombos[] = array('1b5535', 'bcfd86'); // DarkSlateGray   || PaleGreen
        $colorCombos[] = array('453513', 'bd91de'); // DarkSlateGray   || Plum
        $colorCombos[] = array('00505c', 'ecaf5f'); // DarkSlateGray   || SandyBrown
        $colorCombos[] = array('3f2e22', 'd8c8b0'); // DarkSlateGray   || Silver
        $colorCombos[] = array('0c5f3b', 'b5dda6'); // DarkSlateGray   || Silver
        $colorCombos[] = array('025f53', 'd4cc82'); // DarkSlateGray   || Tan
        $colorCombos[] = array('144b5c', 'ecbc7b'); // DarkSlateGray   || Tan
        $colorCombos[] = array('294216', '95bd1e'); // DarkSlateGray   || YellowGreen
        $colorCombos[] = array('67527a', '8be3c3'); // DimGray         || Aquamarine
        $colorCombos[] = array('634f56', 'f9c56c'); // DimGray         || SandyBrown
        $colorCombos[] = array('85345f', '97d912'); // DimGray         || YellowGreen
        
        
        // not yet wcag evaluated
        $colorCombos[] = array('0c3113', 'f8a0c9');
        
        
        
        $mod = count($colorCombos); //eventually will be 128 but counting is good
        $n = $input % $mod;
        
        // backgrounc color
        $backgroundHex = $colorCombos[$n][0];
        $this->background[0] = hexdec(substr($backgroundHex, 0, 2));
        $this->background[1] = hexdec(substr($backgroundHex, 2, 2));
        $this->background[2] = hexdec(substr($backgroundHex, 4, 2));
        // pictograph color
        $foregroundHex = $colorCombos[$n][1];
        $this->foreground[0] = hexdec(substr($foregroundHex, 0, 2));
        $this->foreground[1] = hexdec(substr($foregroundHex, 2, 2));
        $this->foreground[2] = hexdec(substr($foregroundHex, 4, 2));
    }//end selectColorCombos()

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
        if ($this->devel) {
            // Background Red Index
            $oneLight = false;
            if ($this->parameters[0] > 115) {
                $oneLight = true;
            }
            $this->background[0] = $this->parameters[0];
            // Background Green Index
            if ($this->parameters[1] <= 115) {
                $this->background[1] = $this->parameters[1];
            } else {
                if ($oneLight) {
                    $value = $this->parameters[1];
                    while ($value > 110) {
                        $value = $value - 27;
                    }
                    $this->background[1] = $value;
                } else {
                    $oneLight = true;
                    $this->background[1] = $this->parameters[1];
                }
            }
            // Background Blue Index
            if ($this->parameters[2] <= 115) {
                $this->background[2] = $this->parameters[2];
            } else {
                if ($oneLight) {
                    $value = $this->parameters[2];
                    while ($value > 110) {
                        $value = $value - 23;
                    }
                    $this->background[2] = $value;
                } else {
                    $this->background[2] = $this->parameters[2];
                }
            }
            // Foreground Blue Index
            $oneDark = false;
            if ($this->parameters[3] < 140) {
                $oneDark = true;
            }
            $this->foreground[2] = $this->parameters[3];
            // Foreground Green Index
            if ($this->parameters[4] >= 140) {
                $this->foreground[1] = $this->parameters[4];
            } else {
                if ($oneDark) {
                    $value = $this->parameters[4];
                    while ($value < 140) {
                        $value = $value + 37;
                    }
                    $this->foreground[1] = $value;
                } else {
                    $oneDark = true;
                    $this->foreground[1] = $this->parameters[4];
                }
            }
            // Foreground Red Index
            if ($this->parameters[5] >= 140) {
                $this->foreground[0] = $this->parameters[5];
            } else {
                if ($oneDark) {
                    $value = $this->parameters[5];
                    while ($value < 140) {
                        $value = $value + 13;
                    }
                    $this->foreground[0] = $value;
                } else {
                    $this->foreground[0] = $this->parameters[5];
                }
            }
        } else {
            // This is production color scheme picker - from WCAG AA/AAA approved combinations
            //  that look decent to me
            $sum = 17; //because I like 17
            for ($i=0; $i<16; $i++) {
                $sum = $sum + $this->parameters[$i];
            }
            $n = $sum % 256;
            $this->selectColorCombos($n);
        }
    }//end hashToParameters()

    /**
     * Generates an SVG RGB string.
     *
     * @param int $r The red component.
     * @param int $g The green component.
     * @param int $b The blue component.
     *
     * @return string The SVG compliant RGB string.
     */
    protected function setRgbString($r, $g, $b): string
    {
        $string = 'rgb(' . $r . ',' . $g . ',' . $b . ')';
        return $string;
    }//end setRgbString()

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
        $backgroundRgb = $this->setRgbString($this->background[0], $this->background[1], $this->background[2]);
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $pathString = 'M0,0 l800,0 l0,800 l-800,0 l0,-800z';
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
    }//end drawCanvas()

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
        $foregroundRgb = $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]);
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $x);
        $circle->setAttribute('cy', (string) $y);
        $circle->setAttribute('r', '75');
        $circle->setAttribute('stroke', 'none');
        $circle->setAttribute('fill', $foregroundRgb);
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
        $foregroundRgb = $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]);
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
        $path->setAttribute('stroke', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
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
        $foregroundRgb = $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]);
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
        $path->setAttribute('stroke', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $startX = $x + ($xWidth / 2);
        $pathString = 'M' . $startX . ',' . $startY . ' ';
        $pathString = $pathString . 'l-' . $xWidth . ',' . ($yHeight / 2) . ' ';
        $pathString = $pathString . 'l' . $xWidth . ',' . ($yHeight / 2);
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('fill', 'none');
        $path->setAttribute('stroke-width', '11');
        $path->setAttribute('stroke', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
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
        $backgroundRgb = $this->setRgbString($this->background[0], $this->background[1], $this->background[2]);
        $foregroundRgb = $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]);
        //Draw the light
        $pathString = 'M' . $x . ',' . ($y - 74) . ' ';
        $pathString .= 'a37,37 0 0 1 0,74 a37,37 0 0 0 0,74 a74,74 0 0 1 0,-148z';
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        //draw the outer circle
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $x);
        $circle->setAttribute('cy', (string) $y);
        $circle->setAttribute('r', '75');
        $circle->setAttribute('stroke-width', '3');
        $circle->setAttribute('stroke', $foregroundRgb);
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
        $circle->setAttribute('fill', $backgroundRgb);
        $this->svg->appendChild($circle);
        
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', (string) $x);
        $circle->setAttribute('cy', (string) $yLower);
        $circle->setAttribute('r', '8');
        $circle->setAttribute('stroke', 'none');
        $circle->setAttribute('fill', $foregroundRgb);
        $this->svg->appendChild($circle);
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
        $backgroundRgb = $this->setRgbString($this->background[0], $this->background[1], $this->background[2]);
        $foregroundRgb = $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]);
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
        $pathString .= 'q-11.25,9.75 -21,15.75z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
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
        $pathString .= 'q16.875,-18 21.375,-19.5z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $startX = $x +1;
        $startY = $y +66;
        
        $pathString = 'M' . $startX. ',' . $startY . ' ';
        $pathString .= 'q-21.75,-15 -33.75,-30 ';
        $pathString .= 'q-6.75,-7.5 -8.25,-18 ';
        $pathString .= 'a-15,-14.25 0 0 1 11.25,-14.25 ';
        $pathString .= 'q12,-1.5 17.25,3.75 ';
        $pathString .= 'q5.25,5.25 5.25,8.25 ';
        $pathString .= 'q-0.75,5.25 -7.5,6 ';
        $pathString .= 'q-3,0.75 -5.25,-0.75 ';
        $pathString .= 'q-1.5,-2.25 -2.25,-6 ';
        $pathString .= 'a-4.5,-4.5 0 0 0 -7.875,1.5 ';
        $pathString .= 'a-7.5,-7.5 0 0 0 1.125,8.25 ';
        $pathString .= 'q2.25,3.75 8.25,6 ';
        $pathString .= 'a-14.25,-14.25 0 0 0 8.25,-1.5 ';
        $pathString .= 'q7.5,-3 12,-7.5 ';
        $pathString .= 'a-18.75,-18.75 0 0 0 16.5,9.75 ';
        $pathString .= 'q6,0.375 14.25,-8.625 ';
        $pathString .= 'q2.25,-3.75 2.25,-6.75 ';
        $pathString .= 'a-3,-3 0 0 0 -3.75,-2.25 ';
        $pathString .= 'q-3,0 -4.875,2.625 ';
        $pathString .= 'a-7.5,-9 0 0 1 -8.25,6 ';
        $pathString .= 'a-9,-9.75 0 0 1 -8.25,-7.5 ';
        $pathString .= 'q0,-10.5 15,-10.5 ';
        $pathString .= 'a-15.75,-15 0 0 1 16.5,12 ';
        $pathString .= 'q0.375,3.375 -1.5,7.5 ';
        $pathString .= 'q-4.5,11.25 -15,22.5 ';
        $pathString .= 'q-16.875,18 -21.375,19.5z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $startX = $x -7;
        $startY = $y -0;
        
        $pathString = 'M' . $startX. ',' . $startY . ' ';
        
        $pathString .= 'a21,21 0 0 0 6.5,-6 ';
        $pathString .= 'a18,18 0 0 0 8,7.5 ';
        $pathString .= 'a21,21 0 0 0 -6.5,6 ';
        $pathString .= 'a18,18 0 0 0 -8,-7.5z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
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
        $foregroundRgb = $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]);
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
      
        $pathString = 'M' . $Ax . ',' . $Ay . ' ';
        $pathString = $pathString . 'l' . ($Bx - $Ax) . ',' . ($By - $Ay) . ' ';
        $pathString = $pathString . 'l' . ($Cx - $Bx) . ',' . ($Cy - $By) . ' ';
        $pathString = $pathString . 'l' . ($Dx - $Cx) . ',' . ($Dy - $Cy) . ' ';
        $pathString = $pathString . 'l' . ($Ex - $Dx) . ',' . ($Ey - $Dy) . ' ';
        $pathString = $pathString . 'l' . ($Fx - $Ex) . ',' . ($Fy - $Ey) . ' ';
        $pathString = $pathString . 'l' . ($Gx - $Fx) . ',' . ($Gy - $Fy) . ' ';
        $pathString = $pathString . 'l' . ($Ax - $Gx) . ',' . ($Ay - $Gy) . 'z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('fill', 'none');
        $path->setAttribute('stroke', $foregroundRgb);
        $path->setAttribute('stroke-width', '5');
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
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
        $backgroundRgb = $this->setRgbString($this->background[0], $this->background[1], $this->background[2]);
        $foregroundRgb = $this->setRgbString($this->foreground[0], $this->foreground[1], $this->foreground[2]);
        
        $pathString = 'M' . $x . ',' . ($y + 75) . ' ';
        $pathString .= 'a24.5,47 0 0 0 -44.5,13.5 ';
        $pathString .= 'a20,27.5 0 0 1 9.5,-47.75 ';
        $pathString .= 'a87.5,75 0 0 1 0,-73 ';
        $pathString .= 'a10,10 0 0 0 -3,-4 ';
        $pathString .= 'a10,10 0 0 0 -6.5,-0.5 ';
        $pathString .= 'a40,40 0 0 0 -26.5,30 ';
        $pathString .= 'a6,40 0 0 1 -4,9.25 ';
        $pathString .= 'a10,15 0 0 1 -9,-7.5 ';
        $pathString .= 'a50,50 0 0 1 15,-40.5 ';
        $pathString .= 'a35,40 0 0 1 31.5,-3.5 ';
        $pathString .= 'l 10,4 ';
        $pathString .= 'a15,20 0 0 0 10.5,-0.5 ';
        $pathString .= 'a27.5,20 0 0 1 10,-2.75 ';
        $pathString .= 'c1,-8 -7,-15 -7,-25 ';
        $pathString .= 'a14,15 0 0 1 28,0 ';
        $pathString .= 'c0,10 -8,17 -7,25 ';
        $pathString .= 'a27.5,20 0 0 1 10,2.75 ';
        $pathString .= 'a15,20 0 0 0 10.5,0.5 ';
        $pathString .= 'l 10,-4 ';
        $pathString .= 'a35,40 0 0 1 32,3.5 ';
        $pathString .= 'a50,50 0 0 1 16,40.5 ';
        $pathString .= 'a10,15 0 0 1 -9.5,8 ';
        $pathString .= 'a6,40 0 0 1 -4,-9.25 ';
        $pathString .= 'a40,40 0 0 0 -26.5,-30.5 ';
        $pathString .= 'a10,10 0 0 0 -7,0.5 ';
        $pathString .= 'a10,10 0 0 0 -3.5,4 ';
        $pathString .= 'a87.5,75 0 0 1 0,73 ';
        $pathString .= 'a20,27.5 0 0 1 9.5,47.75 ';
        $pathString .= 'a24.5,47 0 0 0 -44.5,-13.5z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . ($x - 25) . ',' . ($y + 51.5) . ' ';
        $pathString .= 'a30,20 0 0 0 50,0z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $foregroundRgb);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . ($x - 2.75) . ',' . ($y - 33.75) . ' ';
        $pathString .= 'l 0,78 ';
        $pathString .= 'c0,6.5 -7.5,5 -11,3 ';
        $pathString .= 'a37.5,45 0 0 1 0,-84 ';
        $pathString .= 'c3.5,-2 11,-3.5 11,3z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $path->setAttribute('fill-opacity', '0.7');
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
        
        $pathString = 'M' . ($x + 2.75) . ',' . ($y - 33.75) . ' ';
        $pathString .= 'l 0,78 ';
        $pathString .= 'c0,6.5 7.5,5 11,3 ';
        $pathString .= 'a37.5,45 0 0 0 0,-84 ';
        $pathString .= 'c-3.5,-2 -11,-3.5 -11,3z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $backgroundRgb);
        $path->setAttribute('fill-opacity', '0.7');
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
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
     * The Constructor. Creates the SVG that is to be served.
     *
     * @param string $hash  Intended to be a hex representation of a 128-bit hash but any string will do.
     * @param bool   $devel For testing purposes.
     */
    public function __construct($hash, bool $devel = false)
    {
        if ($devel) {
            $this->devel = true;
        }
        $this->dom = new \DOMDocument("1.0", "UTF-8");
        //$this->dom->formatOutput = true;
        // @codingStandardsIgnoreLine
        $docstring = '<?xml version="1.0"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="800" height="800" viewBox="0 0 800 800"/>';
        $this->dom->loadXML($docstring);
        $this->svg = $this->dom->getElementsByTagName('svg')->item(0);
        
        $this->hashToParameters($hash);
        $this->drawCanvas();
        
        // The sixteen squares
        for ($i=0; $i<4; $i++) {
            for ($j=0; $j<4; $j++) {
                $byteN = (4 * $j) + $i;
                $byte = $this->parameters[$byteN];
                $x = (200 * $i) + 100;
                $y = (200 * $j) + 100;
                $mod = $byte % 9;
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
                    default:
                        $this->simpleCircle($x, $y);
                        //$this->simpleSun($x, $y);
                        break;
                }
            }
        }
        $this->addFrame();
        $gendate = 'SVG Generated on ' . date('r');
        $comment = $this->dom->createComment($gendate);
        // make psalm happy
        if (! is_null($this->svg)) {
            $this->svg->appendChild($comment);
        }
    }//end __construct()
}//end class

// uncomment this block for testing file as standalone program

$foo = random_bytes(32);
$foo = base64_encode($foo);
//$bar = new PictoGlyph($foo, true);
$bar = new PictoGlyph($foo);
$bar->sendContent();
exit;


?>