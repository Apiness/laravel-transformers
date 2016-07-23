<?php namespace Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Zaltana\Transformers\Transformable;

class NestedModelStub extends Model {

	use Transformable;

	protected $fillable = [ 'history', 'date' ];

	public function modelStub()
	{
		return $this->belongsTo(ModelStub::class);
	}

}
