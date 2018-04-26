<?php

require_once '../vendor/autoload.php';

class PlaceholderNode extends \Mouuro\XmlParser\Node
{

	public function __toString()
	{
		$firstChild = $this->getChildren()->getFirst();
		if ($firstChild instanceof \Mouuro\XmlParser\TextNode) {
			return '{{ ' . $firstChild->getText() . ' }}';
		}

		return '';
	}

	public function validate()
	{
		if ($this->getChildren()->count() > 1) {
			throw new Exception('Placeholder node cannot contain other nodes');
		}

		$node = $this->getChildren()->getFirst();
		if ($node !== null and !($node instanceof \Mouuro\XmlParser\TextNode)) {
			throw new Exception('Placeholder can only contain text');
		}
	}

	public function cleanup()
	{
		if ($this->getChildren()->count() === 0) {
			$this->destroy();
		}
	}

}

class SelfClosingNode extends \Mouuro\XmlParser\Node
{

	public function __toString()
	{
		return '{{ _el_' . $this->getName() . '() }}';
	}

}

class InlineNode extends \Mouuro\XmlParser\Node
{

	public function __toString()
	{
		$attributes = '';
		if ($this->getAttributes()->count() > 0) {
			$attributes = $this->getAttributes()->toJson();
		}

		$result = '{{ _el_' . $this->getName() . '_open(' . $attributes . ') }}';
		foreach ($this->getChildren() as $child) {
			$result .= $child->__toString();
		}
		$result .= '{{ _el_' . $this->getName() . '_close() }}';

		return $result;
	}

}

class BlockNode extends InlineNode
{

	public function cleanup()
	{
		$child = $this->children->getFirst();
		if ($child instanceof \Mouuro\XmlParser\TextNode) {
			$child->setText(ltrim($child->getText()));
		}
		
		$child = $this->children->getLast();
		if ($child instanceof \Mouuro\XmlParser\TextNode) {
			$child->setText(rtrim($child->getText()));
		}
	}
	
}

try {
	$h2t = new \Mouuro\XmlParser\Parser();
	$h2t->registerNodeClass(['placeholder'], PlaceholderNode::class);
	$h2t->registerNodeClass(['bold', 'italic', 'underline', 'font', 'a'], InlineNode::class);
	$h2t->registerNodeClass(['paragraph'], BlockNode::class);
	$h2t->registerNodeClass(['br'], SelfClosingNode::class);
	$h2t->registerNodeClass(['body'], \Mouuro\XmlParser\Node::class);
	$h2t->setDefaultNodeClass(null);
	$tree = $h2t->parse(file_get_contents('test.html'));

	echo (string)$tree . PHP_EOL;
} catch (Exception $e) {
	echo $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
