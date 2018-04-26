<?php

namespace Mouuro\XmlParser;

class Parser
{

	/** @var resource */
	protected $parser;

	/** @var Node|null */
	protected $rootNode = null;

	/** @var Node|null */
	protected $currentNode = null;

	/** @var string */
	protected $defaultNodeClass = Node::class;

	/** @var string[] */
	protected $nodeClasses = [];

	/**
	 * Parser constructor.
	 * @param string $charset
	 */
	public function __construct($charset = 'UTF-8')
	{
		$this->parser = xml_parser_create($charset);
		xml_set_element_handler($this->parser, $this->startTag(), $this->endTag());
		xml_set_character_data_handler($this->parser, $this->data());

		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 0);
		xml_parser_set_option($this->parser, XML_OPTION_TARGET_ENCODING, $charset);
	}
	
	public function __destruct()
	{
		xml_parser_free($this->parser);
	}

	/**
	 * @param string|null $className
	 * @throws \Exception
	 */
	public function setDefaultNodeClass($className)
	{
		if ($className !== null and !is_subclass_of($className, Node::class)) {
			throw new \Exception('Class ' . $className . ' does not extend the base Node class');
		}
		$this->defaultNodeClass = $className;
	}

	/**
	 * @param string[] $names
	 * @param string $className
	 * @throws \Exception
	 */
	public function registerNodeClass(array $names, $className)
	{
		if (!is_a($className, Node::class, true)) {
			throw new \Exception('Class ' . $className . ' does not extend the base Node class');
		}

		foreach ($names as $name) {
			$this->nodeClasses[$name] = $className;
		}
	}

	/**
	 * @param string $data
	 * @return Node
	 * @throws \Exception
	 */
	public function parse($data)
	{
		$this->rootNode = null;
		$this->currentNode = null;
		xml_parse($this->parser, $data, true);

		if (!($this->rootNode instanceof Node)) {
			throw new \Exception('Invalid data');
		}

		return $this->rootNode;
	}

	/**
	 * @return \Closure
	 */
	protected function startTag()
	{
		return function ($parser, $name, $attributes) {
			if (array_key_exists($name, $this->nodeClasses)) {
				$node = new $this->nodeClasses[$name]($name);
			} else if ($this->defaultNodeClass !== null) {
				$node = new $this->defaultNodeClass($name);
			} else {
				throw new \Exception('Unknown tag ' . $name);
			}

			/** @var Node $node */
			$node->setAttributes(AttributeList::fromArray($attributes));
			if ($this->rootNode === null) {
				$this->rootNode = $node;
			} else {
				$this->currentNode->addChild($node);
			}
			$this->currentNode = $node;
		};
	}

	/**
	 * @return \Closure
	 */
	protected function endTag()
	{
		return function ($parser, $name) {
			if ($this->currentNode->getName() !== $name) {
				throw new \Exception('Invalid closing tag, current node is ' . $this->currentNode->getName() . ' and closing tag is ' . $name);
			}
			$this->currentNode->validate();
			$this->currentNode->cleanup();
			$this->currentNode = $this->currentNode->getParent();
		};
	}

	/**
	 * @return \Closure
	 */
	protected function data()
	{
		return function ($parser, $data) {
			$node = new TextNode();
			$node->setText($data);
			$this->currentNode->addChild($node);
		};
	}

}
