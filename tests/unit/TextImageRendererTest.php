<?php

use Pehape\TextImage;


class TextImageRendererTest extends \PHPUnit_Framework_TestCase
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


    public function testRendering()
    {
        $image = $this->renderer->render($this->textImage);
        $this->assertInstanceOf('Pehape\TextImage\Utils\Image', $image);
    }


}
