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
     // {{{ getCategoryList
    public function getCategoryList()
    {
        return $this->getAttributeList('Category');
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
     // {{{ getAttributeList
    protected function getAttributeList($type)
    {
        $attributeList = [];
        $attributes = Mapper::findBy(
            $type,
            [
                'user' => $this->getCurrentUser(),
            ]
        );

        foreach ($attributes as $attribute) {
            $attributeList[$attribute->getId()] = $attribute->getName();
        }

        return $attributeList;
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
        $record = new Record($this->getCurrentUser(), $category, $tags, $start, $end);

        Mapper::save($record);
        Mapper::commit();

        return $record;
    }
    // }}}
}
