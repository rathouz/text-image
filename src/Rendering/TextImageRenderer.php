<?php

/**
 * This file is part of the Pehape libraries (http://pehape.cz)
 * Copyright (c) 2016 Tomas Rathouz <trathouz at gmail.com>
 */

namespace Pehape\TextImage\Rendering;

use Pehape\TextImage\TextImage;
use Pehape\TextImage\Utils;


/**
 * TextImageRenderer render the TextImage object.
 *
 * @author Tomas Rathouz <trathouz at gmail.com>
 */
class TextImageRenderer
{


    /**
     * Render image.
     * @param TextImage $textImage
     * @return Utils\Image
     */
    public static function render(TextImage $textImage)
    {
        $image = self::createEmptyImage(
            $textImage->getFullWidth(),
            $textImage->getFullHeight(),
            $textImage->getBorder(),
            $textImage->getBackgroundColor(),
            $textImage->getBorderColor()
        );
        $textOffset = $textImage->getTextOffset();
        foreach ($textImage->getLines() as $line) {
            $image = self::addTextToImage(
                $image,
                $line,
                $textImage->getFontPath(),
                $textImage->getFontSize(),
                $textImage->getTextColor(),
                $textOffset
            );
            if ($textImage->getStripText() === TRUE) {
                break;
            }

            $textOffset[TextImage::OPT_TOP] += $textImage->getLineHeight();
        }

        return self::generateRealImage($image, $textImage->getFormat());
    }


    /**
     * Create empty GD image.
     * @param int $width
     * @param int $height
     * @param array $border
     * @param Utils\Color $backgroundColor
     * @param Utils\Color $borderColor
     * @return resource
     */
    private static function createEmptyImage($width, $height, array $border, Utils\Color $backgroundColor, Utils\Color $borderColor)
    {
        $image = imagecreatetruecolor($width, $height);
        $backColor = Utils\Color::allocateToImage($image, $backgroundColor);
        $bordColor = Utils\Color::allocateToImage($image, $borderColor);
        // Border
        imagefilledrectangle($image, 0, 0, $width, $height, $bordColor);
        // Background
        imagefilledrectangle(
            $image,
            $border[TextImage::OPT_LEFT],
            $border[TextImage::OPT_TOP],
            ($width - $border[TextImage::OPT_RIGHT]),
            ($height - $border[TextImage::OPT_BOTTOM]),
            $backColor
        );
        return $image;
    }


    /**
     * Add text to GD image.
     * @param resource $image
     * @param string $text
     * @param string $font
     * @param int $fontSize
     * @param Utils\Color $textColor
     * @param array $offset
     * @return resource
     */
    private static function addTextToImage($image, $text, $font, $fontSize, Utils\Color $textColor, array $offset)
    {
        $color = Utils\Color::allocateToImage($image, $textColor);
        imagettftext($image, $fontSize, 0, $offset[TextImage::OPT_LEFT], $offset[TextImage::OPT_TOP], $color, $font, $text);
        return $image;
    }


    /**
     * Generate real image.
     * @param resource $image
     * @param string $format
     * @return \App\Libraries\Image
     */
    private static function generateRealImage($image, $format)
    {
        $tempFile = tmpfile();
        $metaDatas = stream_get_meta_data($tempFile);
        $tmpFilename = $metaDatas['uri'];
        fclose($tempFile);
        switch ($format) {
            case Utils\Image::PNG:
                imagepng($image, $tmpFilename);
                break;
            case Utils\Image::JPG:
                imagejpeg($image, $tmpFilename);
                break;
            case Utils\Image::GIF:
                imagegif($image, $tmpFilename);
                break;
            default:
                $format = Utils\Image::PNG;
                imagepng($image, $tmpFilename);
                break;
        }

        $formatedFilename = $tmpFilename.".".$format;
        @rename($tmpFilename, $formatedFilename);
        imagedestroy($image);
        return new Utils\Image($formatedFilename, $format);
    }


}
