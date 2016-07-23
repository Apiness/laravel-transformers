<?php namespace Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Zaltana\Transformers\Transformable;

class ModelStub extends Model {

	use Transformable;

	protected $fillable = [ 'name', 'title', 'is_active', 'date' ];

	public function nestedModels()
	{
		return $this->hasMany(NestedModelStub::class);
	}

}
