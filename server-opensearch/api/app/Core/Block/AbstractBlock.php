<?php

namespace SoloSearch\Core\Block;

use Psr\Container\ContainerInterface;

/**
 * Class AbstractBlock
 * Provides the base functionality for blocks including child management,
 * data storage, and sorting by position.
 */
abstract class AbstractBlock implements BlockInterface
{
    protected string $name;
    protected array $data;
    protected array $childs = [];
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container, string $name = '', array $data = [])
    {
        $this->container = $container;
        $this->name = $name;
        $this->data = $data;
    }

    public function getName(): string 
    { 
        return $this->name; 
    }

    public function setName(string $name): self 
    { 
        $this->name = $name; 
        return $this; 
    }
    
    public function addChild(string $alias, BlockInterface $block, int $position = 0): self
    {
        $this->childs[$alias] = [
            'block' => $block,
            'position' => $position
        ];
        return $this;
    }

    public function getChild(string $alias): ?BlockInterface
    {
        return $this->childs[$alias]['block'] ?? null;
    }

    public function getChilds(): array
    {
        return array_column($this->childs, 'block', array_keys($this->childs));
    }

    public function getSortedChilds(): array
    {
        $sorted = $this->childs;
        usort($sorted, fn($a, $b) => $a['position'] <=> $b['position']);
        return array_map(fn($item) => $item['block'], $sorted);
    }
    
    public function getData(?string $key = null, $default = null)
    {
        if ($key === null) return $this->data;
        return $this->data[$key] ?? $default;
    }

    public function getChildHtml(string $alias = ''): string
    {
        if ($alias === '') {
            $html = '';
            foreach ($this->getSortedChilds() as $child) {
                $html .= $child->toHtml();
            }
            return $html;
        }

        $child = $this->getChild($alias);
        return $child ? $child->toHtml() : '';
    }
}
