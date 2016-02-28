<?php

namespace Bh\Lib;

use Bh\Entity\Record;

class API {
    public function __construct()
    {
        $this->prm = new \Bh\Lib\PRM();
    }

    public function login($name, $pass)
    {
        return $this->prm->login($name, $pass);
    }

    protected function getEntities($class)
    {
        $getter = "get$class";
        $entities = [];

        foreach ($this->prm->$getter() as $entity) {
            $entities[$entity->getId()] = $entity->getName();
        }

        return $entities;
    }

    public function getCategories()
    {
        return $this->getEntities('Categories');
    }

    public function getTags()
    {
        return $this->getEntities('Tags');
    }

    public function getActivities()
    {
        return $this->getEntities('Activities');
    }

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

        $this->prm->editRecord($record);
    }
}
