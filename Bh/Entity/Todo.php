<?php

namespace Bh\Entity;

class Todo extends Entry
{
    // {{{ variables
    protected $text;
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

    // {{{ addChild
    public function addChild($todo)
    {
        if (!$this->children->contains($todo)) {
            $this->children->add($todo);
        }
    }
    // }}}
    // {{{ getChildren
    public function getChildren()
    {
        return $this->children->toArray();
    }
    // }}}
}
