<?php

namespace Bh\Lib;

use Bh\Entity\Record;
use Bh\Entity\Category;
use Bh\Entity\Tag;

class PRM extends Controller
{
     // {{{ getCategory
    public function getCategory($name)
    {
        return $this->getAttribute('Category', $name);
    }
    // }}}
     // {{{ getCategories
    public function getCategories()
    {
        return $this->getAttributes('Category');
    }
    // }}}
    // {{{ addCategory
    public function addCategory($name)
    {
        return $this->addAttribute('Category', $name);
    }
    // }}}

    // {{{ addTags
    public function addTags(array $names)
    {
        $names = array_unique($names);
        $newNames = [];

        $tags = Mapper::findBy(
            'Tag',
            [
                'user' => $this->getCurrentUser(),
                'name' => $names,
            ]
        );

        foreach ($tags as $tag) {
            unset($names[$tag->__toString()]);
        }

        foreach ($names as $name) {
            $newTag = new Tag($this->getCurrentUser(), $name);
            Mapper::save($newTag);
            $tags[] = $newTag;
        }

        return $tags;
    }
    // }}}

     // {{{ getAttribute
    protected function getAttribute($type, $name)
    {
        $name = is_string($name) ? $name : $name->__toString();

        $attribute = Mapper::findOneBy(
            $type,
            [
                'user' => $this->getCurrentUser(),
                'name' => $name,
            ]
        );

        return $attribute;
    }
    // }}}
     // {{{ getAttributes
    protected function getAttributes($type)
    {
        $attributes = Mapper::findBy(
            $type,
            [
                'user' => $this->getCurrentUser(),
            ]
        );

        return $attributes;
    }
    // }}}
     // {{{ addAttribute
    protected function addAttribute($type, $name)
    {
        $attribute = $this->getAttribute($type, $name);

        if (!$attribute) {
            $class = 'Bh\Entity\\' . $type;
            $attribute= new $class($this->getCurrentUser(), $name);
            Mapper::save($attribute);
        }

        return $attribute;
    }
    // }}}

    // {{{ getRecord
    public function getRecord($id)
    {
        $record = Mapper::findOneBy(
            'Record',
            [
                'id' => $id,
                'user' => $this->getCurrentUser(),
            ]
        );

        return $record;
    }
    // }}}
    // {{{ getRecords
    public function getRecords()
    {
        $records = Mapper::findBy(
            'Record',
            [
                'user' => $this->getCurrentUser(),
            ]
        );

        return $records;
    }
    // }}}
    // {{{ editRecord
    public function editRecord($id, $start, $end, $category, $tags)
    {
        $record = $this->getRecord($id);

        if (is_null($record)) {
            $record = new Record();
            Mapper::save($record);
        }

        $record->setStart($start);
        $record->setEnd($end);
        $record->setCategory($this->addCategory($category));
        $record->setTags($this->addTags($tags));

        Mapper::commit($record);
    }
    // }}}
}
