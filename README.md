# Transformer

[![Build Status](https://travis-ci.org/Apiness/laravel-transformers.svg?branch=master)](https://travis-ci.org/Apiness/laravel-transformers)

Transformer is a presentation layer for transforming data output when building an API.

## Installation

In your file `composer.json`, add the repository and the requirement

```
{
	"repository": [
		"type": "vsc",
		"url": "https://github.com/Apiness/laravel-transformers"
	],
	"require": {
        "apiness/laravel-transformers": "dev-master"
    }
}
```

## How to use

Override the Base transformer like in the following example:

```
use Apiness\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Model;

class ModelTransformer extends BaseTransformer {

	protected function transformModel(Model $model)
	{
		return [
			'title' => $model->title,
			'is_active' => $model->is_active
		];
	}
}
```

For transforming the model you can use the `process` method:

```
$transformer = new ModelTransformer(
	[
		'nested_model' => new NestedModelTransformer()
	]
);

$result = $transformer->process($this->mockedModel);
```

##License

This library is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).




