<?php

namespace Bh\Entity;

class Entry extends Entity
{
    protected $category;
    protected $entryTagAssoc = null;

    // {{{ constructor
    public function __construct()
    {
        parent::__construct();

        $this->entryTagAssoc = new \Doctrine\Common\Collections\ArrayCollection();
    }
    // }}}

    // {{{ addEntryTagAssoc
    public function addEntryTagAssoc(EntryTagAssoc $assoc)
    {
        $this->entryTagAssoc[] = $assoc;
    }
    // }}}
    // {{{ getTags
    public function getTags()
    {
        $tags = [];

        foreach ($this->entryTagAssoc as $assoc) {
            $tags[] = $assoc->getTag();
        }

        return $tags;
    }
    // }}}
}
