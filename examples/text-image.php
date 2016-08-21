<?php

require __DIR__ . '/../vendor/autoload.php';

$greyColor = Pehape\TextImage\Utils\Color::create('grey');
$blackColor = Pehape\TextImage\Utils\Color::create('black');
$redColor = Pehape\TextImage\Utils\Color::create('blue');

// Basic image
$basicImage = new \Pehape\TextImage\TextImage('Basic image');
$image1 = $basicImage->generate();

// Image with background
$backgroundImage = new \Pehape\TextImage\TextImage('Image with background');
$backgroundImage->setBackgroundColor($greyColor);
$image2 = $backgroundImage->generate();

// Image border
$borderedImage = new \Pehape\TextImage\TextImage('Image with border');
$borderedImage->setBackgroundColor($greyColor);
$borderedImage->setBorder(3);
$borderedImage->setBorderColor($blackColor);
$image3 = $borderedImage->generate();

// Text color
$coloredImage = new \Pehape\TextImage\TextImage('Image with blue text color');
$coloredImage->setBackgroundColor($greyColor);
$coloredImage->setTextColor($redColor);
$image4 = $coloredImage->generate();

// More padding
$paddedImage = new \Pehape\TextImage\TextImage('Image with custom padding');
$paddedImage->setBackgroundColor($greyColor);
$paddedImage->setPadding(30);
$image5 = $paddedImage->generate();

// Wrapping
$wrappedImage = new \Pehape\TextImage\TextImage('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac eros finibus, pretium erat non, fermentum leo. Curabitur hendrerit lobortis risus.');
$wrappedImage->setBackgroundColor($greyColor);
$wrappedImage->setPadding(30);
$image6 = $wrappedImage->generate();

// Line height
$lineImage = new \Pehape\TextImage\TextImage('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac eros finibus, pretium erat non, fermentum leo. Curabitur hendrerit lobortis risus.');
$lineImage->setBackgroundColor($greyColor);
$lineImage->setPadding(30);
$lineImage->setLineHeight(40);
$image7 = $lineImage->generate();

// Stripping
$strippedImage = new \Pehape\TextImage\TextImage('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac eros finibus, pretium erat non, fermentum leo. Curabitur hendrerit lobortis risus.');
$strippedImage->setBackgroundColor($greyColor);
$strippedImage->setStripText(TRUE);
$image8 = $strippedImage->generate();

// Set custom font
$customFontImage = new \Pehape\TextImage\TextImage('OpenSans-Bold.ttf');
$customFontImage->setFontPath(__DIR__ . '/assets/fonts/open-sans/OpenSans-Bold.ttf');
$customFontImage->setBackgroundColor($greyColor);
$image9 = $customFontImage->generate();

?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600italic" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html { height: 100%; }
        body { font-family: 'Open Sans', sans-serif; }
        h2, .content, img { text-align: center; margin-bottom: 20px; }
        h3 { margin-bottom: 40px; }
        pre { text-align: left; }
    </style>
</head>
<body>

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <h2 class="text-primary">Library pehape/text-image</h2>
            
            <div class="content">
            <p>Converting text to images using pehape/text-image library.</p>
            
            <h3>Default image</h3>
            <?php echo $image1->getHtmlTag(); ?>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    
<pre>$basicImage = new \Pehape\TextImage\TextImage('Basic image');
$image = $basicImage->generate();
echo $image->getHtmlTag();</pre>

                </div>
            </div>
            
            <h3>Image with background</h3>
            <?php echo $image2->getHtmlTag(); ?>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    
<pre>$backgroundImage = new \Pehape\TextImage\TextImage('Image with background');
$backgroundImage->setBackgroundColor($greyColor);
$image2 = $backgroundImage->generate();
echo $image2->getHtmlTag();</pre>

                </div>
            </div>
            
            <h3>Image with border</h3>
            <?php echo $image3->getHtmlTag(); ?>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    
<pre>$borderedImage = new \Pehape\TextImage\TextImage('Image with border');
$borderedImage->setBackgroundColor($greyColor);
$borderedImage->setBorder(3);
$borderedImage->setBorderColor($blackColor);
$image3 = $borderedImage->generate();
echo $image3->getHtmlTag();</pre>

                </div>
            </div>
            
            <h3>Image with blue text color</h3>
            <?php echo $image4->getHtmlTag(); ?>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    
<pre>$coloredImage = new \Pehape\TextImage\TextImage('Image with blue text color');
$coloredImage->setBackgroundColor($greyColor);
$coloredImage->setTextColor($redColor);
$image4 = $coloredImage->generate();
echo $image4->getHtmlTag();</pre>

                </div>
            </div>
            
            <h3>Image with custom padding</h3>
            <?php echo $image5->getHtmlTag(); ?>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    
<pre>$paddedImage = new \Pehape\TextImage\TextImage('Image with custom padding');
$paddedImage->setBackgroundColor($greyColor);
$paddedImage->setPadding(30);
$image5 = $paddedImage->generate();
echo $image5->getHtmlTag();</pre>

                </div>
            </div>
            
            <h3>Image with wrapped text</h3>
            <?php echo $image6->getHtmlTag(); ?>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    
<pre>$wrappedImage = new \Pehape\TextImage\TextImage('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac eros finibus, pretium erat non, fermentum leo. Curabitur hendrerit lobortis risus.');
$wrappedImage->setBackgroundColor($greyColor);
$wrappedImage->setPadding(30);
$image6 = $wrappedImage->generate();
echo $image6->getHtmlTag();</pre>

                </div>
            </div>
            
            <h3>Image with custom line height set</h3>
            <?php echo $image7->getHtmlTag(); ?>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    
<pre>$lineImage = new \Pehape\TextImage\TextImage('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac eros finibus, pretium erat non, fermentum leo. Curabitur hendrerit lobortis risus.');
$lineImage->setBackgroundColor($greyColor);
$lineImage->setPadding(30);
$lineImage->setLineHeight(40);
$image7 = $lineImage->generate();
echo $image7->getHtmlTag();</pre>

                </div>
            </div>
            
            <h3>Image with stripped text</h3>
            <?php echo $image8->getHtmlTag(); ?>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    
<pre>$strippedImage = new \Pehape\TextImage\TextImage('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac eros finibus, pretium erat non, fermentum leo. Curabitur hendrerit lobortis risus.');
$strippedImage->setBackgroundColor($greyColor);
$strippedImage->setStripText(TRUE);
$image8 = $strippedImage->generate();
echo $image8->getHtmlTag();</pre>

                </div>
            </div>
            
            <h3>Image with custom font</h3>
            <?php echo $image9->getHtmlTag(); ?>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    
<pre>$customFontImage = new \Pehape\TextImage\TextImage('OpenSans-Bold.ttf');
$customFontImage->setFontPath(__DIR__ . '/assets/fonts/open-sans/OpenSans-Bold.ttf');
$customFontImage->setBackgroundColor($greyColor);
$image9 = $customFontImage->generate();
echo $image9->getHtmlTag();</pre>

                </div>
            </div>
            
        </div>
    </div>
    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</body>
</html>
