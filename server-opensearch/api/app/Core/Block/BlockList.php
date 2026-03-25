<?php

namespace SoloSearch\Core\Block;

class BlockList extends AbstractBlock
{
    public function toHtml(): string
    {
        return $this->getChildHtml();
    }
}
