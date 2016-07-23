<?php namespace Tests;

use Mockery;
use Carbon\Carbon;
use PHPUnit_Framework_TestCase;
use Illuminate\Support\Collection;
use Tests\Stubs\ModelStub;
use Tests\Stubs\NestedModelStub;

class TestCase extends PHPUnit_Framework_TestCase {

	/**
	 * @var Collection
	 */
	protected $models;

	public function setUp()
	{
		$model = new ModelStub(['name' => 'my_name', 'title' => 'my_title', 'is_active' => true, 'date' => Carbon::now()]);
		$nestedModel = new NestedModelStub([ 'history' => 'my_history', 'date' => Carbon::now() ]);

		$mockedModel = Mockery::mock($model);
		$mockedModel->shouldReceive('getRelations')->once()->andReturn([
			'nested_model' => $nestedModel
		]);

		$otherModel = new ModelStub(['name' => 'your_name', 'title' => 'your_title', 'is_active' => true, 'date' => Carbon::now()]);
		$otherNestedModel = new NestedModelStub([ 'history' => 'your_history', 'date' => Carbon::now() ]);

		$otherMockedModel = Mockery::mock($otherModel);
		$otherMockedModel->shouldReceive('getRelations')->once()->andReturn([
			'nested_model' => $otherNestedModel
		]);

		$this->models = new Collection([ $mockedModel, $otherMockedModel ]);
	}

}
