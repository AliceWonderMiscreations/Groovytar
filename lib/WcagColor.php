<?php
declare(strict_types = 1);

/**
 * Allows selection of background/foreground color combinations from a predefined list of
 * sets. The predefined colors in the set are chosen by me (Alice Wonder) to be aesthetically
 * pleasing (subjective) and meet the WCAG 2.0 AAA standard for large text (contrast ratio
 * of 4.5:1 for large text) which is measurable, not subjective.
 *
 * Still in development.
 *
 * The goal is to have 128 different combinations without *too many* being too visually
 * similar. That goal has not yet been met.
 *
 * @package AWonderPHP/Groovytar
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/Groovytar
 */

namespace AWonderPHP\Groovytar;

/**
 * Class of static functions for color combo selection.
 */
class WcagColor
{
    /**
     * Predefined list of 128 color combinations. Not yet stable, nor does it yet have 128
     * different color combinations. That is the plan however.
     *
     * @return array An array of all the available color combinations.
     */
    public static function definedColorCombinations(): array
    {
        $colorCombos = array();
        // This sorting may need improvement
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
        
        return $colorCombos;
    }//end definedColorCombinations()

    /**
     * Selects a color combination based upon the integer it is fed. The integer should
     * be derived from the hash a Groovytar identicon class is fed in a predictable way
     * so that (within the context of the identicon class) the same hash always results
     * in the same number and thus same color combination.
     *
     * @param int $integer The integer to use. Should be a parameter with equal distribution
     *                     of likelihood between 0 and N where N is one less than an integer
     *                     multiple of 128.
     *
     * @return array An array containing the randomly selected background and foreground colors.
     */
    public static function selectColorCombo(int $integer): array
    {
        $combos = self::definedColorCombinations();
        $mod = count($combos); //eventually will be 128 but counting is good
        $n = $integer % $mod;
        $backgroundHex = $combos[$n][0];
        $foregroundHex = $combos[$n][1];
        
        $background = array();
        $background[] = hexdec(substr($backgroundHex, 0, 2));
        $background[] = hexdec(substr($backgroundHex, 2, 2));
        $background[] = hexdec(substr($backgroundHex, 4, 2));
                
        $foreground = array();
        $foreground[] = hexdec(substr($foregroundHex, 0, 2));
        $foreground[] = hexdec(substr($foregroundHex, 2, 2));
        $foreground[] = hexdec(substr($foregroundHex, 4, 2));
        
        $return = array();
        $return['background'] = $background;
        $return['foreground'] = $foreground;
        return $return;
    }//end selectColorCombo()

    /**
     * Selects a color combination randomly. Groovytar identicon classes should not
     * call this function, but should select a combo based upon an integer derived
     * from the hash rather than randomly.
     *
     * @return array An array containing the randomly selected background and foreground colors.
     */
    public static function randomColorCombo(): array
    {
        $combos = self::definedColorCombinations();
        $max = ((4 * count($combos)) - 1);
        $rnum = intval(random_int(0, $max), 10);
        $return = self::selectColorCombo($rnum);
        return $return;
    }//end randomColorCombo()
}//end class

?>