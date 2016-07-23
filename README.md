# Zaltana Transformers

[![Build Status](https://travis-ci.org/marfurt/zaltana-transformers.svg?branch=master)](https://travis-ci.org/marfurt/zaltana-transformers)

> **Note:** This package is part of the _Zaltana components_, a serie of small packages made to provide useful features to Laravel projects.

This package provides a presentation layer for transforming data output when building an API.


## Requirements

- PHP 5.6+
- Laravel 5.2+


## Installation

Pull this package in through [Composer](https://getcomposer.org), by updating the `composer.json` file as follows:

```json
{
	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/marfurt/zaltana-transformers"
		}
	],
	"require": {
        "zaltana/transformers": "~1.0"
    }
}
```


## Usage

Override the abstract `Transformer` class to create a custom transformer for your entity:

```php
use Zaltana\Transformers\Transformer;

class MyModelTransformer extends Transformer {

	protected function transform($model)
	{
		return [
			'title'     => $model->title,
			'is_active' => $model->is_active
		];
	}
}
```

For transforming a model object, you need to instantiate your transformer and to call the `process` method on it.
If you want to transform your model relationships as well, you need to inject their corresponding transformers into your model transformer.

```php
$transformer = new MyModelTransformer([
	'myRelationship' => new RelationshipTransformer()
]);

$data = $transformer->process($myModelObject);
```

You can also transform a collection of objects instead of a single object.

```php
// Transforming array of objects
$objectsInArray = [ $myModelObject ];
$data = $transformer->process($objectsInArray);

// Transforming a Laravel Collection of objects
$objectsInCollection = collect([ $myModelObject ]);
$data = $transformer->process($objectsInCollection);
```

### Making Models Transformable

You can also use the `Transformable` trait on your model.

```php
use Zaltana\Transformers\Transformable;

class MyModel extends Model {

	use Transformable;

}
```

Then you can transform you objects as follows:

```php
$transformer = new MyModelTransformer();

$data = $myModelObject->transform($transformer);
```

If you only use one transformer on your model, you can define a transformer via the `$transformer` property.

```php
$myModelObject->transformer = new MyModelTransformer();

$data = $myModelObject->transform();
```

If you don't define any transformer on your model and call `transform()`, it will dynamically look for a default transformer named `ModelClassNameTransformer` in the same namespace.
If no transformer is found, a TransformerException exception is thrown.

```php
$object = new MyModel();

$data = $object->transform(); // Will try to use MyModelTransformer
```


##License

This library is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
