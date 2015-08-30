<?php

namespace Bh\Lib;

use Bh\Entity\Record;
use Bh\Entity\Category;
use Bh\Entity\Activity;
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

     // {{{ getActivity
    public function getActivity($name)
    {
        return $this->getAttribute('Activity', $name);
    }
    // }}}
     // {{{ getActivities
    public function getActivities()
    {
        return $this->getAttributes('Activity');
    }
    // }}}
    // {{{ addActivity
    public function addActivity($name)
    {
        return $this->addAttribute('Activity', $name);
    }
    // }}}

    // {{{ addTag
    public function addTag($name)
    {
        return $this->addAttribute('Tag', $name);
    }
    // }}}
    // {{{ addTags
    public function addTags(array $names)
    {
        $tags = [];

        foreach($names as $name) {
            if ($tag = $this->addTag($name)) {
                $tags[] = $tag;
            }
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
        $this->logAction('add', $type, $name);

        $attribute = null;
        $name = trim($name);

        if (!empty($name)) {
            $attribute = $this->getAttribute($type, $name);

            if (!$attribute) {
                $class = 'Bh\Entity\\' . $type;
                $attribute= new $class($this->getCurrentUser(), $name);
                Mapper::save($attribute);
            }
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
            ['user' => $this->getCurrentUser()],
            false,
            ['start' => 'ASC']
        );

        return $records;
    }
    // }}}
    // {{{ getCurrentRecords
    public function getCurrentRecords()
    {
        $records = Mapper::findBy(
            'Record',
            [
                'user' => $this->getCurrentUser(),
                'end' => null,
            ],
            false,
            ['start' => 'ASC']
        );

        return $records;
    }
    // }}}
    // {{{ editRecord
    public function editRecord($id, $start, $end, $activity, $category, $tags)
    {
        $record = $this->getRecord($id);

        if (is_null($record)) {
            $record = new Record($this->getCurrentUser());
            Mapper::save($record);
        }

        $record->setStart($start);
        $record->setEnd($end);
        $record->setActivity($this->addActivity($activity));
        $record->setCategory($this->addCategory($category));
        $record->setTags($this->addTags($tags));

        $this->logAction(
            'edit',
            'Record',
            $id . '|' . implode('|',  $record->getRow())
        );

        Mapper::commit($record);
    }
    // }}}

    // {{{ logAction
    public function logAction($action, $class, $content)
    {
        $user = $this->getCurrentUser();
        Log::log('(' . $user->getId() . ') ' . $user->getEmail() . ' : ' . $action . ' : ' . $content);
    }
    // }}}
}
