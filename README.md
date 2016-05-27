# kijtra/heads

HTML `<head>` tag helper for PHP.


## Usage

```sh
composer.phar require kijtra/heads
```

```php
<?php
require '/path/to/vendor/autoload.php';
use Kijtra\Heads;
```


## Standerd Use

```php
<?php
Heads::add('charset', 'utf-8');
Heads::add('description', 'My Site description.');

Heads::print();
```

To

```html
<meta charset="utf-8">
<meta name="description" content="My Site description.">
```


## Multiple Adding

```php
<?php
Heads::add('keywords', 'word1, word2');
Heads::add('keyword', 'word3'); // typo OK
Heads::add('keywords', array('word3', 'word4'));
Heads::add('viewport', 'width=device-width');
Heads::add('viewport', 'initial-scale=1');

Heads::print();
```

To

```html
<meta name="keywords" content="word1,word2,word3,word4">
<meta name="viewport" content="width=device-width,initial-scale=1">
```


## Auto detect attributes and tag

```php
<?php
Heads::add('refresh', 'example.html', 20);
Heads::option('timezone', 'Asia/Tokyo'); // Default Timeone for Expires
Heads::add('expires', '+5 years');
Heads::add('next', 'next.html'); // To <link> tag
Heads::add('prev', 'prev.html');

Heads::print();
```

To

```html
<meta http-equiv="Refresh" content="20; URL=example.html">
<meta http-equiv="Expires" content="Thu, 27 May 2021 13:55:02 +0900">
<link rel="prev" href="prev.html">
<link rel="next" href="next.html">
```


## Favicon

```php
<?php
Heads::add('favicon', 'favicon1.ico');
Heads::add('icon', array(
    16 => 'favicon-16.png',
    32 => 'favicon-32.png',
    48 => 'favicon-48.png',
    62 => 'favicon-62.png',
));

Heads::print();
```

To

```html
<link rel="icon" href="favicon1.ico" type="image/x-icon">
<link rel="icon" href="favicon-16.png" type="image/png" sizes="16x16">
<link rel="icon" href="favicon-32.png" type="image/png" sizes="32x32">
<link rel="icon" href="favicon-48.png" type="image/png" sizes="48x48">
<link rel="icon" href="favicon-62.png" type="image/png" sizes="62x62">
```

## RSS, EditURI, oEmbed...

```php
<?php
Heads::add('rss', 'http://example.com/feed.rss', 'RSS2.0');
Heads::add('atom', 'http://example.com/feed.atom', 'Atom 0.3');
Heads::add('search', '/open_search.xml');
Heads::add('edit', 'https://example.com/xmlrpc.php?rsd', 'RSD');
Heads::add('me', 'mailto:mail@example.com');
Heads::add('me', 'sms:+123456789');
Heads::add('publisher', 'https://plus.google.com/+YourPage');
Heads::add('oembed', 'http://example.com/oembed?url=xxxxx', 'xml');
Heads::add('oembed', 'http://example.com/oembed?url=xxxxx&format=json');

Heads::print();
```

To

```html
<link rel="alternate" href="http://example.com/feed.rss" type="application/rss+xml" title="RSS2.0">
<link rel="alternate" href="http://example.com/feed.atom" type="application/atom+xml" title="Atom 0.3">
<link rel="search" href="/open_search.xml" type="application/opensearchdescription+xml">
<link rel="EditURI" href="https://example.com/xmlrpc.php?rsd" type="application/rsd+xml" title="RSD">
<link rel="me" href="mailto:mail@example.com">
<link rel="me" href="sms:+123456789">
<link rel="publisher" href="https://plus.google.com/+YourPage">
<link rel="http://example.com/oembed?url=xxxxx" type="application/xml+oembed">
<link rel="http://example.com/oembed?url=xxxxx&amp;format=json" type="application/json+oembed">
```

## Open Graph

```php
<?php
Heads::add('og:url', 'https://example.com/page.html');
Heads::add('og:type', 'website');
Heads::add('og:title', 'Content Title');
Heads::add('og:image', 'https://example.com/image.jpg');
Heads::add('og:description', 'Description Here');
Heads::add('og:site_name', 'Site Name');
Heads::add('og:locale', 'en_US');
Heads::add('music:album', 'Music Album');
Heads::add('video:actor', 'Video Actor');
Heads::add('article:author', 'Article Author');
Heads::add('book:author', 'Book Author');
Heads::add('profile:username', 'username');
Heads::add('op:markup_version', 'v1.0');
Heads::add('fb:app_id', '123456789');
Heads::add('fb:article_style', 'article style');

Heads::print();
```

To

```html
<meta property="og:url" content="https://example.com/page.html">
<meta property="og:type" content="website">
<meta property="og:title" content="Content Title">
<meta property="og:image" content="https://example.com/image.jpg">
<meta property="og:description" content="Description Here">
<meta property="og:site_name" content="Site Name">
<meta property="og:locale" content="en_US">
<meta property="music:album" content="Music Album">
<meta property="video:actor" content="Video Actor">
<meta property="article:author" content="Article Author">
<meta property="book:author" content="Book Author">
<meta property="profile:username" content="username">
<meta property="op:markup_version" content="v1.0">
<meta property="fb:app_id" content="123456789">
<meta property="fb:article_style" content="article style">
```


## Twitter Cards

Excamples of [Gallery Card](https://dev.twitter.com/cards/types/gallery)

```php
<?php
Heads::add('twitter:card', 'gallery');
Heads::add('twitter:site', '@fodorstravel');
Heads::add('twitter:creator', '@fodorstravel');
Heads::add('twitter:title', 'America\'s Best Small Towns');
Heads::add('twitter:description', 'For the second year in a row, we\'ve compiled a list that highlights some of the best places in the country you don\'t hear about every day.');
Heads::add('twitter:url', 'http://www.fodors.com/news/photos/americas-best-small-towns');
Heads::add('twitter:gallery', 'image0.jpg');
Heads::add('twitter:gallery', array(
    'http://www.fodors.com/ee/files/slideshows/telluride-resized.jpg',
    'http://www.fodors.com/ee/files/slideshows/shutterstock_18216130-resized.jpg',
    'http://www.fodors.com/ee/files/slideshows/3-marfa-texas.jpg',
    'http://www.fodors.com/ee/files/slideshows/4-paia-maui-hawaii.jpg',
));

Heads::print();
```

To

```html
<meta property="twitter:card" content="gallery">
<meta property="twitter:site" content="@fodorstravel">
<meta property="twitter:creator" content="@fodorstravel">
<meta property="twitter:title" content="America&#039;s Best Small Towns">
<meta property="twitter:description" content="For the second year in a row, we&#039;ve compiled a list that highlights some of the best places in the country you don&#039;t hear about every day.">
<meta property="twitter:url" content="http://www.fodors.com/news/photos/americas-best-small-towns">
<meta property="twitter:image0" content="http://www.fodors.com/ee/files/slideshows/telluride-resized.jpg">
<meta property="twitter:image1" content="http://www.fodors.com/ee/files/slideshows/shutterstock_18216130-resized.jpg">
<meta property="twitter:image2" content="http://www.fodors.com/ee/files/slideshows/3-marfa-texas.jpg">
<meta property="twitter:image3" content="http://www.fodors.com/ee/files/slideshows/4-paia-maui-hawaii.jpg">
```


## Add from array

Excamples of [Twitter App Card](https://dev.twitter.com/cards/types/app)

```php
<?php
Heads::twitter(array(
    'card' => 'app',
    'site' => '@TwitterDev',
    'description' => 'Cannonball is the fun way to create and share stories and poems on your phone. Start with a beautiful image from the gallery, then choose words to complete the story and share it with friends.',
    'app:country' => 'US',
    'app:name:iphone' => 'Cannonball',
    'app:id:iphone' => '929750075',
    'app:url:iphone' => 'cannonball://poem/5149e249222f9e600a7540ef',
    'app:name:ipad' => 'Cannonball',
    'app:id:ipad' => '929750075',
    'app:url:ipad' => 'cannonball://poem/5149e249222f9e600a7540ef',
    'app:name:googleplay' => 'Cannonball',
    'app:id:googleplay' => 'io.fabric.samples.cannonball',
    'app:url:googleplay' => 'http://cannonball.fabric.io/poem/5149e249222f9e600a7540ef',
));

Heads::print();
```

To

```html
<meta property="twitter:card" content="app">
<meta property="twitter:site" content="@TwitterDev">
<meta property="twitter:description" content="Cannonball is the fun way to create and share stories and poems on your phone. Start with a beautiful image from the gallery, then choose words to complete the story and share it with friends.">
<meta property="twitter:app:country" content="US">
<meta property="twitter:app:name:iphone" content="Cannonball">
<meta property="twitter:app:id:iphone" content="929750075">
<meta property="twitter:app:url:iphone" content="cannonball://poem/5149e249222f9e600a7540ef">
<meta property="twitter:app:name:ipad" content="Cannonball">
<meta property="twitter:app:id:ipad" content="929750075">
<meta property="twitter:app:url:ipad" content="cannonball://poem/5149e249222f9e600a7540ef">
<meta property="twitter:app:name:googleplay" content="Cannonball">
<meta property="twitter:app:id:googleplay" content="io.fabric.samples.cannonball">
<meta property="twitter:app:url:googleplay" content="http://cannonball.fabric.io/poem/5149e249222f9e600a7540ef">
```


## Other functions

More attributes

```php
<?php
Heads::add('css', 'app.css', array(
    'id' => 'app-css',
    'title' => 'Main CSS',
));
/*
<link id="app-css" title="Main CSS" rel="stylesheet" href="app.css">
*/
```

Return to HTML

```php
<?php
Heads::add('description', 'My Site description.');
$html = Heads::html();
```

Remove item

```php
<?php
Heads::add('charset', 'utf-8');
Heads::add('description', 'My Site description.');
Heads::remove('description');
/*
<meta charset="utf-8">
*/
```

Clear all item

```php
<?php
Heads::clear();
```

Without auto detect adding

```php
<?php
Heads::meta('charset', 'utf-8');         // <meta name= ...>
Heads::http('refresh', 'example.html');  // <meta http-equiv= ...>
Heads::link('favicon', 'favicon.ico');   // <link ...>
Heads::og('title', 'Content Title');     // <meta property="og: ...>
Heads::fb('app_id', '123456');           // <meta property="fb: ...>
Heads::twitter('card', 'website');       // <meta property="twitter: ...>
Heads::al('ios:url', 'applinks://docs'); // <meta property="al: ...>
```

Get Namespace (HTML5)

```php
<?php
Heads::add('og:title', 'Content Title');
Heads::add('music:album', 'Music Album');
Heads::add('video:actor', 'Video Actor');
$ns = Heads::namespace();
/*
$ns = array (
  'og' => 'og: http://ogp.me/ns#',
  'music' => 'music: http://ogp.me/ns/music#',
  'video' => 'video: http://ogp.me/ns/video#',
)
*/
```

Print Namespace to `prefix`

```php
<?php
Heads::add('og:title', 'Content Title');
echo '<html '.Heads::namespace().'>';
/*
<html prefix="og: http://ogp.me/ns#">
*/
```

_This software is released under the MIT License. See [License File](LICENSE.md) for more information._
