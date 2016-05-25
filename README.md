# kijtra/heads

HTML `<head>` tag helper for PHP.


## Usage

```sh
composer.phar require kijtra/heads
```

```php
<?php
require '/path/to/autoload.php';
use Kijtra\Heads;
```


## Example1

The PHP

```php
<?php
Heads::add('charset', 'utf-8');
Heads::add('description', 'My Site description.');
Heads::print();
```

To HTML

```html
<meta charset="utf-8">
<meta name="description" content="My Site description.">
```

## Example2

The PHP

```php
<?php
Heads::add('keywords', 'word1, word2');
Heads::add('keywords', 'word3');
Heads::add('keywords', array('word3', 'word4'));
Heads::print();
```

To HTML

```html
<meta name="keywords" content="word1,word2,word3,word4">
```

## Example3

The PHP

```php
<?php
Heads::add('refresh', 'example.html', 5);
Heads::add('expires', '+10 hours');
Heads::print();
```

To HTML

```html
<meta http-equiv="Refresh" content="5; URL=example.html">
<meta http-equiv="Expires" content="Thu, 26 May 2016 00:28:47 +0000">
```

## Example4

The PHP

```php
<?php
Heads::add('prev', 'prev.html');
Heads::add('next', 'next.html');
Heads::print();
```

To HTML

```html
<link rel="prev" href="prev.html">
<link rel="next" href="next.html">
```

## Example5

The PHP

```php
<?php
Heads::add('charset', 'utf-8');
Heads::add('description', 'My Site description.');

Heads::remove('description');
Heads::print();
```

To HTML

```html
<meta charset="utf-8">
```

## Example6

from PHP

```php
<?php
Heads::meta('viewport', 'width=device-width');
Heads::http('set-cookie', 'cookie value');
Heads::link('made', 'mail@example.com');
$html = Heads::html();
echo $html;
```

To HTML

```html
<meta name="viewport" content="width=device-width">
<meta http-equiv="Set-Cookie" content="cookie value">
<link rel="made" href="mail@example.com">
```


_This software is released under the MIT License. See [License File](LICENSE.md) for more information._
