<?php

namespace Bh\Entity;

class Todo extends Entry
{
    protected $text;
    protected $done = false;
    protected $children;
    protected $parent;

    // {{{ constructor
    public function __construct($user) {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($user);
    }
    // }}}
}