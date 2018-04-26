<?php

namespace Mouuro\XmlParser;

class Node
{

	/** @var string */
	protected $name;

	/** @var NodeList */
	protected $children;

	/** @var AttributeList */
	protected $attributes;

	/** @var Node|null */
	protected $parent = null;

	/**
	 * Node constructor.
	 * @param string $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
		$this->children = new NodeList();
		$this->attributes = new AttributeList();
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return AttributeList
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * @param AttributeList $attributes
	 */
	public function setAttributes(AttributeList $attributes)
	{
		$this->attributes = $attributes;
	}

	/**
	 * @return NodeList
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * @param NodeList $children
	 */
	public function setChildren(NodeList $children)
	{
		/** @var Node $child */
		foreach($children as $child) {
			$child->setParent($this);
		}
		$this->children = $children;
	}

	/**
	 * @param Node $child
	 */
	public function addChild(Node $child)
	{
		$child->setParent($this);
		$this->children->append($child);
	}

	/**
	 * @return bool
	 */
	public function hasParent()
	{
		return ($this->parent instanceof Node);
	}

	/**
	 * @return Node|null
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @param Node $parent
	 * @return void
	 */
	public function setParent(Node $parent)
	{
		$this->parent = $parent;
	}

	/**
	 * @return void
	 */
	public function clearParent()
	{
		$this->parent = null;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return array_reduce($this->getChildren()->toArray(), function ($carry, Node $node) { 
			return $carry . $node->__toString(); 
		}, '');
	}

	/**
	 * @return void
	 * @throws \Exception
	 */
	public function validate()
	{
	}

	/**
	 * @return void
	 */
	public function cleanup()
	{
	}

	/**
	 * @return void
	 */
	protected function destroy()
	{
		if (!$this->hasParent()) {
			return;
		}
		
		$this->getParent()->getChildren()->remove($this);
	}

}
