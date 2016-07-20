<?php namespace Apiness\Transformers\Tests\Stubs;

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
