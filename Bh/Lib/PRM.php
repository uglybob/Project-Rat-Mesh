<?php

namespace Bh\Lib;

use Bh\Entity\Record;
use Bh\Entity\Category;
use Bh\Entity\Tag;
use Bh\Entity\Entry;
use Bh\Entity\EntryTagAssoc;

class PRM extends Controller
{
     // {{{ getCategory
    public function getCategory($name)
    {
        return $this->getAttribute('Category', $name);
    }
    // }}}
    // {{{ addCategory
    public function addCategory($name)
    {
        return $this->addAttribute('Category', $name);
    }
    // }}}
     // {{{ getTag
    public function getTag($name)
    {
        return $this->getAttribute('Tag', $name);
    }
    // }}}
    // {{{ addTag
    public function addTag($name)
    {
        return $this->addAttribute('Tag', $name);
    }
    // }}}

     // {{{ getAttribute
    protected function getAttribute($type, $name)
    {
        $attribute = $this->mapper->findOneBy(
            $type,
            [
                'user' => $this->getCurrentUser(),
                'name' => $name,
            ]
        );

        return $attribute;
    }
    // }}}
     // {{{ addAttribute
    protected function addAttribute($type, $name)
    {
        $attribute = $this->getAttribute($type, $name);

        if (!$attribute) {
            $class = 'Bh\Entity\\' . $type;
            $attribute= new $class($this->getCurrentUser(), $name);
            $this->save($attribute);
        }

        return $attribute;
    }
    // }}}

    // {{{ addRecordRaw
    public function addRecordRaw($categoryName, array $tagNames = [], \DateTime $start = null, \DateTime $end = null)
    {
        $category = $this->addCategory($categoryName);

        foreach (array_unique($tagNames) as $tagName) {
            $tags[] = $this->addTag($tagName);
        }

        return $this->addRecord($category, $tags);
    }
    // }}}
    // {{{ addRecord
    protected function addRecord(Category $category, array $tags = [], \DateTime $start = null, \DateTime $end = null)
    {
        $entry = new Entry($category);
        $record = new Record($this->getCurrentUser(), $entry, $start, $end);

        foreach ($tags as $tag) {
            $assoc = new EntryTagAssoc($entry, $tag);
            $this->save($assoc);
        }

        $this->save($entry);
        $this->save($record);
        $this->commit();

        return $record;
    }
    // }}}
}
