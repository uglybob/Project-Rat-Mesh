<?php

namespace Bh\Entity;

use Bh\Lib\Mapper;

class EntryInterface extends PrivateEntity
{
    protected $entry;

    // {{{ constructor
    public function __construct(User $user)
    {
        parent::__construct($user);

        $this->entry = new Entry();
        Mapper::save($this->entry);
    }
    // }}}

    // {{{ setCategory
    public function setCategory(Category $category)
    {
        $this->entry->setCategory($category);
    }
    // }}}
    // {{{ getCategory
    public function getCategory()
    {
        return $this->entry->getCategory();
    }
    // }}}

    // {{{ setTags
    public function setTags(array $tags)
    {
        $this->addTagAssocs($tags);
    }
    // }}}
    // {{{ getTags
    public function getTags()
    {
        return $this->entry->getTags();
    }
    // }}}

    // {{{ getTagAssocs
    protected function getTagAssocs(array $tags)
    {
        $assocs = Mapper::findBy(
            'EntryTagAssoc',
            [
                'entry' => $this->entry,
                'tag' => $tags,
            ]
        );

        return $assocs;
    }
    // }}}
    // {{{ addTagAssocs
    protected function addTagAssocs(array $tags)
    {
        $assocs = $this->getTagAssocs($tags);

        $tagIndex = [];
        foreach ($tags as $tag) {
            $tagIndex[$tag->__toString()] = $tag;
        }

        foreach ($assocs as $assoc) {
            unset($tagIndex[$assoc->getTag()->__toString()]);
        }

        foreach($tagIndex as $tag) {
            $assoc = new EntryTagAssoc($this->entry, $tag);
            $this->entry->addEntryTagAssoc($assoc);
            $tag->addEntryTagAssoc($assoc);

            Mapper::save($assoc);
        }
    }
    // }}}
}
