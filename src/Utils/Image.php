<?php

/**
 * This file is part of the Pehape libraries (http://pehape.cz)
 * Copyright (c) 2016 Tomas Rathouz <trathouz at gmail.com>
 */

namespace Pehape\TextImage\Utils;

use Pehape\TextImage\Exceptions;


/**
 * Image class represent the image file.
 *
 * @author Tomas Rathouz <trathouz at gmail.com>
 */
class Image
{

    /** @var string */
    private $imagePath;

    /** @var string */
    private $format;

    /** Image formats */
    const JPG = 'jpg';
    const PNG = 'png';
    const GIF = 'gif';


    /**
     * Constructor.
     * @param string $imagePath
     * @param string $format
     * @throws TextImage\FileException
     */
    public function __construct($imagePath, $format = self::PNG)
    {
        if (\is_file($imagePath) === FALSE) {
            throw new Exceptions\FileException('File "' . $imagePath . '" does not exist.');
        }

        $this->imagePath = $imagePath;
        $this->format = $this->getImageFormat($imagePath);
        if ($this->format !== $format) {
            throw new Exceptions\FileException('File format mismatch. Expected "' . $format . '" but found "' . $this->format . '".');
        }
    }


    /** @return string */
    private function getImageFormat($path)
    {
        return \strtolower(\pathinfo($path, PATHINFO_EXTENSION));
    }


    /** @return string */
    public function getImagePath()
    {
        return $this->imagePath;
    }


    /** @return string */
    public function getFormat()
    {
        return $this->format;
    }


    /** @return int */
    public function getWidth()
    {
        return \getimagesize($this->getImagePath())[0];
    }


    /** @return int */
    public function getHeight()
    {
        return \getimagesize($this->getImagePath())[1];
    }


    /**
     * @param string $imagePath
     * @throws TextImage\FileException
     * @return Image
     */
    public function setImagePath($imagePath, $format = NULL)
    {
        $format = isset($format) === FALSE ? $this->format : $format;
        if (\is_file($imagePath) === FALSE) {
            throw new Exceptions\FileException('File "' . $imagePath . '" does not exist.');
        }

        $this->imagePath = $imagePath;
        if ($this->getImageFormat($imagePath) !== $format) {
            throw new Exceptions\FileException('File format mismatch. Expected "' . $format . '" but found "' . $this->format . '".');
        }

        $this->format = $format;
        return $this;
    }


    /**
     * Copy this image.
     * @param string $destinationPath
     * @param bool $override
     * @throws TextImage\FileException
     * @return bool
     */
    public function copy($destinationPath, $override = FALSE)
    {
        if (\is_file($destinationPath) === TRUE && $override === FALSE) {
            throw new Exceptions\FileException('Destination file "' . $destinationPath . '" already exist.');
        }

        return @\copy($this->imagePath, $destinationPath);
    }


    /**
     * Move this image.
     * @param string $destinationPath
     * @throws TextImage\FileException
     * @return bool
     */
    public function move($destinationPath, $override = FALSE)
    {
        if (\is_file($destinationPath) === TRUE && $override === FALSE) {
            throw new Exceptions\FileException('Destination file "' . $destinationPath . '" already exist.');
        }

        if (@\rename($this->imagePath, $destinationPath) === TRUE) {
            $this->imagePath = $destinationPath;
            return TRUE;
        }

        return FALSE;
    }


    /**
     * Get base64 encoded URI data.
     * @return string
     */
    public function getUriData()
    {
        $data = \file_get_contents($this->imagePath);
        return \base64_encode($data);
    }


    /**
     * Get HTML img tag.
     * @return string
     */
    public function getHtmlTag()
    {
        $uriData = $this->getUriData();
        return sprintf('<img src="data:image/png;base64,%s">', $uriData);
    }


}
