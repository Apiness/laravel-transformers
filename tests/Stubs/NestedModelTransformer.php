<?php namespace Apiness\Transformers\Tests\Stubs;

use Apiness\Transformers\Transformer;
use Illuminate\Database\Eloquent\Model;

class NestedModelTransformer extends Transformer {

	protected function transformModel(Model $model)
	{
		return [
			'history' => $model->history
		];
	}

}
