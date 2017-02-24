**Twig object dumper** is a simple Twig extension improving templates debugging : it helps front-end developers to interact the right way with back-end objects.

## Usage

With this code :

```php
class Product
{
    private $stockQuantity;

    public function isInStock() : bool
    {
        return $this->stockQuantity > 0;
    }
}
```

Standard Twig `dump` function displays :

```
Product {#762 ▼
  -stockQuantity: 3
}
```

Which is not a good information : `stockQuantity` is private and not usable.

Whereas the new `d` function displays the right way to access object's properties :

```
WizaTech\Product
    isInStock() : bool
```

## Installation

```
$ composer require wizaplace/twig-object-dump
```

Don't forget to load the new bundle in the `app/AppKernel.php` :

```php
$bundles = [
    // ...
    new Wizaplace\TwigObjectDumpBundle\TwigObjectDumpBundle(),
];
```

This extension checks the twig environment and do nothing if it's set to _debug_.

## Installation for dev

You can use Vagrant to setup a 'ready for dev' environment :

```
$ cp Vagrantfile.dist Vagrantfile
$ vagrant up
$ vagrant ssh
```

Load dependencies with Composer :

```
$ composer install
```

## Coding Style

To ensure that you use the right coding standard, use `coke` :

```
$ vendor/bin/coke
```

## Credits

Developed by [Wizaplace](http://tech.wizaplace.com/).

## License

This project is licensed under the [MIT license](LICENSE).
