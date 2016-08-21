<?php

/**
 * This file is part of the Pehape libraries (http://pehape.cz)
 * Copyright (c) 2016 Tomas Rathouz <trathouz at gmail.com>
 */

namespace Pehape\TextImage;

use Pehape\TextImage\Exceptions;
use Pehape\TextImage\Utils;


/**
 * TextImage class convert a text to image.
 *
 * @author Tomas Rathouz <trathouz at gmail.com>
 */
class TextImage
{

    /** @var string */
    private $text;

    /** @var string PNG has the best quality */
    private $format = Utils\Image::PNG;

    /** @var int */
    private $width = self::DEFAULT_WIDTH;

    /** @var int */
    private $height = self::HEIGHT_AUTO;

    /** @var int */
    private $lineHeight;

    /** @var Utils\Color */
    private $backgroundColor;

    /** @var Utils\Color */
    private $textColor;

    /** @var Utils\Color */
    private $borderColor;

    /** @var int|array */
    private $border = self::DEFAULT_BORDER;

    /** @var string */
    private $fontPath;

    /** @var int */
    private $fontSize = self::DEFAULT_FONT_SIZE;

    /** @var bool */
    private $wrapText = TRUE;

    /** @var bool */
    private $stripText = FALSE;

    /** @var string */
    private $stripTextString = self::STRIP_DOTS;

    /** @var int|array */
    private $padding = self::DEFAULT_PADDING;

    /** Strip text string */
    const STRIP_DOTS = '...';

    /** Space separator */
    const SEP_SPACE = ' ';

    /** Dimension options */
    const HEIGHT_AUTO = 0;

    /** Option indexes for padding and borders */
    const OPT_TOP = 0;
    const OPT_RIGHT = 1;
    const OPT_BOTTOM = 2;
    const OPT_LEFT = 3;
    const OPT_VERTICAL = 4;
    const OPT_HORIZONTAL = 5;
    const OPT_COMPLETE = 6;

    /** Defults */
    const DEFAULT_WIDTH = 600;
    const DEFAULT_PADDING = 10;
    const DEFAULT_FONT_SIZE = 14;
    const DEFAULT_BORDER = 0;


    /**
     * Constructor.
     * @param string $text
     * @throws Exceptions\ExtensionException
     * @throws \BadMethodCallException
     * @throws Exceptions\FileException
     */
    public function __construct($text = '')
    {
        if (\extension_loaded('gd') === FALSE) {
            throw new Exceptions\ExtensionException('PHP extension GD is not loaded.');
        }

        if (\is_string($text) === FALSE) {
            throw new \BadMethodCallException('Parameter "text" must be string.');
        }

        $this->text = $text;
        $this->fontPath = __DIR__ . '/../fonts/open-sans/OpenSans-Regular.ttf';
        if (\is_readable($this->fontPath) === FALSE) {
            throw new Exceptions\FileException('Directory with fonts "' . $this->fontPath . '" does not exist or is not readable.');
        }

        $this->setBackgroundColor(Utils\Color::create('white'));
        $this->setTextColor(Utils\Color::create('black'));
        $this->setBorderColor(Utils\Color::create('grey'));
    }


    /**
     * Generate final image.
     * @return Utils\Image
     */
    public function generate()
    {
        $this->lineHeight = $this->lineHeight ? : $this->fontSize + (int) ($this->fontSize / 2);
        $this->height = $this->height ? : $this->lineHeight * $this->getLinesCount();
        return Rendering\TextImageRenderer::render($this);
    }


    /**
     * Wrap text to image width.
     * @param string $separator
     * @return array
     */
    private function wrapText($separator = self::SEP_SPACE)
    {
        if ($this->wrapText === FALSE && $this->stripText === FALSE) {
            return [$this->text];
        }

        $words = \explode($separator, $this->text);
        $lines[0] = '';
        $lineIndex = 0;
        foreach ($words as $word) {
            if ($this->stripText === FALSE) {
                $lineText = ($lines[$lineIndex] === '') ? $word : $lines[$lineIndex] . $separator . $word;
            } else {
                $lineText = ($lines[$lineIndex] === '') ? $word . ' ' . self::STRIP_DOTS : $lines[$lineIndex] . $separator . $word;
            }

            $textBlock = \imagettfbbox($this->fontSize, 0, $this->fontPath, $lineText);
            if ($textBlock[2] > $this->width) {
                $lineIndex++;
                $lines[$lineIndex] = $word;
            } else {
                $lines[$lineIndex] = \str_replace($separator . self::STRIP_DOTS, '', $lineText);
            }
        }

        if ($this->stripText === TRUE) {
            $strippedText = \array_slice($lines, 0, 1);
            if (count($lines) === 1) {
                $strippedText = $lines[0];
            } else {
                $strippedText = $lines[0] . self::STRIP_DOTS;
            }

            return [$strippedText];
        }

        return $lines;
    }


    /** @return string */
    public function getText()
    {
        return $this->text;
    }


    /** @return string */
    public function getFormat()
    {
        return $this->format;
    }


    /** @return Utils\Color */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }


    /** @return Utils\Color */
    public function getTextColor()
    {
        return $this->textColor;
    }


    /** @return int */
    public function getFontSize()
    {
        return $this->fontSize;
    }


    /** @return string */
    public function getFontPath()
    {
        return $this->fontPath;
    }


    /** @return bool */
    public function getWrapText()
    {
        return $this->wrapText;
    }


    /** @return int */
    public function getWidth()
    {
        return $this->width;
    }


    /** @return int */
    public function getFullWidth()
    {
        return ($this->getWidth() + $this->getPadding(self::OPT_HORIZONTAL) + $this->getBorder(self::OPT_HORIZONTAL));
    }


    /** @return int */
    public function getHeight()
    {
        return $this->height;
    }


    /** @return int */
    public function getFullHeight()
    {
        return ($this->getHeight() + $this->getPadding(self::OPT_VERTICAL) + $this->getBorder(self::OPT_VERTICAL));
    }


    /** @return int */
    public function getLineHeight()
    {
        return $this->lineHeight;
    }


    /** @return array */
    public function getLines()
    {
        return $this->wrapText();
    }


    /** @return int */
    public function getLinesCount()
    {
        return count($this->wrapText());
    }


    /** @return array */
    private function getCombinedOption($option)
    {
        if (\is_array($option) === FALSE) {
            return \array_merge(
                \array_fill(0, 4, $option), \array_fill(4, 2, ($option * 2))
            );
        }

        switch (count($option)) {
            case 1:
                return \array_merge(
                    \array_fill(0, 4, $option[0]), \array_fill(4, 2, ($option[0] * 2))
                );
            case 2:
                return [
                    $option[0],
                    $option[1],
                    $option[0],
                    $option[1],
                    $option[0] * 2,
                    $option[1] * 2,
                ];
            case 3:
                return [
                    $option[0],
                    $option[1],
                    $option[2],
                    $option[1],
                    $option[0] + $option[2],
                    $option[1] * 2,
                ];
            case 4:
                return [
                    $option[0],
                    $option[1],
                    $option[2],
                    $option[3],
                    $option[0] + $option[2],
                    $option[1] + $option[3],
                ];
            default:
                return \array_merge(
                    \array_fill(0, 4, $option[0]), \array_fill(4, 2, ($option[0] * 2))
                );
        }
    }


    /**
     * @param int|array $option
     * @throws \BadMethodCallException
     * @return bool
     */
    private function parseCombinedOption($option, $parameter)
    {
        if (\is_int($option) === TRUE && $option >= 0) {
            // Valid integer
            return TRUE;
        } else if (\is_array($option) === TRUE && count($option) < 5) {
            // Valid array
            foreach ($option as $optionItem) {
                if ($optionItem < 0) {
                    throw new \BadMethodCallException($parameter . ' must be array of integers equal or grater than 0.');
                }
            }
        } else {
            throw new \BadMethodCallException($parameter . ' must be integer or array of integers equal or grater than 0.');
        }
    }


    /** @return array|int */
    public function getPadding($type = self::OPT_COMPLETE)
    {
        $combinedOption = $this->getCombinedOption($this->padding);
        if ($type >= self::OPT_COMPLETE || $type < 0) {
            return $combinedOption;
        }

        return $combinedOption[$type];
    }


    /** @return Utils\Color */
    public function getBorderColor()
    {
        return $this->borderColor;
    }


    /** @return array|int */
    public function getBorder($type = self::OPT_COMPLETE)
    {
        $combinedOption = $this->getCombinedOption($this->border);
        if ($type >= self::OPT_COMPLETE || $type < 0) {
            return $combinedOption;
        }

        return $combinedOption[$type];
    }


    /** @return bool */
    public function getStripText()
    {
        return $this->stripText;
    }


    /** @return string */
    public function getStripTextString()
    {
        return $this->stripTextString;
    }


    /**
     * Get start coordinates of text.
     * @return array
     */
    public function getTextOffset()
    {
        return [
            // Top
            ($this->getLineHeight() - (int) (($this->getLineHeight() - $this->getFontSize()) / 2) +
            $this->getPadding(self::OPT_TOP) + $this->getBorder(self::OPT_TOP)),
            // Right
            $this->getFullWidth() - $this->getPadding(self::OPT_RIGHT) - $this->getBorder(self::OPT_RIGHT),
            // Bottom
            $this->getFullHeight() - $this->getPadding(self::OPT_TOP) - $this->getBorder(self::OPT_TOP),
            // Left
            $this->getPadding(self::OPT_LEFT) + $this->getBorder(self::OPT_LEFT),
        ];
    }


    /**
     * @param string $text
     * @throws \BadMethodCallException
     * @return TextImage
     */
    public function setText($text)
    {
        if (\is_string($text) === FALSE) {
            throw new \BadMethodCallException('Parameter "text" must be string.');
        }

        $this->text = $text;
        return $this;
    }


    /**
     * @param string $format
     * @return TextImage
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }


    /**
     * @param Utils\Color $backgroundColor
     * @return TextImage
     */
    public function setBackgroundColor(Utils\Color $backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }


    /**
     * @param Utils\Color $textColor
     * @return TextImage
     */
    public function setTextColor(Utils\Color $textColor)
    {
        $this->textColor = $textColor;
        return $this;
    }


    /**
     * @param int $fontSize
     * @return TextImage
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
        return $this;
    }


    /**
     * @param string $fontPath Without ending '/'
     * @throws Exceptions\FileException
     * @return TextImage
     */
    public function setFontPath($fontPath)
    {
        if (\is_readable($fontPath) === FALSE) {
            throw new Exceptions\FileException('Font path "' . $fontPath . '" does not exist.');
        }

        $this->fontPath = $fontPath;
        return $this;
    }


    /**
     * @param bool $wrapText
     * @return TextImage
     */
    public function setWrapText($wrapText)
    {
        $this->wrapText = $wrapText;
        return $this;
    }


    /**
     * @param int $width
     * @throws \BadMethodCallException
     * @return TextImage
     */
    public function setWidth($width)
    {
        if (\is_int($width) === FALSE || $width <= 0) {
            throw new \BadMethodCallException('Width must be integer greater than zero.');
        }

        $this->width = $width;
        return $this;
    }


    /**
     * @param int $height
     * @throws \BadMethodCallException
     * @throws \LogicException
     * @return TextImage
     */
    public function setHeight($height)
    {
        if (\is_int($height) === FALSE || $height <= 0) {
            throw new \BadMethodCallException('Width must be integer greater than zero.');
        }

        if ($this->stripText === TRUE) {
            throw new \LogicException('Cannot set height when the strip option is activated.');
        }

        $this->height = $height;
        return $this;
    }


    /**
     * @param int $lineHeight
     * @return TextImage
     */
    public function setLineHeight($lineHeight)
    {
        $this->lineHeight = $lineHeight;
        return $this;
    }


    /**
     * @param int|array $padding
     * @return TextImage
     */
    public function setPadding($padding)
    {
        $this->parseCombinedOption($padding, 'Padding');
        $this->padding = $padding;
        return $this;
    }


    /**
     * @param Utils\Color $borderColor
     * @return TextImage
     */
    public function setBorderColor(Utils\Color $borderColor)
    {
        $this->borderColor = $borderColor;
        return $this;
    }


    /**
     * @param int|array $border
     * @return TextImage
     */
    public function setBorder($border)
    {
        $this->parseCombinedOption($border, 'Border');
        $this->border = $border;
        return $this;
    }


    /**
     * @param bool $stripText
     * @throws \LogicException
     * @return TextImage
     */
    public function setStripText($stripText)
    {
        if ($this->height !== self::HEIGHT_AUTO) {
            throw new \LogicException('Cannot strip text when the height is not auto [auto height means value set to 0].');
        }

        $this->stripText = $stripText;
        return $this;
    }


    /**
     * @param string $stripTextString
     * @return TextImage
     */
    public function setStripTextString($stripTextString)
    {
        $this->stripTextString = $stripTextString;
        return $this;
    }


}
