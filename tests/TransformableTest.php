<?php namespace Tests;

use InvalidArgumentException;
use Tests\Stubs\ModelTransformer;
use Tests\Stubs\EmptyTransformer;
use Tests\Stubs\NestedModelTransformer;

class TransformableTest extends TestCase {

	/** @test */
	public function it_transforms_a_tranformable_model()
	{
		$model = $this->models->first();

		$result = $model->transform(new ModelTransformer());

		$this->assertCount(2, $result);
		$this->assertTrue($result[ 'is_active' ]);
		$this->assertEquals($model->title, $result[ 'title' ]);
	}

	/** @test */
	public function it_transforms_a_tranformable_model_using_property_transformer()
	{
		$model = $this->models->first();

		$model->transformer = new ModelTransformer();

		$result = $model->transform(/* No provided transformer, use property one */);

		$this->assertCount(2, $result);
		$this->assertTrue($result[ 'is_active' ]);
		$this->assertEquals($model->title, $result[ 'title' ]);
	}

	/** @test */
	public function it_transforms_a_tranformable_model_using_default_transformer()
	{
		$model = $this->models->first();

		$result = $model->transform(/* No provided transformer, use default one */);

		$this->assertCount(2, $result);
		$this->assertTrue($result[ 'is_active' ]);
		$this->assertEquals($model->title, $result[ 'title' ]);
	}
}
