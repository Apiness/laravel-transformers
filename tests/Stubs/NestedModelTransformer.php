<?php namespace Tests\Stubs;

use Zaltana\Transformers\Transformer;

class NestedModelTransformer extends Transformer {

	protected function transform($model)
	{
		return [
			'history' => $model->history
		];
	}

}
