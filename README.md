[TextImage](http://pehape.github.io/text-image/) 
=============
A simple PHP library to convert text to image.

## Installation ##

The best way to install TextImage is using  [Composer](http://getcomposer.org/):

```sh
$ composer require pehape/text-image:~1.0
```

## Examples ##

### Basic image ###

```ruby
$basicImage = new \Pehape\TextImage\TextImage('Basic image');
$image = $basicImage->generate();
echo $image->getHtmlTag();
```

![alt tag](https://raw.githubusercontent.com/pehape/text-image/master/examples/assets/images/image.png)

### Image with background ###

```ruby
$backgroundImage = new \Pehape\TextImage\TextImage('Image with background');
$backgroundImage->setBackgroundColor($greyColor);
$image2 = $backgroundImage->generate();
echo $image2->getHtmlTag();
```

![alt tag](https://raw.githubusercontent.com/pehape/text-image/master/examples/assets/images/image2.png)

### Image with border ###

```ruby
$borderedImage = new \Pehape\TextImage\TextImage('Image with border');
$borderedImage->setBackgroundColor($greyColor);
$borderedImage->setBorder(3);
$borderedImage->setBorderColor($blackColor);
$image3 = $borderedImage->generate();
echo $image3->getHtmlTag();
```

![alt tag](https://raw.githubusercontent.com/pehape/text-image/master/examples/assets/images/image3.png)

### Image with blue text color ###

```ruby
$coloredImage = new \Pehape\TextImage\TextImage('Image with blue text color');
$coloredImage->setBackgroundColor($greyColor);
$coloredImage->setTextColor($redColor);
$image4 = $coloredImage->generate();
echo $image4->getHtmlTag();
```

![alt tag](https://raw.githubusercontent.com/pehape/text-image/master/examples/assets/images/image4.png)

### Image with custom padding ###

```ruby
$paddedImage = new \Pehape\TextImage\TextImage('Image with custom padding');
$paddedImage->setBackgroundColor($greyColor);
$paddedImage->setPadding(30);
$image5 = $paddedImage->generate();
echo $image5->getHtmlTag();
```

![alt tag](https://raw.githubusercontent.com/pehape/text-image/master/examples/assets/images/image5.png)

### Image with wrapped text ###

```ruby
$wrappedImage = new \Pehape\TextImage\TextImage('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac eros finibus, pretium erat non, fermentum leo. Curabitur hendrerit lobortis risus.');
$wrappedImage->setBackgroundColor($greyColor);
$wrappedImage->setPadding(30);
$image6 = $wrappedImage->generate();
echo $image6->getHtmlTag();
```

![alt tag](https://raw.githubusercontent.com/pehape/text-image/master/examples/assets/images/image6.png)

### Image with custom line height set ###

```ruby
$lineImage = new \Pehape\TextImage\TextImage('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac eros finibus, pretium erat non, fermentum leo. Curabitur hendrerit lobortis risus.');
$lineImage->setBackgroundColor($greyColor);
$lineImage->setPadding(30);
$lineImage->setLineHeight(40);
$image7 = $lineImage->generate();
echo $image7->getHtmlTag();
```

![alt tag](https://raw.githubusercontent.com/pehape/text-image/master/examples/assets/images/image7.png)

### Image with stripped text ###

```ruby
$strippedImage = new \Pehape\TextImage\TextImage('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac eros finibus, pretium erat non, fermentum leo. Curabitur hendrerit lobortis risus.');
$strippedImage->setBackgroundColor($greyColor);
$strippedImage->setStripText(TRUE);
$image8 = $strippedImage->generate();
echo $image8->getHtmlTag();
```

![alt tag](https://raw.githubusercontent.com/pehape/text-image/master/examples/assets/images/image8.png)

### Image with custom font ###

```ruby
$customFontImage = new \Pehape\TextImage\TextImage('OpenSans-Bold.ttf');
$customFontImage->setFontPath(__DIR__ . '/assets/fonts/open-sans/OpenSans-Bold.ttf');
$customFontImage->setBackgroundColor($greyColor);
$image9 = $customFontImage->generate();
echo $image9->getHtmlTag();
```

![alt tag](https://raw.githubusercontent.com/pehape/text-image/master/examples/assets/images/image9.png)
