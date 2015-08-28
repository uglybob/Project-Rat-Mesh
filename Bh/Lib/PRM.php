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
     // {{{ getTag
    public function getTag($name)
    {
        return $this->getAttribute('Tag', $name);
    }
    // }}}
     // {{{ getTagsById
    public function getTagsById(array $ids)
    {
        $tags = Mapper::findBy(
            'Tag',
            [
                'user' => $this->getCurrentUser(),
                'id' => $ids,
            ]
        );

        return $tags;
 
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

        $record->setPrm($this);

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
    public function editRecord(Record $Record)
    {
        Mapper::save($record);
        Mapper::commit($record);
    }
    // }}}
}
