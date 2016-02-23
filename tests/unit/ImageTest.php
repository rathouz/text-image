<?php

use Pehape\TextImage\Utils\Image;


/**
 * Unit test for \Pehape\TextImage\Utils\Image.
 *
 * @author Tomas Rathouz <trathouz AT gmail.com>
 */
class ImageTest extends \PHPUnit_Framework_TestCase
{

    /** @var string */
    private $assetsPath = __DIR__ . '/../../examples/assets/';

    /** @var string */
    private $outputPath = __DIR__ . '/../_output/';


    public function testImageConstruct()
    {
        $pngImage = new Image($this->assetsPath . 'image.png');
        $this->assertEquals('png', $pngImage->getFormat());

        $noextImage = new Image($this->assetsPath . 'image-noext', '');
        $this->assertEquals('', $noextImage->getFormat());
    }


    /** @expectedException \Pehape\TextImage\FileException */
    public function testImageConstructException()
    {
        $pngImage = new Image('unknown-image');
    }


    /** @expectedException \Pehape\TextImage\FileException */
    public function testImageConstructFormatException()
    {
        $pngImage = new Image($this->assetsPath . 'image.png', 'jpg');
    }


    /** @expectedException \Pehape\TextImage\FileException */
    public function testDisableUpperFormatException()
    {
        $pngImage = new Image($this->assetsPath . 'image.png', 'PNG');
    }


    public function testGetImageWidth()
    {
        $pngImage = new Image($this->assetsPath . 'image.png', 'png');
        $this->assertEquals(660, $pngImage->getWidth());
    }


    public function testGetImageHeight()
    {
        $pngImage = new Image($this->assetsPath . 'image.png', 'png');
        $this->assertEquals(81, $pngImage->getHeight());
    }


    public function testImageFormatException()
    {
        $pngImage1 = new Image($this->assetsPath . 'image.png');
        $this->assertEquals('png', $pngImage1->getFormat());

        $pngImage2 = new Image($this->assetsPath . 'image.png', 'png');
        $this->assertEquals('png', $pngImage2->getFormat());

        $pngImage3 = new Image($this->assetsPath . 'image-noext', '');
        $this->assertEquals('', $pngImage3->getFormat());
    }


    public function testSetImagePath()
    {
        $pngImage = new Image($this->assetsPath . 'image.png');
        $pngImage->setImagePath($this->assetsPath . 'image.png');
        $pngImage->setImagePath($this->assetsPath . 'image.png', 'png');
    }


    /** @expectedException \Pehape\TextImage\FileException */
    public function testSetImagePathException()
    {
        $pngImage = new Image($this->assetsPath . 'image.png');
        $pngImage->setImagePath($this->assetsPath . 'image.png', 'jpg');
    }


    /** @expectedException \Pehape\TextImage\FileException */
    public function testSetImageFormatException()
    {
        $pngImage = new Image($this->assetsPath . 'image.png');
        $pngImage->setImagePath($this->assetsPath . 'image.png', 'jpg');
    }


    public function testGetUriData()
    {
        $pngImage = new Image($this->assetsPath . 'image.png');
        $this->assertGreaterThan(0, \strlen($pngImage->getUriData()));
    }


    public function testGetHtmlTag()
    {
        $pngImage = new Image($this->assetsPath . 'image.png');
        $this->assertGreaterThan(0, \strlen($pngImage->getHtmlTag()));
    }


    public function testCopyImage()
    {
        $newPath = $this->outputPath . '/image.png';
        $pngImage = new Image($this->assetsPath . 'image.png');
        $copyResult1 = $pngImage->copy($newPath);
        $this->assertEquals(TRUE, $copyResult1);
        if (\is_file($newPath) === FALSE) {
            $this->fail('Copying image failed.');
        }

        $copyResult2 = $pngImage->copy(__DIR__ . '/nonexistingptah/no');
        $this->assertEquals(FALSE, $copyResult2);
    }


    /** @expectedException \Pehape\TextImage\FileException */
    public function testCopyImageExist()
    {
        $newPath = $this->outputPath . '/image.png';
        $pngImage = new Image($this->assetsPath . 'image.png');
        $pngImage->copy($newPath);
        $pngImage->copy($newPath);
    }


    public function testCopyImageExistOverride()
    {
        $newPath = $this->outputPath . '/image.png';
        $pngImage = new Image($this->assetsPath . 'image.png');
        $pngImage->copy($newPath);
        $pngImage->copy($newPath, TRUE);
    }


    public function testMoveImage()
    {
        $pngImage = new Image($this->assetsPath . 'image.png');
        $copyResult = $pngImage->copy(__DIR__ . '/nonexistingptah/no');
        $this->assertEquals(FALSE, $copyResult);
    }


    public function tearDown()
    {
        @\unlink($this->outputPath . '/image.png');
    }


}
