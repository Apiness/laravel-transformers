<?php namespace Apiness\Transformers\Tests\Stubs;

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
