<?php

namespace Mouuro\XmlParser;

class Attribute
{

	/** @var string */
	protected $name;

	/** @var string */
	protected $value;

	/**
	 * @param string $name
	 * @param string $value
	 * @return Attribute
	 */
	public static function create($name, $value)
	{
		$attribute = new self();
		$attribute->setName($name);
		$attribute->setValue($value);

		return $attribute;
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
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param string $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

}
