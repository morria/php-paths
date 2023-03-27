<?php

declare(strict_types=1);

namespace Paths;

use ast\Node;
use Paths\FunctionScanner;
use Paths\GraphNodeVisitor;

class Paths
{
    private array $paths;

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    static function fromFileName(string $file_name): Paths
    {
        $paths = [];

        $ast = \ast\parse_file($file_name, $version = 90);
        foreach ((new FunctionScanner())($ast) as $method_ast) {
            $root = (new GraphNodeVisitor())($method_ast);
            foreach ($root->allTerminals() as $terminal) {
                foreach ($terminal->allPathsToOtherTerminals() as $path) {
                    // $paths[] = $path;
                    print "$path\n";
                }
            }
        }

        $p = new Paths($paths);
        print $p->__toString();

        return $p;
    }

    public function __toString(): string
    {
        return implode("\n", $this->paths);
    }
}
