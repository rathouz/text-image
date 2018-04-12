<?php

/**
 * This file is part of the Pehape libraries (http://pehape.cz)
 * Copyright (c) 2016 Tomas Rathouz <trathouz at gmail.com>
 */

namespace Pehape\TextImage\Rendering;

use Pehape\TextImage\TextImage;
use Pehape\Tools\Objects;


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
     * @return Objects\Image
     */
    public static function render(TextImage $textImage)
    {
        $image = self::createEmptyImage(
            $textImage->getFullWidth(),
            $textImage->getFullHeight(),
            $textImage->getBorder(),
            $textImage->getBackgroundColor(),
            $textImage->getBorderColor(),
            $textImage->getTransparentBackground()
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
     * @param Objects\Color $backgroundColor
     * @param Objects\Color $borderColor
     * @param bool $transparentBackground
     * @return resource
     */
    private static function createEmptyImage($width, $height, array $border, Objects\Color $backgroundColor, Objects\Color $borderColor, $transparentBackground)
    {
        $image = imagecreatetruecolor($width, $height);
        
        if ($transparentBackground === TRUE) {
            imagealphablending($image, false);
            $transparency = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefill($image, 0, 0, $transparency);
            imagesavealpha($image, true);
        } else {
            $backColor = Objects\Color::allocateToImage($image, $backgroundColor);
            $bordColor = Objects\Color::allocateToImage($image, $borderColor);

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
        }
        
        return $image;
    }


    /**
     * Add text to GD image.
     * @param resource $image
     * @param string $text
     * @param string $font
     * @param int $fontSize
     * @param Objects\Color $textColor
     * @param array $offset
     * @return resource
     */
    private static function addTextToImage($image, $text, $font, $fontSize, Objects\Color $textColor, array $offset)
    {
        $color = Objects\Color::allocateToImage($image, $textColor);
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
            case Objects\Image::PNG:
                imagepng($image, $tmpFilename);
                break;
            case Objects\Image::JPG:
                imagejpeg($image, $tmpFilename);
                break;
            case Objects\Image::GIF:
                imagegif($image, $tmpFilename);
                break;
            default:
                $format = Objects\Image::PNG;
                imagepng($image, $tmpFilename);
                break;
        }

        $formatedFilename = $tmpFilename.".".$format;
        @rename($tmpFilename, $formatedFilename);
        imagedestroy($image);
        return new Objects\Image($formatedFilename, $format);
    }


}
