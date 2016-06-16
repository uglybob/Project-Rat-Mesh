<?php

namespace Bh\Lib;

use Bh\Entity\Record;
use Bh\Entity\Todo;

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

            $obj->tags = [];
            foreach ($record->getTags() as $tag) {
                $obj->tags[] = $tag->__toString();
            }

            $obj->text = $record->getText();
        }

        return $obj;
    }
    // }}}
    // {{{ convertTodo
    protected function convertTodo($todo)
    {
        $obj = null;

        if ($todo) {
            $obj = new \stdClass();

            $obj->id = $todo->getId();
            $obj->activity = $todo->getActivity()->__toString();
            $obj->category = $todo->getCategory()->__toString();
            $obj->done = ($todo->isDone()) ? $todo->getDone()->getTimestamp() : null;

            $obj->parentId = ($todo->getParent()) ? $todo->getParent()->getId() : null;
            $obj->position = $todo->getPosition();

            $obj->tags = [];
            foreach ($todo->getTags() as $tag) {
                $obj->tags[] = $tag->__toString();
            }

            $obj->text = $todo->getText();
        }

        return $obj;
    }
    // }}}
    // {{{ convertTodos
    protected function convertTodos($todos = [])
    {
        $converted = [];

        foreach($todos as $todo) {
            $converted[$todo->getId()] = $this->convertTodo($todo);
        }

        return $converted;
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
    // {{{ getLastRecord
    public function getLastRecord()
    {
        $record = $this->prm->getLastRecord();

        return $this->convertRecord($record);
  }
    // }}}

    // {{{ stopRecord
    public function stopRecord()
    {
        $result = $this->prm->stopRecord();

        return $this->convertRecord($result);
    }
    // }}}
    // {{{ editRecord
    public function editRecord($id, $activity, $category, $tags, $text, $start, $end)
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

    // {{{ editTodo
    public function editTodo($id, $activity, $category, $tags, $text, $parentId, $position, $done)
    {
        if ($id) {
            $todo = $this->prm->getTodo($id);
        } else {
            $todo = new Todo($this->prm->getCurrentUser());
        }

        $todo->setActivity($activity);
        $todo->setCategory($category);
        $todo->setTags($tags);
        $todo->setText($text);
        $todo->setParent($this->prm->getTodo($parentId));
        $todo->setPosition($position);

        if ($done) {
            $todo->check();
        } else {
            $todo->uncheck();
        }

        return $this->convertTodo($this->prm->editTodo($todo));
    }
    // }}}
    // {{{ deleteTodo
    public function deleteTodo($id)
    {
        $todo = $this->prm->getTodo($id);

        return $this->prm->deleteTodo($todo);
    }
    // }}}

    // {{{ getTodos
    public function getTodos($activity = null, $category = null, $tags = [])
    {
        $todos = $this->prm->getTodos($activity, $category, $tags);

        return $this->convertTodos($todos);
  }
    // }}}
    // {{{ getTodo
    public function getTodo($id)
    {
        $todo = $this->prm->getTodo($id);

        return $this->convertTodo($todo);
  }
    // }}}
}
