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
    // {{{ convertRecord
    protected function convertRecord($record)
    {
        $obj = null;

        if ($record) {
            $obj = new \stdClass();

            $obj->id = $record->getId();
            $obj->start = $record->getStart()->getTimestamp();
            $obj->end = $record->isRunning() ? null : $record->getEnd()->getTimestamp();
            $obj->activity = $record->getActivity()->__toString();
            $obj->category = $record->getCategory()->__toString();

            foreach ($record->getTags() as $tag) {
                $obj->tags[] = $tag->__toString();
            }

            $obj->text = $record->getText();
        }

        return $obj;
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

    // {{{ getCurrentRecord
    public function getCurrentRecord()
    {
        $records = $this->prm->getCurrentRecords();
        $record = isset($records[0]) ? $records[0] : null;

        return $this->convertRecord($record);
  }
    // }}}

    // {{{ stopRecord
    public function stopRecord($id)
    {
        $result = null;

        if ($id) {
            $result = $this->prm->stopRecord($id);
        }

        return $this->convertRecord($result);
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

        return $this->convertRecord($this->prm->editRecord($record));
    }
    // }}}
}
