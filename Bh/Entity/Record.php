<?php

namespace Bh\Entity;

use Bh\Lib\Mapper;

class Record extends PrivateEntity
{
    protected $entry;
    protected $start;
    protected $end;

    // {{{ constructor
    public function __construct(User $user, Category $category, array $tags = [], \DateTime $start = null, \DateTime $end = null)
    {
        parent::__construct($user);

        $this->entry = new Entry($category);
        Mapper::save($this->entry);

        foreach ($tags as $tag) {
            $assoc = new EntryTagAssoc($this->entry, $tag);
            Mapper::save($assoc);
        }

        if (is_null($start)) {
            $this->start = new \DateTime('now');
        }

        $this->end = $end;
    }
    // }}}

    // {{{ getCategory
    public function getCategory()
    {
        return $this->entry->getCategory();
    }
    // }}}
}
