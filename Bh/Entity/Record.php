<?php

namespace Bh\Entity;

use Bh\Lib\Mapper;

class Record extends EntryInterface
{
    protected $start;
    protected $end;

    // {{{ constructor
    public function __construct(PRM $controller)
    {
        parent::__construct($controller);

        foreach ($tags as $tag) {
            $assoc = new EntryTagAssoc($this->entry, $tag);
            Mapper::save($assoc);
        }

        $this->start = new \DateTime('now');
        $this->end = null;
    }
    // }}}
}
