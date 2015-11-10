<?php

namespace Bh\Entity;

class Todo extends Entry
{
    // {{{ variables
    protected $done = false;
    protected $children;
    protected $parent;
    // }}}
    // {{{ constructor
    public function __construct($user) {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($user);
    }
    // }}}

    // {{{ getChildren
    public function getChildren()
    {
        return $this->children->toArray();
    }
    // }}}

    // {{{ toString
    public function __toString()
    {
        return $this->getText();
    }
    // }}}
}
