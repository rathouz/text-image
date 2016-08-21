<?php

/**
 * This file is part of the Pehape libraries (http://pehape.cz)
 * Copyright (c) 2016 Tomas Rathouz <trathouz at gmail.com>
 */

namespace Pehape\TextImage\Utils;


/**
 * Color class represents the color.
 *
 * @author Tomas Rathouz <trathouz at gmail.com>
 */
class Color
{

    /** @var array */
    private static $stringColors = [
        'black' => '000000',
        'white' => 'FFFFFF',
        'grey' => 'CCCCCC',
        'red' => 'FF0000',
        'green' => '00FF00',
        'blue' => '0000FF',
    ];
    
    /** @var string */
    private static $fallbackColor = '000000';

    /** @var int */
    private static $minRGB = 0;

    /** @var int */
    private static $maxRGB = 255;

    /** @var string HEX format */
    private $color;


    /**
     * Constructor.
     * @param array|string $color
     */
    public function __construct($color)
    {
        $this->color = $this->parseColor($color);
    }


    /**
     * Parse color from source parameter.
     * @param array|string $color
     * @return string
     */
    private function parseColor($color)
    {
        if (self::isRGB($color) === TRUE) {
            return self::getHEXFromRGB($color[0], $color[1], $color[2]);
        } else if (self::isHEX($color) === TRUE) {
            return \strtoupper($color);
        } else if ($this->isString($color) === TRUE) {
            return self::$stringColors[$color];
        } else {
            return self::$fallbackColor;
        }

    }


    /**
     * Check if given color is in RGB mode.
     * @param mixed $color
     * @return bool
     */
    public static function isRGB($color)
    {
        if (\is_array($color) === TRUE && \count($color) === 3) {
            foreach ($color as $colorPart) {
                if (\is_int($colorPart) === FALSE) {
                    return FALSE;
                }

                if ($colorPart < self::$minRGB || $colorPart > self::$maxRGB) {
                    return FALSE;
                }
            }

            return TRUE;
        }

        return FALSE;
    }


    /**
     * Check if given color is in HEX mode.
     * @param string $color
     * @return type
     */
    public static function isHEX($color)
    {
        return (self::getRGBFromHEX($color) === FALSE) ? FALSE : TRUE;
    }


    /**
     * Check if given color is known color name.
     * @param string $color
     * @return bool
     */
    private function isString($color)
    {
        return (\is_string($color) === TRUE) ? \array_key_exists($color, self::$stringColors) : FALSE;
    }


    /** @return array */
    public function getRGB()
    {
        return self::getRGBFromHEX($this->color);
    }


    /** @return string */
    public function getHEX()
    {
        return $this->color;
    }


    /**
     * Set RGB options of a color.
     * @param array $color
     */
    public function setRGB(array $color)
    {
        if (self::isRGB($color) === FALSE) {
            $this->color = self::$fallbackColor;
        } else {
            $this->color = self::getHEXFromRGB($color[0], $color[1], $color[2]);
        }

    }


    /**
     * Set HEX option of a color.
     * @param string $color
     */
    public function setHEX($color)
    {
        if (self::isHEX($color) === FALSE) {
            $this->color = self::$fallbackColor;
        } else {
            $this->color = $color;
        }

    }


    /**
     * Shortcut to create the color object.
     * @param string $color
     * @return Color
     */
    public static function create($color)
    {
        return new Color($color);
    }


    /** @return bool|array */
    public static function getRGBFromHEX($color)
    {
        if (\is_array($color) === TRUE || \preg_match('/^[0-9A-Fa-f]{6}$/', $color) !== 1) {
            return FALSE;
        }

        $r = \hexdec(\substr($color, 0, 2));
        $g = \hexdec(\substr($color, 2, 2));
        $b = \hexdec(\substr($color, 4, 2));
        return [$r, $g, $b];
    }


    /** @return bool|string */
    public static function getHEXFromRGB($r, $g, $b)
    {
        if (self::isRGB([$r, $g, $b]) === FALSE) {
            return FALSE;
        }

        $rh = \dechex($r);
        $gh = \dechex($g);
        $bh = \dechex($b);
        return \strtoupper($rh) . \strtoupper($gh) . \strtoupper($bh);
    }


    /**
     * Allocate color for use in GD image.
     * @param resource $image
     * @param Color $color
     * @return int|bool
     */
    public static function allocateToImage($image, Color $color)
    {
        $rgb = $color->getRGB();
        return \imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
    }


}
