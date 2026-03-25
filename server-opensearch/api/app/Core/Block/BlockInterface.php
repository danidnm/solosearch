<?php

namespace SoloSearch\Core\Block;

interface BlockInterface
{
    public function getName(): string;
    public function setName(string $name): self;
    public function addChild(string $alias, BlockInterface $block, int $position = 0): self;
    public function getChild(string $alias): ?BlockInterface;
    public function getChilds(): array;
    public function getSortedChilds(): array;
    public function getChildHtml(string $alias = ''): string;
    public function toHtml(): string;
}
