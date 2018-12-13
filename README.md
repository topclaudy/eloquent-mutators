Eloquent Mutators
==================

**Eloquent Mutators** allows us to define accessors and mutators outside of an Eloquent model. This gives us the ability to organize and reuse them on any model or any attribute of the same model.

## The problem

Eloquent has support for [accessors and mutators](https://laravel.com/docs/5.7/eloquent-mutators). However, it requires us to define them directly in the model. What if we want to reuse an accessor/mutator logic in another model? Or, what if we want to reuse an accessor/mutator logic for another attribute of the same model? We can't! **Eloquent Mutators** aims at solving this limitation.

#### Related discussions:

* [Reusing accessors & mutators](https://stackoverflow.com/questions/37725691/reusing-an-accessors-mutators/37727418#37727418)
* [Simple and organized accessors & mutators](https://github.com/laravel/ideas/issues/1270)

## Installation

The recommended way to install **Eloquent Mutators** is through [Composer](http://getcomposer.org/)

```bash
$ composer require awobaz/eloquent-mutators
```

The package will automatically register itself if you're using Laravel 5.5+. For Laravel 5.4, you'll have to register the package manually:

1) Open your `config/app.php` and add the following to the `providers` array:

```php
Awobaz\Mutator\MutatorServiceProvider::class,
```

2) In the same `config/app.php` add the following to the `aliases ` array: 

```php
'Mutator'   => Awobaz\Mutator\Facades\Mutator::class,
```

> **Note:** **Eloquent Mutators** requires Laravel 5.4+.

After installation, publish the assets using the `mutators:install` Artisan command. The primary configuration file will be located at `config/mutators.php`. The installation also publishes and registers the `app/Providers/MutatorServiceProvider.php`. Within this service provider, you may register your custom mutators.

```sh
php artisan mutators:install
```

## Usage

### Using the `Awobaz\Mutator\Database\Eloquent\Model` class

Simply make your model class derive from the `Awobaz\Mutator\Database\Eloquent\Model` base class. The `Awobaz\Mutator\Database\Eloquent\Model` extends the `Eloquent` base class without changing its core functionality.

### Using the `Awobaz\Mutator\Mutable` trait

If for some reasons you can't derive your models from `Awobaz\Mutator\Database\Eloquent\Model`, you may take advantage of the `Awobaz\Mutator\Mutable` trait. Simply use the trait in your models.

### Syntax

After configuring your model, you may configure accessors and mutators for its attributes.

#### Defining accessors

For the following Post model, we configure accessors to trim whitespace from the beginning and end of the `title` and `content` attributes:

```php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use \Awobaz\Mutator\Mutable;
    
    protected $accessors = [
        'title'   => 'trim_whitespace',
        'content' => 'trim_whitespace',
    ];
}
```

As you can see, we use an array property named `accessors` on the model to configure its **accessors**. Each key of the array represents the name of an attribute, and the value points to one or multiple accessors. To apply multiple accessors, pass an array as value (the accessors will be applied in the order they are specified):

```php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use \Awobaz\Mutator\Mutable;
    
    protected $accessors = [
        'title'   => ['trim_whitespace', 'capitalize'], 
        'content' => ['trim_whitespace', 'remove_extra_whitespace'],
    ];
}
```

#### Defining mutators

To define mutators, use an array property named `mutators` instead.

```php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use \Awobaz\Mutator\Mutable;
    
    protected $mutators = [
        'title'    => 'remove_extra_whitespace',
    ];
}
```

> **Note:** The name of the properties used for accessors and mutators can be respectively configured in the `config/mutators.php` configuration file.

#### Defining accessors/mutators extensions

In the previous examples, we use [accessors/mutators provided](#built-in-accessors-mutators) by the package. You may also register accessors/mutators extensions using the **extend** method of the `Mutator` facade. The **extend** method accepts the name of the accessor/mutator and a closure.

```
<?php

namespace App\Providers;

use Awobaz\Mutator\Facades\Mutator;
use Illuminate\Support\ServiceProvider;

class MutatorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Register your custom accessors/mutators extensions here.
        Mutator::extend('extension_name', function($model, $value, $key){
            //DO STUFF HERE AND RETURN THE VALUE
        });
    }
}
```
As you can see, the model ($model), the attribute's value ($value) and the attribute's name ($key) are passed to the closure, allowing you to access other attributes of the model to compute and return the desired value. 

## Built-in accessors/mutators

- [`lower_case`](#lower_case)
- [`upper_case`](#upper_case)
- [`capitalize`](#capitalize)
- [`capitalize_words`](#capitalize_words)
- [`trim_whitespace`](#trim_whitespace)
- [`camel_case`](#camel_case)
- [`snake_case`](#snake_case)
- [`kebab_case`](#kebab_case)
- [`studly_case`](#studly_case)
- [`title_case`](#title_case)
- [`plural`](#plural)
- [`singular`](#singular)
- [`slug`](#slug)
- [`remove_extra_whitespace`](#remove_extra_whitespace)

### `lower_case`
Convert the attribute to lower case.

### `upper_case`
Convert the attribute to upper case.

### `capitalize`
Convert the first character of attribute to upper case.

### `capitalize_words`
Convert the first character of each word of the attribute to upper case.

### `trim_whitespace`
Strip whitespace from the beginning and end of the attribute.

### `camel_case`
Convert the attribute to camel case.

### `snake_case`
Convert the attribute to snake case.

### `studly_case`
Convert the attribute to studly case.

### `kebab_case`
Convert the attribute to kebab case.

### `title_case`
Convert the attribute to title case.

### `plural`
Convert the attribute to its plural form (only supports the English language).

### `singular`
Convert the attribute to its singular form (only supports the English language).

### `slug`
Convert the attribute to its URL friendly "slug" form.

### `remove_extra_whitespace`
Remove extra whitespaces within the attribute.

## Contributing

Please read [CONTRIBUTING.md](https://github.com/topclaudy/eloquent-mutators/blob/master/CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/topclaudy/eloquent-mutators/tags).

## Unit Tests

In order to run the test suite, install the development dependencies:

```bash
$ composer install --dev
```

Then, run the following command:

```bash
$ vendor/bin/phpunit
```

## Authors

* [Claudin J. Daniel](https://github.com/topclaudy) - *Initial work*

## Sponsored by

* [Awobaz](https://awobaz.com) - Web/Mobile agency based in Montreal, Canada

## License

**Eloquent Mutators** is licensed under the [MIT License](http://opensource.org/licenses/MIT).