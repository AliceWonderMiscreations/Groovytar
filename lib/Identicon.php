<?php
declare(strict_types = 1);

/**
 * Abstract class that can be extended when writing an identicon class.
 *
 * @package AWonderPHP/Groovytar
 * @author  Alice Wonder <paypal@domblogger.net>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/AliceWonderMiscreations/Groovytar
 */

namespace AWonderPHP\Groovytar;

/**
 * An abstract class for identicons classes
 */

abstract class Identicon
{
    /**
     * The parameters, an array of 16 sets of integers between 0 and 255 inclusive
     *
     * @var array
     */
    protected $parameters = array();

    /**
     * The dom object. Created by the constructor.
     *
     * @var \DOMDocument
     */
    protected $dom;

    /**
     * The root SVG node. Created by the constructor.
     *
     * @var \DOMNode
     */
    protected $svg;

    /**
     * Generate a set of parameters from a given hex hash or string. This should be called from
     * the constructor. It is recommended that you create your own version of this function in
     * your extended class so that when the same hash is used with different classes, the
     * generated parameters are different.
     *
     * At a minimum you should copy this function and change the string in the $changeMeWhenExtending
     * variable so that $hash128bit for an input $hash is unique to your class.
     *
     * @param string $hash    The hex hash to generate parameters from.
     * @param bool   $example When true, you may optionally generate the parameters in a less than
     *                        random fashion and/or ignore the $hash.
     *
     * @return void
     */
    protected function hashToParameters(string $hash, bool $example = false): void
    {
        if (! ctype_xdigit($hash)) {
            $hash = md5($hash);
        }
        if (strlen($hash) !== 32) {
            $hash = md5($hash);
        }
        $raw = hex2bin($hash);
        $changeMeWhenExtending = 'JFrFV0n4BHZAx1TMW1ZxZpNPzS230fQo7TwnoiFUxp7Sy2u7pbrZRtvIZDZd3C';
        
        $hash128bit = hash_hmac('tiger128,3', $raw, $changeMeWhenExtending, false);
        
        $hash128bit=hash('tiger128,3', $raw, false);
        $this->parameters = array();
        for ($i=0; $i<16; $i++) {
            $n = 2 * $i;
            $this->parameters[] = hexdec(substr($hash128bit, $n, 2));
        }
    }//end hashToParameters()

    /* Convenience protected methods likely to be beneficial to lots of classes */

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
     * Draws the background canvas path and adds it as a direct child of the root
     * svg node. As no opacity is set, it will cover everything before it completely
     * so if called it should be the first argument.
     *
     * @param int    $size  The size of the canvas (will be square).
     * @param string $color The color to use for North and East part of frame. String in
     *                      the format of rgb(r,g,b).
     *
     * @return void
     */
    protected function drawCanvas(int $size, string $color): void
    {
        $pathString = 'M0,0 l' . $size . ',0 l0,' . $size . 'l-' . $size . ',0 l0,-' . $size . 'z';
        
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $color);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
    }//end drawCanvas()

    /**
     * Generates the frame around the image and adds the frame (as two paths) as directed
     * child nodes of the root svg node. They are added with a hard-coded opacity of 0.6.
     *
     * @param int    $size     The size of the SVG canvas, which is assumed to be square.
     * @param string $colorOne The color to use for North and East part of frame. String in
     *                         the format of rgb(r,g,b).
     * @param string $colorTwo The color to use for South and West part of feame. String in
     *                         the format of rgb(r,g,b).
     *
     * @return void
     */
    protected function addFrame(int $size, string $colorOne, string $colorTwo): void
    {
        $frameWidth = intval(($size * 0.00875), 10);
        $innerSize = $size - (2 * $frameWidth);
        $pathString = 'M0,0 l' . $size . ',0 l0,' . $size . ' l-' . $frameWidth . ',-' . $frameWidth;
        $pathString = $pathString . ' l0,-' . $innerSize . 'l-' . $innerSize . ',0z';
        
        $path = $this->dom->createElement('path');
//        $pathString = 'M0,0 l800,0 l0,800 l-7,-7 l0,-786 l-786,0z';
        $path->setAttribute('d', $pathString);
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill-opacity', '0.6');
        $path->setAttribute('fill', $colorOne);
        $this->svg->appendChild($path);
        
        $pathString = 'M0,0 l0,' . $size . ' l' . $size . ',0 l-' . $frameWidth . ',-' . $frameWidth;
        $pathString = $pathString . ' l-' . $innerSize . ',0 l0,-' . $innerSize . 'z';
        
        $path = $this->dom->createElement('path');
//        $pathString = 'M0,0 l0,800 l800,0 l-7,-7 l-786,0 l0,-786z';
        $path->setAttribute('d', $pathString);
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill-opacity', '0.6');
        $path->setAttribute('fill', $colorTwo);
        $this->svg->appendChild($path);
    }//end addFrame()

    /**
     * Creates an SVG path node without fill and adds it as direct child of the root svg node.
     *
     * @param int|float $x     The x coordinate to start with.
     * @param int|float $y     The y coordinate to start with.
     * @param string    $spath The d parameter for the path.
     * @param string    $color The stroke color.
     * @param int|float $width The stroke width.
     *
     * @return void
     */
    protected function svgStrokePath($x, $y, $spath, $color, $width): void
    {
        $stringWidth = (string) $width;
        $pathString = 'M' . $x . ',' . $y . ' ' . $spath;
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', $color);
        $path->setAttribute('fill', 'none');
        $path->setAttribute('stroke-width', $stringWidth);
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
    }//end svgStrokePath()

    /**
     * Creates an SVG path node without stroke and adds it as direct child of the root svg node.
     *
     * @param int|float $x       The x coordinate to start with.
     * @param int|float $y       The y coordinate to start with.
     * @param string    $spath   The d parameter for the path.
     * @param string    $color   The color to fill with.
     * @param int|float $opacity The opacity for the the fill.
     *
     * @return void
     */
    protected function svgFillPath($x, $y, $spath, $color, $opacity = 1): void
    {
        $stringOpacity = (string) $opacity;
        $pathString = 'M' . $x . ',' . $y . ' ' . $spath;
        $path = $this->dom->createElement('path');
        $path->setAttribute('stroke', 'none');
        $path->setAttribute('fill', $color);
        if ($stringOpacity !== '1') {
            $path->setAttribute('fill-opacity', $stringOpacity);
        }
        $path->setAttribute('d', $pathString);
        $this->svg->appendChild($path);
    }//end svgFillPath()
    
    /**
     * Creates a filled SVG circle node without stroke and adds it as direct child of the root svg node.
     *
     * @param int|float $cx The center x coordinate.
     * @param int|float $cy The center y coordinate.
     * @param int|float $r The radius of the circle.
     * @param string    $color The color to fill with.
     * @param int|float $opacity The opacity for the the fill.
     *
     * @return void
     */
    protected function svgFilledCircle($cx, $cy, $r, $color, $opacity = 1): void
    {
        $stringCx = (string) $cx;
        $stringCy = (string) $cy;
        $stringRadius = (string) $r;
        $stringOpacity = (string) $opacity;
        
        $circle = $this->dom->createElement('circle');
        $circle->setAttribute('cx', $stringCx);
        $circle->setAttribute('cy', $stringCy);
        $circle->setAttribute('r', $stringRadius);
        $circle->setAttribute('stroke', 'none');
        $circle->setAttribute('fill', $color);
        if ($stringOpacity !== '1') {
            $circle->setAttribute('fill-opacity', $stringOpacity);
        }
        $this->svg->appendChild($circle);
    }//end svgFilledCircle()
     

    /**
     * Adds generation timestamp to the SVG file.
     *
     * @return void
     */
    protected function addGenerationDateComment(): void
    {
        $gendate = 'SVG Generated on ' . date('r');
        $comment = $this->dom->createComment($gendate);
        // make vimeo/psalm happy
        if (! is_null($this->svg)) {
            $this->svg->appendChild($comment);
        }
    }//end addGenerationDateComment()

    /* Public Methods */

    /**
     * Writes the SVG to the specified file.
     *
     * @param string $path The path to where the file is to be written.
     *
     * @return void
     */
    public function writeFile(string $path): void
    {
        $string = $this->dom->saveXML();
        // TODO error handle
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
        // the filesystem/server for expires etc.
        $expires = $tstamp + (90);
        header("HTTP/1.1 200 OK");
        header('Content-Type: image/svg+xml; charset=utf-8');
        header_remove('X-Powered-By');
        $content = $this->dom->saveXML();
        print($content);
    }//end sendContent()
}//end class

?>