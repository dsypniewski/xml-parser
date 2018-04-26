<?php

namespace Mouuro\XmlParser;

class NodeList implements \IteratorAggregate, \Countable
{

	protected $nodes = [];

	/**
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->nodes);
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->nodes);
	}

	/**
	 * @param Node $node
	 * @return void
	 */
	public function append(Node $node)
	{
		$this->nodes[] = $node;
	}

	/**
	 * @param Node $node
	 * @return void
	 */
	public function prepend(Node $node)
	{
		array_unshift($this->nodes, $node);
	}

	/**
	 * @return Node|null
	 */
	public function getFirst()
	{
		if ($this->count() === 0) {
			return null;
		}

		return $this->nodes[0];
	}

	/**
	 * @return Node|null
	 */
	public function getLast()
	{
		if ($this->count() === 0) {
			return null;
		}

		return $this->nodes[$this->count() - 1];
	}

	/**
	 * @param Node $node
	 * @return void
	 */
	public function remove(Node $node)
	{
		foreach ($this->nodes as $key => $value) {
			if ($value === $node) {
				unset($this->nodes[$key]);
			}
		}
		$this->nodes = array_values($this->nodes);
	}

	/**
	 * @return Node[]
	 */
	public function toArray()
	{
		return $this->nodes;
	}

}
