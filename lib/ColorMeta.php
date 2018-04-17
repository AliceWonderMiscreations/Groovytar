<?php
declare(strict_types=1);

/**
 * This class is just being written to help me with color selection and testing.
 * It will not be called by any of the identicon classes.
 *
 * @package AWonderPHP/Groovytar
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/Groovytar
 */

namespace AWonderPHP\Groovytar;

/**
 * Figure out stuff about a color from its hex code.
 */
class ColorMeta
{
    /**
     * Named Reference Colors.
     *
     * @var array
     */
    protected $referenceColors;
  
    /**
     * Calculate the luminance of a color.
     *
     * @param string hex The hex color code.
     *
     * @return float The greyscale luminance.
     */
    protected function greyscaleLuminance($hex)
    {
        $red = hexdec(substr($hex, 0, 2);
        $green = hexdec(substr($hex, 2, 2);
        $blue = hexdec(substr($hex, 4, 2);
        $redRatio = bcdiv(255, $red, 4);
        $greenRatio = bcdiv(255, $green, 4);
        $blueRatio = bcdiv(255, $blue, 4);
        
        $redComponent = bcmul(0.299, $redRatio, 4);
        $greenComponent = bcmul(0.587, $greenRatio, 4);
        $blueComponent = bcmul(0.114, $blueComponent, 4);
        
        $luminance = bcadd($redComponent, $greenComponent, 4);
        $luminance = bcadd($luminance, $blueComponent, 4);
        
        return $luminance;
    }
    
    /**
     * Created a stdClass object with properties related to the color.
     *
     * @param string      $majorCat  The major color group it belongs with.
     * @param string      $hex       The hex color code.
     * @param string|null $colorName The official color name.
     *
     */
    protected function createColorObject($majorCat, $hex, $colorName = null)
    {
        $obj = new \stdClass();
        $obj->majorCat = $majorCat;
        if(! is_null($colorName) {
            $obj->colorName = $colorName;
        }
        $obj->hex = strtolower($hex);
        $onj->luminance = $this->greyscaleLuminance($hex);
        return $obj;
    }
    
    // only should be used when colors already determined to have
    // similar luminense
    protected function compareTwoColors($one, $two)
    {
        $lumediff = 0;
        if($one->luminance > $two->luminance) {
            $lumediff = bcsub($one->luminance, $two->luminance, 2);
        } else {
            $lumediff = bcsub($two->luminance, $one->luminance, 2);
        }
        $ldiff = intval(bcmul(100, $lumediff, 0));
        if($ldiff > 10) {
            // too different to compare this way
            return false;
        }
      
        $cone = array();
        $ctwo = array();
        $cone['red'] = hexdec(substr($one->hex, 0, 2);
        $cone['green'] = hexdec(substr($one->hex, 2, 2);
        $cone['blue'] = hexdec(substr($one->hex, 4, 2);
        $ctwo['red'] = hexdec(substr($two->hex, 0, 2);
        $ctwo['green'] = hexdec(substr($two->hex, 2, 2);
        $ctwo['blue'] = hexdec(substr($two->hex, 4, 2);
        $diff = 0;
        $a = $cone['red'] - $ctwo['red'];
        $diff = $diff + abs($a);
        $a = $cone['green'] - $ctwo['green'];
        $diff = $diff + abs($a);
        $a = $cone['blue'] - $ctwo['blue'];
        $diff = $diff + abs($a);
        return $diff;
    }
    
    // X11 color names - W3C takes precedence where clash
    protected function generateReferenceColors()
    {
        //Pink Colors
        $majorCat = 'Pink Colors';
        $colors[] = $this->createColorObject($majorCat, 'ffc0cb', 'Pink');
        $colors[] = $this->createColorObject($majorCat, 'ffb6c1', 'LightPink');
        $colors[] = $this->createColorObject($majorCat, 'ff69b4', 'HotPink');
        $colors[] = $this->createColorObject($majorCat, 'ff1493', 'DeepPink');
        $colors[] = $this->createColorObject($majorCat, 'db7093', 'PaleVioletRed');
        $colors[] = $this->createColorObject($majorCat, 'c71585', 'MediumVioletRed');
        //Red Colors
        $majorCat = 'Red Colors';
        $colors[] = $this->createColorObject($majorCat, 'ffa07a', 'LightSalmon');
        $colors[] = $this->createColorObject($majorCat, 'fa8072', 'Salmon');
        $colors[] = $this->createColorObject($majorCat, 'e9967a', 'DarkSalmon');
        $colors[] = $this->createColorObject($majorCat, 'f08080', 'LightCoral');
        $colors[] = $this->createColorObject($majorCat, 'cd5c5c', 'IndianRed');
        $colors[] = $this->createColorObject($majorCat, 'dc143c', 'Crimson');
        $colors[] = $this->createColorObject($majorCat, 'b22222', 'FireBrick');
        $colors[] = $this->createColorObject($majorCat, '8b0000', 'DarkRed');
        $colors[] = $this->createColorObject($majorCat, 'ff0000', 'Red');
        //Orange Colors
        $majorCat = 'Orange Colors';
        $colors[] = $this->createColorObject($majorCat, 'ff4500', 'OrangeRed');
        $colors[] = $this->createColorObject($majorCat, 'ff6348', 'Tomato');
        $colors[] = $this->createColorObject($majorCat, 'ff7f50', 'Coral');
        $colors[] = $this->createColorObject($majorCat, 'ff8c00', 'DarkOrange');
        $colors[] = $this->createColorObject($majorCat, 'ffa500', 'Orange');
        //Yellow Colors
        $majorCat = 'Yellow Colors';
        $colors[] = $this->createColorObject($majorCat, 'ffff00', 'Yellow');
        $colors[] = $this->createColorObject($majorCat, 'ffffe0', 'LightYellow');
        $colors[] = $this->createColorObject($majorCat, 'fffacd', 'LemonChiffon');
        $colors[] = $this->createColorObject($majorCat, 'fafad2', 'LightGoldenrodYellow');
        $colors[] = $this->createColorObject($majorCat, 'ffefd5', 'PapayWhip');
        $colors[] = $this->createColorObject($majorCat, 'ffe4b5', 'Moccasin');
        $colors[] = $this->createColorObject($majorCat, 'ffdab9', 'PeachPuff');
        $colors[] = $this->createColorObject($majorCat, 'eee8aa', 'PaleGoldenrod');
        $colors[] = $this->createColorObject($majorCat, 'f0e68c', 'Khaki');
        $colors[] = $this->createColorObject($majorCat, 'bdb76b', 'DarkKhaki');
        $colors[] = $this->createColorObject($majorCat, 'ffd700', 'Gold');
        //Brown Colors
        $majorCat = 'Brown Colors';
        $colors[] = $this->createColorObject($majorCat, 'fff8dc', 'Cornsilk');
        $colors[] = $this->createColorObject($majorCat, 'ffebcd', 'BlanchedAlmond');
        $colors[] = $this->createColorObject($majorCat, 'ffe4c4', 'Bisque');
        $colors[] = $this->createColorObject($majorCat, 'ffdead', 'NavajoWhite');
        $colors[] = $this->createColorObject($majorCat, 'f5deb3', 'Wheat');
        $colors[] = $this->createColorObject($majorCat, 'deb887', 'Tan');
        $colors[] = $this->createColorObject($majorCat, 'bc8f8f', 'RosyBrown');
        $colors[] = $this->createColorObject($majorCat, 'f4a460', 'SandyBrown');
        $colors[] = $this->createColorObject($majorCat, 'b8860b', 'DarkGoldenrod');
        $colors[] = $this->createColorObject($majorCat, 'cd853f', 'Peru');
        $colors[] = $this->createColorObject($majorCat, 'd2691e', 'Chocolate');
        $colors[] = $this->createColorObject($majorCat, '8b4513', 'SaddleBrown');
        $colors[] = $this->createColorObject($majorCat, 'a0522d', 'Sienna');
        $colors[] = $this->createColorObject($majorCat, 'a52a2a', 'Brown');
        $colors[] = $this->createColorObject($majorCat, '800000', 'Maroon');
        //Green Colors
        $majorCat = 'Green Colors';
        $colors[] = $this->createColorObject($majorCat, '556b2f', 'DarkOliveGreen');
        $colors[] = $this->createColorObject($majorCat, '808000', 'Olive');
        $colors[] = $this->createColorObject($majorCat, '6b8e23', 'OliveDrab');
        $colors[] = $this->createColorObject($majorCat, '9acd32', 'YellowGreen');
        $colors[] = $this->createColorObject($majorCat, '32cd32', 'LimeGreen');
        $colors[] = $this->createColorObject($majorCat, '00ff00', 'Lime');
        $colors[] = $this->createColorObject($majorCat, '7cfc00', 'LawnGreen');
        $colors[] = $this->createColorObject($majorCat, '7fff00', 'Chartreuse');
        $colors[] = $this->createColorObject($majorCat, 'adff2f', 'GreenYellow');
        $colors[] = $this->createColorObject($majorCat, '00ff7f', 'SpringGreen');
        $colors[] = $this->createColorObject($majorCat, '00fa9a', 'MediumSpringGreen');
        $colors[] = $this->createColorObject($majorCat, '90ee90', 'LightGreen');
        $colors[] = $this->createColorObject($majorCat, '98fb98', 'PaleGreen');
        $colors[] = $this->createColorObject($majorCat, '8fbc8f', 'DarkSeaGreen');
        $colors[] = $this->createColorObject($majorCat, '66cdaa', 'MediumAquamarine');
        $colors[] = $this->createColorObject($majorCat, '3cb371', 'MediumSeaGreen');
        $colors[] = $this->createColorObject($majorCat, '2e8b57', 'SeaGreen');
        $colors[] = $this->createColorObject($majorCat, '228b22', 'ForestGreen');
        $colors[] = $this->createColorObject($majorCat, '008000', 'Green');
        $colors[] = $this->createColorObject($majorCat, '006400', 'Dark Green');
        //Cyan Colors
        $majorCat = 'Cyan Colors';
        $colors[] = $this->createColorObject($majorCat, '00ffff', 'Aqua');
        $colors[] = $this->createColorObject($majorCat, 'e0ffff', 'LightCyan');
        $colors[] = $this->createColorObject($majorCat, 'afeeee', 'PaleTurquoise');
        $colors[] = $this->createColorObject($majorCat, '7fffd4', 'Aquamarine');
        $colors[] = $this->createColorObject($majorCat, '40e0d0', 'Turquoise');
        $colors[] = $this->createColorObject($majorCat, '48d1cc', 'MediumTurquoise');
        $colors[] = $this->createColorObject($majorCat, '00ced1', 'DarkTurquoise');
        $colors[] = $this->createColorObject($majorCat, '20b2aa', 'LightSeaGreen');
        $colors[] = $this->createColorObject($majorCat, '5f9ea0', 'CadetBlue');
        $colors[] = $this->createColorObject($majorCat, '008b8b', 'DarkCyan');
        $colors[] = $this->createColorObject($majorCat, '008080', 'Teal');
        //Blue Colors
        $majorCat = 'Blue Colors';
        $colors[] = $this->createColorObject($majorCat, 'b0c4de', 'LightSteelBlue');
        $colors[] = $this->createColorObject($majorCat, 'b0e0e6', 'PowderBlue');
        $colors[] = $this->createColorObject($majorCat, 'add8e6', 'LightBlue');
        $colors[] = $this->createColorObject($majorCat, '87ceeb', 'SkyBlue');
        $colors[] = $this->createColorObject($majorCat, '87cefa', 'LightSkyBlue');
        $colors[] = $this->createColorObject($majorCat, '00bfff', 'DeepSkyBlue');
        $colors[] = $this->createColorObject($majorCat, '1e90ff', 'DodgerBlue');
        $colors[] = $this->createColorObject($majorCat, '6495ed', 'CornflowerBlue');
        $colors[] = $this->createColorObject($majorCat, '4682b4', 'Steelblue');
        $colors[] = $this->createColorObject($majorCat, '4169e1', 'RoyalBlue');
        $colors[] = $this->createColorObject($majorCat, '0000ff', 'Blue');
        $colors[] = $this->createColorObject($majorCat, '0000cd', 'MediumBlue');
        $colors[] = $this->createColorObject($majorCat, '00008b', 'DarkBlue');
        $colors[] = $this->createColorObject($majorCat, '000080', 'Navy');
        $colors[] = $this->createColorObject($majorCat, '191970', 'MidnightBlue');
        //Purple, Violet, Magenta colors
        $majorCat = 'Purple Colors';
        $colors[] = $this->createColorObject($majorCat, 'e6e6fa', 'Lavender');
        $colors[] = $this->createColorObject($majorCat, 'd8bfd8', 'Thistle');
        $colors[] = $this->createColorObject($majorCat, 'dda0dd', 'Plum');
        $colors[] = $this->createColorObject($majorCat, 'ee82ee', 'Violet');
        $colors[] = $this->createColorObject($majorCat, 'da70d6', 'Orchid');
        $colors[] = $this->createColorObject($majorCat, 'ff00ff', 'Fuchsia');
        $colors[] = $this->createColorObject($majorCat, 'ba55d3', 'MediumOrchid');
        $colors[] = $this->createColorObject($majorCat, '9370db', 'MediumPurple');
        $colors[] = $this->createColorObject($majorCat, '8a2be2', 'BlueViolet');
        $colors[] = $this->createColorObject($majorCat, '9400d3', 'DarkViolet');
        $colors[] = $this->createColorObject($majorCat, '9932cc', 'DarkOrchid');
        $colors[] = $this->createColorObject($majorCat, '8b008b', 'DarkMagenta');
        $colors[] = $this->createColorObject($majorCat, '800080', 'Purple');
        $colors[] = $this->createColorObject($majorCat, '4b0082', 'Indigo');
        $colors[] = $this->createColorObject($majorCat, '483d8b', 'DarkSlateBlue');
        $colors[] = $this->createColorObject($majorCat, '6a5acd', 'SlateBlue');
        $colors[] = $this->createColorObject($majorCat, '7b68ee', 'MediumSlateBlue');
        //White Colors
        $majorCat = 'White Colors';
        $colors[] = $this->createColorObject($majorCat, 'ffffff', 'White');
        $colors[] = $this->createColorObject($majorCat, 'fffafa', 'Snow');
        $colors[] = $this->createColorObject($majorCat, 'f0fff0', 'Honeydew');
        $colors[] = $this->createColorObject($majorCat, 'f5fffa', 'MintCream');
        $colors[] = $this->createColorObject($majorCat, 'f0ffff', 'Azure');
        $colors[] = $this->createColorObject($majorCat, 'f0f8ff', 'AliceBlue');
        $colors[] = $this->createColorObject($majorCat, 'f8f8ff', 'GhostWhite');
        $colors[] = $this->createColorObject($majorCat, 'f5f5f5', 'WhiteSmoke');
        $colors[] = $this->createColorObject($majorCat, 'fff5ee', 'Seashell');
        $colors[] = $this->createColorObject($majorCat, 'f5f5dc', 'Beige');
        $colors[] = $this->createColorObject($majorCat, 'fdf5e6', 'OldLace');
        $colors[] = $this->createColorObject($majorCat, 'fffaf0', 'FloralWhite');
        $colors[] = $this->createColorObject($majorCat, 'fffff0', 'Ivory');
        $colors[] = $this->createColorObject($majorCat, 'faebd7', 'AntiqueWhite');
        $colors[] = $this->createColorObject($majorCat, 'faf0e6', 'Linen');
        $colors[] = $this->createColorObject($majorCat, 'fff0f5', 'LavenderBlush');
        $colors[] = $this->createColorObject($majorCat, 'ffe4e1', 'MistyRose');
        //Gray and Black Colors
        $majorCat = 'Gray and Black Colors';
        $colors[] = $this->createColorObject($majorCat, 'dcdcdc', 'Gainsboro');
        $colors[] = $this->createColorObject($majorCat, 'd3d3d3', 'LightGray');
        $colors[] = $this->createColorObject($majorCat, 'c0c0c0', 'Silver');
        $colors[] = $this->createColorObject($majorCat, 'a9a9a9', 'DarkGray');
        $colors[] = $this->createColorObject($majorCat, '808080', 'Gray');
        $colors[] = $this->createColorObject($majorCat, '696969', 'DimGray');
        $colors[] = $this->createColorObject($majorCat, '778899', 'LightSlateGray');
        $colors[] = $this->createColorObject($majorCat, '708090', 'SlateGray');
        $colors[] = $this->createColorObject($majorCat, '2f4f4f', 'DarkSlateGray');
        $colors[] = $this->createColorObject($majorCat, '000000', 'Black');
        
        $this->referenceColors = $colors;
        
        
    }
}


























?>