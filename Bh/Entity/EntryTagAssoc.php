<?php

namespace Bh\Entity;

class EntryTagAssoc extends Entity
{
    protected $entry;
    protected $tag;

    // {{{ constructor
    public function __construct(Entry $entry, Tag $tag)
    {
        parent::__construct();

        $this->entry = $entry;
        $this->tag = $tag;
    }
    // }}}
}
