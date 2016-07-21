<?php namespace Apiness\Transformers;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Transformer {

	protected $nestedTransformers;

	public function __construct($transformers = [])
	{
		$this->nestedTransformers = $transformers;
	}

	public function process($data)
	{
		if (!$data instanceof  Collection && !$data instanceof Model) {
			throw new TypeErrorException('$data must be a collection or a model');
		}

		$result = [];

		if ($data instanceof Collection) {
			$data->each(function($model) use(&$result) {
				$result[] = $this->transform($model);
			});
		}
		else if ($data instanceof Model) {
			$result = array_merge($result, $this->transform($data));
		}

		return $result;
	}

	public function perform($data)
	{
		return $this->process($data);
	}

	private function transform(Model $model)
	{
		$result = [];

		$flatModel = $this->transformModel($model);
		$result = array_merge($result, $flatModel);

		if (!empty($this->nestedTransformers)) {
			$relations = $this->transformRelations($model);
			$result = array_merge($result, $relations);
		}

		return $result;
	}

	protected abstract function transformModel(Model $model);

	protected function transformRelations(Model $model)
	{
		$relations = $model->getRelations();

		$result = [];

		foreach ($this->nestedTransformers as $key => $transformer) {
			if (array_key_exists($key, $relations)) {
				$data = $transformer->process($relations[$key]);
				$result[$key] = $data;
			}
		}

		return $result;
	}
}
