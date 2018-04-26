<?php

namespace Mouuro\XmlParser;

class AttributeList implements \IteratorAggregate, \Countable
{

	/** @var Attribute[] */
	protected $attributes = [];

	/**
	 * @param string[] $attributes
	 * @return self
	 */
	public static function fromArray(array $attributes)
	{
		$list = new self;
		foreach ($attributes as $name => $value) {
			$list->append(Attribute::create($name, $value));
		}

		return $list;
	}

	/**
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->attributes);
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->attributes);
	}

	/**
	 * @param Attribute $node
	 * @return void
	 */
	public function append(Attribute $node)
	{
		$this->attributes[] = $node;
	}

	/**
	 * @param Attribute $node
	 * @return void
	 */
	public function prepend(Attribute $node)
	{
		array_unshift($this->attributes, $node);
	}

	/**
	 * @return string[]
	 */
	public function toArray()
	{
		$array = [];
		foreach ($this->attributes as $attribute) {
			$array[$attribute->getName()] = $attribute->getValue();
		}

		return $array;
	}

	/**
	 * @return string
	 */
	public function toJson()
	{
		return json_encode($this->toArray());
	}

}
