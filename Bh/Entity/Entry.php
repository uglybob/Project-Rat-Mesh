<?php

namespace Bh\Entity;

class Entry extends Entity
{
    protected $category;

    // {{{ constructor
    public function __construct(Category $category)
    {
        parent::__construct();

        $this->category = $category;
    }
    // }}}
}
