<?php namespace Apiness\Transformers;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Apiness\Transformers\Exceptions\TypeErrorException;

abstract class BaseTransformer {

	protected $nestedTransformers;

	public function __construct($nestedTransformer = [])
	{
		$this->nestedTransformers = $nestedTransformer;
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

		foreach ($this->nestedTransformers as $key => $nestedTransformer) {
			if (array_key_exists($key, $relations)) {
				$data = $nestedTransformer->process($relations[$key]);
				$result[$key] = $data;
			}
		}

		return $result;
	}
}
