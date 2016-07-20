<?php namespace Apiness\Transformers\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Apiness\Transformers\BaseTransformer;

class NestedModelTransformer extends BaseTransformer {

	protected function transformModel(Model $model)
	{
		return [
			'history' => $model->history
		];
	}
}
