# Laravel Transformers

[![Build Status](https://travis-ci.org/Apiness/laravel-transformers.svg?branch=master)](https://travis-ci.org/Apiness/laravel-transformers)

This package is a presentation layer for transforming data output when building an API.

## Requirements

- PHP 5.6+
- Laravel 5.2+


## Installation

Pull this package in through Composer, by updating the `composer.json` as follows:

```
{
	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/Apiness/laravel-transformers"
		}
	],
	"require": {
        "apiness/laravel-transformers": "~1.0"
    }
}
```

## Usage

Override the abstract `Transformer` class to create a custom transformer on your entity:

```php
use Apiness\Transformers\Transformer;
use Illuminate\Database\Eloquent\Model;

class ModelTransformer extends Transformer {

	protected function transformModel(Model $model)
	{
		return [
			'title'     => $model->title,
			'is_active' => $model->is_active
		];
	}
}
```

For transforming the model, you need to call the `process` method:

```php
$transformer = new ModelTransformer(
	[
		'nested_model' => new NestedModelTransformer()
	]
);

$result = $transformer->process($this->mockedModel);
```

##License

This library is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).




