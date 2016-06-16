<?php

namespace Bh\Entity;

class Todo extends Entry
{
    // {{{ variables
    protected $done = null;
    protected $children;
    protected $parent;
    protected $position;
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
    // {{{ done
    public function done($done = true)
    {
        if ($done) {
            $this->done = new \Datetime('now');
        }
    }
    // }}}
    // {{{ isDone
    public function isDone()
    {
        return (bool) $this->done;
    }
    // }}}

    // {{{ check
    public function check()
    {
        if (!$this->isDone()) {
            $this->done = new \DateTime('now');

            foreach ($this->getChildren() as $child) {
                $child->check();
            }
        }
    }
    // }}}
    // {{{ uncheck
    public function uncheck()
    {
        $this->done = null;

        foreach ($this->getChildren() as $child) {
            $child->uncheck();
        }
    }
    // }}}
}
