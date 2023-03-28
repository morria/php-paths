<?php

declare(strict_types=1);

namespace Paths\GraphNode;

use Paths\PartialPath;
use Paths\Path;
use Paths\GraphNode;

class NonTerminal extends GraphNode
{
    private array $children = [];

    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    public function appendChild(GraphNode $child): void
    {
        $this->children[] = $child;
    }

    public function isTerminal(): bool
    {
        return false;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return \Generator<Path>
     * Generate all paths to all terminals reachable from
     * this node.
     */
    public function allPathsToTerminals(PartialPath $prefix): \Generator
    {
        $previous_node = $prefix->lastNode();
        $prefix = $prefix->withNonTerminal($this);

        foreach ($this->children as $child) {
            // Skip the node we just popped out of
            if ($child === $previous_node) {
                continue;
            }
            foreach ($child->allPathsToTerminals($prefix) as $path) {
                yield $path;
            }
        }
    }

    /**
     * 
     * @return \Generator<Terminal>
     * Generate all the terminal nodes reachable from this node
     */
    public function allTerminals(): \Generator
    {
        foreach ($this->children as $child) {
            foreach ($child->allTerminals() as $terminal) {
                yield $terminal;
            }
        }
    }
}
