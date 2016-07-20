<?php namespace Apiness\Transformers\Tests;


use Apiness\Transformers\Tests\Stubs\ModelStub;
use Apiness\Transformers\Tests\Stubs\NestedModelStub;
use Apiness\Transformers\Tests\Stubs\ModelTransformer;
use Apiness\Transformers\Exceptions\TypeErrorException;
use Apiness\Transformers\Tests\Stubs\NestedModelTransformer;
use Apiness\Transformers\Exceptions\NoRelationForTransformerException;
use Mockery;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TransformerTest extends \PHPUnit_Framework_TestCase {


	protected $mockedModel;
	protected $nestedModel;

	protected $collections;

	public function setUp()
	{
		$model = new ModelStub(['name' => 'my_name', 'title' => 'my_title', 'is_active' => true, 'my_date' => Carbon::now()]);
		$modelSecond = new ModelStub(['name' => 'your_name', 'title' => 'your_title', 'is_active' => true, 'your_date' => Carbon::now()]);

		$this->nestedModel = new NestedModelStub([ 'history' => 'my_history', 'date' => Carbon::now() ]);
		$nestedModelSecond = new NestedModelStub([ 'history' => 'your_history', 'date' => Carbon::now() ]);

		$this->mockedModel = Mockery::mock($model);
		$this->mockedModel->shouldReceive('getRelations')->once()->andReturn([
			'nested_model' => $this->nestedModel,
		]);

		$nestedMockedModelSecond = Mockery::mock($modelSecond);
		$nestedMockedModelSecond->shouldReceive('getRelations')->once()->andReturn([
			'nested_model' => $nestedModelSecond,
		]);

		$this->collections = new Collection([ $this->mockedModel,  $nestedMockedModelSecond ]);

	}

	/** @test */
	public function it_test_a_transformation_without_nested_transformers()
	{
		$transformer = new ModelTransformer();
		$result = $transformer->process($this->mockedModel);

		$this->assertCount(2, $result);
		$this->assertTrue($result['is_active']);
		$this->assertEquals('my_title', $result['title']);
	}

	/** @test */
	public function it_test_a_transformation_with_a_nested_transformers()
	{
		$transformer = new ModelTransformer(
			[
				'nested_model' => new NestedModelTransformer()
			]
		);

		$result = $transformer->process($this->mockedModel);


		$this->assertCount(3, $result);
		$this->assertTrue($result['is_active']);
		$this->assertEquals('my_title', $result['title']);
		$this->assertTrue(array_key_exists('nested_model', $result));

		$this->assertTrue(array_key_exists('history', $result['nested_model']));
	}

	/** @test */
	public function it_test_a_transformation_of_a_collection_without_a_nested_transformers()
	{
		$transformer = new ModelTransformer();

		$result = $transformer->process($this->collections);

		$this->assertCount(2, $result);

		$result1 = $result[0];
		$this->assertCount(2, $result1);

		$this->assertTrue($result1['is_active']);
		$this->assertEquals('my_title', $result1['title']);

		$result2 = $result[1];
		$this->assertCount(2, $result2);

		$this->assertTrue($result2['is_active']);
		$this->assertEquals('your_title', $result2['title']);
	}

	/** @test */
	public function it_test_a_transformation_of_a_collection_with_a_nested_transformers()
	{
		$transformer = new ModelTransformer(
			[
				'nested_model' => new NestedModelTransformer()
			]
		);

		$result = $transformer->process($this->collections);

		$this->assertCount(2, $result);

		$result1 = $result[0];
		$this->assertCount(3, $result1);

		$this->assertTrue($result1['is_active']);
		$this->assertEquals('my_title', $result1['title']);
		$this->assertTrue(array_key_exists('nested_model', $result1));

		$this->assertTrue(array_key_exists('history', $result1['nested_model']));

		$result2 = $result[1];
		$this->assertCount(3, $result2);

		$this->assertTrue($result2['is_active']);
		$this->assertEquals('your_title', $result2['title']);
		$this->assertTrue(array_key_exists('nested_model', $result2));

		$this->assertTrue(array_key_exists('history', $result2['nested_model']));
	}

	/** @test **/
	public function it_throw_an_exception_if_the_nested_transformer_not_match_with_any_relation()
	{
		$transformer = new ModelTransformer(
			[
				'nested' => new NestedModelTransformer()
			]
		);

		$this->expectException(NoRelationForTransformerException::class);
		$transformer->process($this->collections);
	}

	/** @test **/
	public function it_throw_an_exception_if_the_data_given_is_not_a_model_or_a_collection()
	{
		$transformer = new ModelTransformer(
			[
				'nested' => new NestedModelTransformer()
			]
		);

		$this->expectException(TypeErrorException::class);
		$transformer->process([]);
	}
}
