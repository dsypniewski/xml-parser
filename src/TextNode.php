<?php

namespace Mouuro\XmlParser;

class TextNode extends Node
{

	/**
	 * TextNode constructor.
	 */
	public function __construct()
	{
		parent::__construct('#text');
	}

	/** @var string */
	protected $text;

	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @param string $text
	 */
	public function setText($text)
	{
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->text;
	}

}
