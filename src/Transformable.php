<?php namespace Zaltana\Transformers;

/**
 * A transformable object can be transformed using a specific Transformer instance.
 */
trait Transformable {

	public function transform(Transformer $transformer = null)
	{
		if (!$transformer) {
			$transformer = $this->getDefaultTransformer();
		}

		return $transformer->process($this);
	}

	public function getDefaultTransformer()
	{
		$transformer  = null;

		if (isset($this->transformer)) {
			$transformer = $this->transformer;
		}
		else {
			$class = $this->transformerClass();

			if(!class_exists($class)) {
				throw new TransformerException('Unknown transformer class! You have to set a default transformer class to the $transformer property, or create a ' . $this->transformerClass() . ' class.');
			}

			$transformer = new $class();
		}

		if (! $transformer instanceof Transformer) {
			throw new TransformerException('Default transformer should be an instance of Transformer class, instead of '.get_class($transformer).'.');
		}

		return $transformer;
	}

	public function transformerClass()
	{
		return static::class.'Transformer';
	}

}
