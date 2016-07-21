<?php namespace Apiness\Transformers\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;

class NestedModelStub extends Model {

	protected $fillable = [ 'history', 'date' ];

	public function modelStub()
	{
		return $this->belongsTo(ModelStub::class);
	}

}
