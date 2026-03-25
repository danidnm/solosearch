<?php

namespace SoloSearch\Core\Block;

/**
 * Class BlockList
 * A block that serves purely as a structural wrapper to contain 
 * and render child blocks. It has no template of its own.
 */
class BlockList extends AbstractBlock
{
    /**
     * Render the block by simply concatenating all children's HTML.
     */
    public function toHtml(): string
    {
        return $this->getChildHtml();
    }
}
