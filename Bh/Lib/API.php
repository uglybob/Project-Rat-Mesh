<?php

namespace Bh\Lib;

use Bh\Entity\Record;

class API {
    // {{{ constructor
    public function __construct()
    {
        $this->prm = new \Bh\Lib\PRM();
    }
    // }}}

    // {{{ login
    public function login($name, $pass)
    {
        return $this->prm->login($name, $pass);
    }
    // }}}

    // {{{ getEntities
    protected function getEntities($class)
    {
        $getter = "get$class";
        $entities = [];

        foreach ($this->prm->$getter() as $entity) {
            $entities[$entity->getId()] = $entity->getName();
        }

        return $entities;
    }
    // }}}

    // {{{ getCategories
    public function getCategories()
    {
        return $this->getEntities('Categories');
    }
    // }}}
    // {{{ getTags
    public function getTags()
    {
        return $this->getEntities('Tags');
    }
    // }}}
    // {{{ getActivities
    public function getActivities()
    {
        return $this->getEntities('Activities');
    }
    // }}}

    // {{{ getRecords
    public function getRecords($start = null, $end = null)
    {
        return $this->getRecords($start, $end);
    }
    // }}}

    // {{{ editRecord
    public function editRecord($id, $start, $end, $activity, $category, $tags, $text)
    {
        if ($id) {
            $record = $this->prm->getRecord($id);
        } else {
            $record = new Record($this->prm->getCurrentUser());
        }

        $start = (new \DateTime())->setTimestamp($start);
        $end = (is_null($end)) ? null : (new \DateTime())->setTimestamp($end);

        $record->setStart($start);
        $record->setEnd($end);
        $record->setActivity($activity);
        $record->setCategory($category);
        $record->setTags($tags);
        $record->setText($text);

        return (bool) $this->prm->editRecord($record);
    }
    // }}}
}
