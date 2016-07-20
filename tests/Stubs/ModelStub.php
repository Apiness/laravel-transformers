<?php namespace Apiness\Transformers\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;

class ModelStub extends Model {

	protected $fillable = [ 'name', 'title', 'is_active', 'date' ];

	public function nestedModels()
	{
		return $this->hasMany(NestedModelStub::class);
	}
}
