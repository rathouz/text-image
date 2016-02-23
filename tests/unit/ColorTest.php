<?php

use Pehape\TextImage\Utils\Color;


/**
 * Unit test for \Pehape\TextImage\Utils\Color.
 *
 * @author Tomas Rathouz <trathouz AT gmail.com>
 */
class ColorTest extends \PHPUnit_Framework_TestCase
{

    public function testRGBConstruct()
    {
        $blackColor = new Color([0, 0, 0]);
        $this->assertEquals('000000', $blackColor->getHEX());

        $whiteColor = new Color([255, 255, 255]);
        $this->assertEquals('FFFFFF', $whiteColor->getHEX());
    }


    public function testHEXConstruct()
    {
        $blackColor = new Color('000000');
        $this->assertEquals([0, 0, 0], $blackColor->getRGB());

        $whiteColor = new Color('FFFFFF');
        $this->assertEquals([255, 255, 255], $whiteColor->getRGB());
    }


    public function testStringConstruct()
    {
        $blackColor = new Color('black');
        $this->assertEquals([0, 0, 0], $blackColor->getRGB());
        $this->assertEquals('000000', $blackColor->getHEX());

        $whiteColor = new Color('white');
        $this->assertEquals([255, 255, 255], $whiteColor->getRGB());
        $this->assertEquals('FFFFFF', $whiteColor->getHEX());
    }


    public function testFallbackColor()
    {
        // Fallback
        $unknownRGBColor1 = new Color([333, 333, 333]);
        $this->assertEquals('000000', $unknownRGBColor1->getHEX());

        $unknownRGBColor2 = new Color(['abc', 333, 333]);
        $this->assertEquals('000000', $unknownRGBColor2->getHEX());

        $unknownRGBColor3 = new Color('qq');
        $this->assertEquals('000000', $unknownRGBColor3->getHEX());

        $unknownRGBColor4 = new Color('unknownColor');
        $this->assertEquals('000000', $unknownRGBColor4->getHEX());
    }


    public function testCheckRGB()
    {
        $this->assertEquals(TRUE, Color::isRGB([0, 0, 0]));
        // Limits
        $this->assertEquals(FALSE, Color::isRGB([-1, 0, 0]));
        $this->assertEquals(FALSE, Color::isRGB([256, 0, 0]));
        $this->assertEquals(FALSE, Color::isRGB('string'));
    }


    public function testCheckHEX()
    {
        $this->assertEquals(FALSE, Color::isHEX([0, 0, 0]));
        $this->assertEquals(FALSE, Color::isHEX('a'));
        $this->assertEquals(TRUE, Color::isHEX('FFAABB'));
        $this->assertEquals(TRUE, Color::isHEX('ffaabb'));
        // Limits
        $this->assertEquals(FALSE, Color::isHEX('gfaabb'));
    }


    public function testStringCases()
    {
        // HEX string should be lower case or upper case
        $whiteColorLower = new Color('ffffff');
        $this->assertEquals('FFFFFF', $whiteColorLower->getHEX());
        $whiteColorUpper = new Color('FFFFFF');
        $this->assertEquals('FFFFFF', $whiteColorUpper->getHEX());
        $whiteColorCombined = new Color('ffffFF');
        $this->assertEquals('FFFFFF', $whiteColorCombined->getHEX());

        $this->assertEquals(TRUE, Color::isHEX('aaaaaa'));
        $this->assertEquals(TRUE, Color::isHEX('Aaaaaa'));
        $this->assertEquals(TRUE, Color::isHEX('AAAAAA'));
    }


}
