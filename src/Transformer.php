<?php namespace Zaltana\Transformers;

use InvalidArgumentException;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Transformer classes should extend this abstract class to perform transformations on specific entities.
 */
abstract class Transformer {

	protected $nestedTransformers;

	/**
	 * Initialize a new Transformer object.
	 *
	 * @param array $transformers Nested transformers used to transform relationships.
	 */
	public function __construct(array $transformers = [])
	{
		$this->nestedTransformers = $transformers;
	}

	/**
	 * Initialize and return a new Tranformer object with nested transformers used to transform relationships.
	 *
	 * @param array $transformers Nested transformers used to transform relationships.
	 *
	 * @return static A newly initialized transformer object with the given nested transformers.
	 */
	public static function with(array $transformers = [])
	{
		return new static($transformers);
	}

	/**
	 * @param mixed $data The data to be transformed.
	 *
	 * @return array The transformed data.
	 */
	public function process($data)
	{
		// If the data is a collection of items, we need to transform each item
		// Otherwise, we transform the object directly

		if (($collection = $this->normalizeToCollection($data)) instanceof Collection) {
			return $collection->map(function($model) {
				return $this->performTransformation($model);
			})->all();
		}

		return $this->performTransformation($data);
	}

	protected function normalizeToCollection($data)
	{
		if (is_array($data)) {
			return new Collection($data);
		}
		else if ($data instanceof Paginator) {
			return new Collection($data->items());
		}

		return $data;
	}

	protected function performTransformation($model)
	{
		$result = $this->transform($model);

		if (!empty($this->nestedTransformers)) {
			$result = array_merge($result, $this->transformRelations($model));
		}

		return $result;
	}

	protected abstract function transform($model);

	protected function transformRelations($model)
	{
		if (!$model instanceof Model) {
			throw new InvalidArgumentException("Nested transformations can only be applied on ".Model::class." subclasses, not on ".get_class($model)."!");
		}

		$result = [];

		$relations = $model->getRelations();

		foreach ($this->nestedTransformers as $relation => $transformer) {
			if (array_key_exists($relation, $relations)) {
				$result[ $relation ] = $transformer->process($relations[ $relation ]);
			}
		}

		return $result;
	}
}
