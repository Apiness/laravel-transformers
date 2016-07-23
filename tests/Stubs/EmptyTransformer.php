<?php namespace Tests\Stubs;

use Zaltana\Transformers\Transformer;

class EmptyTransformer extends Transformer {

	protected function transform($model)
	{
		return [];
	}

}
