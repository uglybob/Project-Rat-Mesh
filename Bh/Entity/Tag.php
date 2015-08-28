<?php

namespace Bh\Entity;

class Tag extends PrivateNamed
{
    protected $entryTagAssoc = null;

    // {{{ constructor
    public function __construct(User $user, $name)
    {
        parent::__construct($user, $name);

        $this->entryTagAssoc = new \Doctrine\Common\Collections\ArrayCollection();
    }
    // }}}

    // {{{ addEntryTagAssoc
    public function addEntryTagAssoc(EntryTagAssoc $assoc)
    {
        $this->entryTagAssoc[] = $assoc;
    }
    // }}}
}
