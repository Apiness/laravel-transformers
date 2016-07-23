<?php namespace Tests\Stubs;

use Zaltana\Transformers\Transformer;

class ModelTransformer extends Transformer {

	protected function transform($model)
	{
		return [
			'title'     => $model->title,
			'is_active' => $model->is_active
		];
	}

}

class ModelStubTransformer extends ModelTransformer {}
