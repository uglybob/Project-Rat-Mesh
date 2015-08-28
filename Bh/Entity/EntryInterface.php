<?php

namespace Bh\Entity;

use Bh\Lib\Mapper;

class EntryInterface extends PrivateEntity
{
    protected $prm;
    protected $entry;

    // {{{ constructor
    public function __construct(PRM $prm)
    {
        parent::__construct($prm->getCurrentUser());

        $this->entry = new Entry();
        Mapper::save($this->entry);
    }
    // }}}

    // {{{ setCategory
    public function setCategory($category)
    {
        $this->entry->setCategory = $this->prm->addCategory($category);
    }
    // }}}
    // {{{ getCategory
    public function getCategory()
    {
        return $this->entry->getCategory();
    }
    // }}}

    // {{{ addTag
    public function addTag($newTag)
    {
        foreach ($newTags as $newTag) {
            $tag = $this->controller->addTag($newTag);
            $this->addEntryTagAssoc($this->entry, $tag);
        }
    }
    // }}}
    // {{{ getTags
    public function getTags()
    {
        $assocs = Mapper::findBy(
            'EntryTagAssoc',
            [
                'entry' => $this->entry,
            ]
        );

        return $assocs;
    }
    // }}}

     // {{{ getEntryTagAssoc
    protected function getEntryTagAssoc(Entry $entry, Tag $tag)
    {
        $assoc = Mapper::findOneBy(
            'EntryTagAssoc',
            [
                'entry' => $entry,
                'tag' => $tag,
            ]
        );

        return $assoc;
    }
    // }}}
    // {{{ addEntryTagAssoc
    protected function addEntryTagAssoc($entry, $tag)
    {
        $assoc = $this->getEntryTagAssoc($entry, $tag);

        if (!$assoc) {
            $attribute = new EntryTagAssoc($entry, $tag);
            Mapper::save($assoc);
        }

        return $assoc;
    }
    // }}}
}
