<?php namespace Tests;

use InvalidArgumentException;
use Tests\Stubs\ModelTransformer;
use Tests\Stubs\EmptyTransformer;
use Tests\Stubs\NestedModelTransformer;

class TransformerTest extends TestCase {

	/** @test */
	public function it_performs_a_transformation_without_nested_transformers()
	{
		$model = $this->models->first();
		$transformer = new ModelTransformer();

		$result = $transformer->process($model);

		$this->assertCount(2, $result);
		$this->assertTrue($result[ 'is_active' ]);
		$this->assertEquals($model->title, $result[ 'title' ]);
	}

	/** @test */
	public function it_performs_a_transformation_with_nested_transformers()
	{
		$model = $this->models->first();
		$transformer = new ModelTransformer([
			'nested_model' => new NestedModelTransformer()
		]);

		$result = $transformer->process($model);

		$this->assertCount(3, $result);
		$this->assertTrue($result[ 'is_active' ]);
		$this->assertEquals($model->title, $result[ 'title' ]);
		$this->assertTrue(array_key_exists('nested_model', $result));
		$this->assertTrue(array_key_exists('history', $result[ 'nested_model' ]));
	}

	/** @test */
	public function it_creates_a_transformer_with_nested_transformers()
	{
		$model = $this->models->first();

		$transformer1 = new ModelTransformer([ 'nested_model' => new NestedModelTransformer() ]);
		$transformer2 = ModelTransformer::with([ 'nested_model' => new NestedModelTransformer() ]);

		$this->assertEquals($transformer1->process($model), $transformer2->process($model));
	}

	/** @test */
	public function it_transforms_a_collection_without_nested_transformers()
	{
		$transformer = new ModelTransformer();

		$result = $transformer->process($this->models);

		$this->assertCount(2, $result);

		$this->assertCount(2, $result[0]);
		$this->assertTrue($result[0][ 'is_active' ]);
		$this->assertEquals('my_title', $result[0][ 'title' ]);

		$this->assertCount(2, $result[1]);
		$this->assertTrue($result[1][ 'is_active' ]);
		$this->assertEquals('your_title', $result[1][ 'title' ]);
	}

	/** @test */
	public function it_transforms_a_collection_with_nested_transformers()
	{
		$transformer = new ModelTransformer([
			'nested_model' => new NestedModelTransformer()
		]);

		$result = $transformer->process($this->models);

		$this->assertCount(2, $result);

		$this->assertCount(3, $result[0]);
		$this->assertTrue($result[0][ 'is_active' ]);
		$this->assertEquals('my_title', $result[0][ 'title' ]);
		$this->assertTrue(array_key_exists('nested_model', $result[0]));
		$this->assertTrue(array_key_exists('history', $result[0][ 'nested_model' ]));

		$this->assertCount(3, $result[1]);
		$this->assertTrue($result[1][ 'is_active' ]);
		$this->assertEquals('your_title', $result[1][ 'title' ]);
		$this->assertTrue(array_key_exists('nested_model', $result[1]));
		$this->assertTrue(array_key_exists('history', $result[1][ 'nested_model' ]));
	}

	/** @test **/
	public function nested_transformations_are_only_available_on_laravel_model_objects()
	{
		$nonModelObject = new \stdClass();

		$transformer = new EmptyTransformer([
			'nested_model' => new NestedModelTransformer()
		]);

		$this->expectException(InvalidArgumentException::class);

		$transformer->process($nonModelObject);
	}
}
