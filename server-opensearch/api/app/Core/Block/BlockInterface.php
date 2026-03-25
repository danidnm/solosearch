<?php

namespace SoloSearch\Core\Block;

/**
 * Interface BlockInterface
 * Defines the contract for all blocks in the layout system.
 */
interface BlockInterface
{
    /**
     * Get the alias/name of the block.
     */
    public function getName(): string;

    /**
     * Set the alias/name of the block.
     */
    public function setName(string $name): self;

    /**
     * Add a child block with a specific alias and position for sorting.
     */
    public function addChild(string $alias, BlockInterface $block, int $position = 0): self;

    /**
     * Get a specific child block by its alias.
     */
    public function getChild(string $alias): ?BlockInterface;

    /**
     * Get all child blocks as an associative array.
     */
    public function getChilds(): array;

    /**
     * Get all child blocks sorted by their position.
     */
    public function getSortedChilds(): array;

    /**
     * Render a specific child's HTML (or all children if alias is empty).
     */
    public function getChildHtml(string $alias = ''): string;

    /**
     * Render this block to HTML.
     */
    public function toHtml(): string;
}
