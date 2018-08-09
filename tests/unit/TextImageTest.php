<?php

use Rathouz\TextImage;


class TextImageTest extends \PHPUnit_Framework_TestCase
{

    /** @var TextImage */
    private $textImage;

    /** @var TextImage\Rendering\TextImageRenderer */
    private $renderer;


    public function setUp()
    {
        $this->textImage = new TextImage\TextImage('Hello world');
        $this->renderer = new TextImage\Rendering\TextImageRenderer();
    }


    /** @expectedException \BadMethodCallException */
    public function testWrongConstruct_1()
    {
        $textImage = new TextImage\TextImage(20);
    }


    /** @expectedException \BadMethodCallException */
    public function testWrongConstruct_2()
    {
        $textImage = new TextImage\TextImage(TRUE);
    }


    /** @expectedException \BadMethodCallException */
    public function testWrongConstruct_3()
    {
        $textImage = new TextImage\TextImage([1, 2, 3]);
    }


    public function testDefaultDimensions()
    {
        $this->assertEquals(TextImage\TextImage::DEFAULT_WIDTH, $this->textImage->getWidth());
        $this->assertEquals(
            (TextImage\TextImage::DEFAULT_WIDTH + 2 * TextImage\TextImage::DEFAULT_PADDING),
            $this->textImage->getFullWidth()
        );
        $this->assertEquals(TextImage\TextImage::HEIGHT_AUTO, $this->textImage->getHeight());
        $this->assertEquals(
            (TextImage\TextImage::HEIGHT_AUTO + 2 * TextImage\TextImage::DEFAULT_PADDING),
            $this->textImage->getFullHeight()
        );

        $defaultPadding = \array_merge(
            \array_fill(0, 4, TextImage\TextImage::DEFAULT_PADDING),
            \array_fill(4, 2, (TextImage\TextImage::DEFAULT_PADDING * 2))
        );
        $defaultBorder = \array_merge(
            \array_fill(0, 4, TextImage\TextImage::DEFAULT_BORDER),
            \array_fill(4, 2, (TextImage\TextImage::DEFAULT_BORDER * 2))
        );
        $this->assertEquals($defaultPadding, $this->textImage->getPadding());
        $this->assertEquals($defaultBorder, $this->textImage->getBorder());
    }


    public function testSetWidth()
    {
        // Reset padding and border
        $this->textImage->setPadding(0);
        $this->textImage->setBorder(0);

        $this->textImage->setWidth(1024);
        $this->assertEquals(1024, $this->textImage->getWidth());
        $this->assertEquals(1024, $this->textImage->getFullWidth());
    }


    /** @expectedException \BadMethodCallException */
    public function testSetWrongWidth_1()
    {
        $this->textImage->setWidth(0);
    }


    /** @expectedException \BadMethodCallException */
    public function testSetWrongWidth_2()
    {
        $this->textImage->setWidth('wrong');
    }


    /** @expectedException \BadMethodCallException */
    public function testSetWrongWidth_3()
    {
        $this->textImage->setWidth([0]);
    }


    public function testSetHeight()
    {
        // Reset padding and border
        $this->textImage->setPadding(0);
        $this->textImage->setBorder(0);

        $this->textImage->setHeight(1024);
        $this->assertEquals(1024, $this->textImage->getHeight());
        $this->assertEquals(1024, $this->textImage->getFullHeight());
    }


    /** @expectedException \BadMethodCallException */
    public function testSetWrongHeight_1()
    {
        $this->textImage->setHeight(0);
    }


    /** @expectedException \BadMethodCallException */
    public function testSetWrongHeight_2()
    {
        $this->textImage->setHeight('wrong');
    }


    /** @expectedException \BadMethodCallException */
    public function testSetWrongHeight_3()
    {
        $this->textImage->setHeight([0]);
    }


    public function testSetFont()
    {
        $this->textImage->setFontPath(__DIR__ . '/../../examples/assets/fonts/open-sans/OpenSans-Regular.ttf');
    }


    /** @expectedException Rathouz\TextImage\Exceptions\FileException */
    public function testSetWrongFont()
    {
        $this->textImage->setFontPath(__DIR__ . '/../../examples/assets/fonts/open-sans/unnown.ttf');
    }


    public function testSetCorrectPadding()
    {
        $this->textImage->setPadding(10);
        $this->textImage->setPadding([10]);
        $this->textImage->setPadding([10, 10]);
        $this->textImage->setPadding([10, 10, 10]);
        $this->textImage->setPadding([10, 10, 10, 10]);
    }


    /** @expectedException \BadMethodCallException */
    public function testSetWrongPadding_1()
    {
        $this->textImage->setPadding(-1);
    }


    /** @expectedException \BadMethodCallException */
    public function testSetWrongPadding_2()
    {
        $this->textImage->setPadding([-1]);
    }


    /** @expectedException \BadMethodCallException */
    public function testSetWrongPadding_3()
    {
        $this->textImage->setPadding([1, 2, 3, 4, 5]);
    }


    public function testSetCorrectBorder()
    {
        $this->textImage->setPadding(10);
        $this->textImage->setPadding([10]);
        $this->textImage->setPadding([10, 10]);
        $this->textImage->setPadding([10, 10, 10]);
        $this->textImage->setPadding([10, 10, 10, 10]);
    }


    /** @expectedException \BadMethodCallException */
    public function testSetWrongBorder_1()
    {
        $this->textImage->setBorder(-1);
    }


    /** @expectedException \BadMethodCallException */
    public function testSetWrongBorder_2()
    {
        $this->textImage->setBorder([-1]);
    }


    /** @expectedException \BadMethodCallException */
    public function testSetWrongBorder_3()
    {
        $this->textImage->setBorder([1, 2, 3, 4, 5]);
    }

    
    private function resetImageParameters()
    {
        $this->textImage = new TextImage\TextImage('Hello world');
        $this->textImage->setPadding(0);
        $this->textImage->setBorder(0);
        $this->textImage->setLineHeight(0);
    }


    private function resetImageParametersMultiline()
    {
        $this->textImage = new TextImage\TextImage('Hello world. The text-image library is a very tiny library to generate convert text to images.');
        $this->textImage->setPadding(0);
        $this->textImage->setBorder(0);
        $this->textImage->setLineHeight(0);
    }


    private function getImageWidth()
    {
        $image = $this->textImage->generate();
        return \getimagesize($image->getImagePath())[0];
    }


    private function getImageHeight()
    {
        $image = $this->textImage->generate();
        return \getimagesize($image->getImagePath())[1];
    }


    public function testValidateDimensions_Default()
    {
        $this->resetImageParameters();
        $height = ($this->textImage->getFontSize() + $this->textImage->getFontSize() / 2);
        $this->assertEquals(600, $this->getImageWidth());
        $this->assertEquals($height, $this->getImageHeight());
    }


    public function testValidateDimensions_Width()
    {
        $this->resetImageParameters();
        $height = ($this->textImage->getFontSize() + $this->textImage->getFontSize() / 2);
        $this->textImage->setWidth(600);
        $this->assertEquals(600, $this->getImageWidth());
        $this->assertEquals($height, $this->getImageHeight());
    }


    public function testValidateDimensions_WidthLessThanText_NoWrap()
    {
        $this->resetImageParameters();
        $height = ($this->textImage->getFontSize() + $this->textImage->getFontSize() / 2);
        $this->textImage->setWidth(10);
        $this->textImage->setWrapText(FALSE);
        $this->assertEquals(10, $this->getImageWidth());
        $this->assertEquals($height, $this->getImageHeight());
    }


    public function testValidateDimensions_HeightLessThanFontSize()
    {
        $this->resetImageParameters();
        $this->textImage->setHeight(10);
        $this->assertEquals(10, $this->getImageHeight());
    }


    public function testValidateDimensions_Height_1()
    {
        $this->resetImageParameters();
        $this->textImage->setHeight(1);
        $this->assertEquals(1, $this->getImageHeight());
    }


    public function testValidateDimensions_Height_1_Padding()
    {
        $this->resetImageParameters();
        $this->textImage->setHeight(1);
        $this->textImage->setPadding(1);
        $this->assertEquals(3, $this->getImageHeight());
    }


    public function testValidateDimensions_Padding()
    {
        $this->resetImageParameters();
        $height = ($this->textImage->getFontSize() + $this->textImage->getFontSize() / 2);
        $this->textImage->setWidth(600);
        $this->textImage->setPadding(10);
        $this->assertEquals(620, $this->getImageWidth());
        $this->assertEquals($height + 20, $this->getImageHeight());
    }


    public function testValidateDimensions_PaddingAndBorder()
    {
        $this->resetImageParameters();
        $height = ($this->textImage->getFontSize() + $this->textImage->getFontSize() / 2);
        $this->textImage->setWidth(600);
        $this->textImage->setPadding(10);
        $this->textImage->setBorder(10);
        $this->assertEquals(640, $this->getImageWidth());
        $this->assertEquals(($height + 20 + 20), $this->getImageHeight());
    }


    public function testValidateDimensions_ML_Default()
    {
        $this->resetImageParametersMultiLine();
        $height = (($this->textImage->getFontSize() + $this->textImage->getFontSize() / 2) * 2);
        $this->assertEquals(600, $this->getImageWidth());
        $this->assertEquals($height, $this->getImageHeight());
    }


    public function testValidateDimensions_ML_Width()
    {
        $this->resetImageParametersMultiline();
        $height = (($this->textImage->getFontSize() + $this->textImage->getFontSize() / 2) * 2);
        $this->textImage->setWidth(600);
        $this->assertEquals(600, $this->getImageWidth());
        $this->assertEquals($height, $this->getImageHeight());
    }


    public function testValidateDimensions_ML_WidthLessThanText_NoWrap()
    {
        $this->resetImageParametersMultiline();
        $height = ($this->textImage->getFontSize() + $this->textImage->getFontSize() / 2);
        $this->textImage->setWidth(10);
        $this->textImage->setWrapText(FALSE);
        $this->assertEquals(10, $this->getImageWidth());
        $this->assertEquals($height, $this->getImageHeight());
    }


    public function testValidateDimensions_ML_HeightLessThanFontSize()
    {
        $this->resetImageParametersMultiline();
        $this->textImage->setHeight(10);
        $this->assertEquals(10, $this->getImageHeight());
    }


    public function testValidateDimensions_ML_Height_1()
    {
        $this->resetImageParametersMultiline();
        $this->textImage->setHeight(1);
        $this->assertEquals(1, $this->getImageHeight());
    }


    public function testValidateDimensions_ML_Height_1_Padding()
    {
        $this->resetImageParametersMultiline();
        $this->textImage->setHeight(1);
        $this->textImage->setPadding(1);
        $this->assertEquals(3, $this->getImageHeight());
    }


    public function testValidateDimensions_ML_Padding()
    {
        $this->resetImageParametersMultiline();
        $height = (($this->textImage->getFontSize() + $this->textImage->getFontSize() / 2) * 2);
        $this->textImage->setWidth(600);
        $this->textImage->setPadding(10);
        $this->assertEquals(620, $this->getImageWidth());
        $this->assertEquals(($height + 20), $this->getImageHeight());
    }


    public function testValidateDimensions_ML_PaddingAndBorder()
    {
        $this->resetImageParametersMultiline();
        $height = (($this->textImage->getFontSize() + $this->textImage->getFontSize() / 2) * 2);
        $this->textImage->setWidth(600);
        $this->textImage->setPadding(10);
        $this->textImage->setBorder(10);
        $this->assertEquals(640, $this->getImageWidth());
        $this->assertEquals(($height + 20 + 20), $this->getImageHeight());
    }


}
