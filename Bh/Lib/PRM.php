<?php

namespace Bh\Lib;

use Bh\Entity\Record;
use Bh\Entity\Todo;
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
        return $this->getPrivateEntities('Category');
    }
    // }}}
    // {{{ getCategoriesLengths
    public function getCategoriesLengths($start = null, $end = null)
    {
        return $this->getAttributesLengths('category', $start, $end);
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
        return $this->getPrivateEntities('Activity');
    }
    // }}}
    // {{{ getActivitiesLengths
    public function getActivitiesLengths($start = null, $end = null)
    {
        return $this->getAttributesLengths('activity', $start, $end);
    }
    // }}}
    // {{{ addActivity
    public function addActivity($name)
    {
        return $this->addAttribute('Activity', $name);
    }
    // }}}

     // {{{ getTags
    public function getTags()
    {
        return $this->getPrivateEntities('Tag');
    }
    // }}}
    // {{{ getTagsLengths
    public function getTagsLengths($start = null, $end = null)
    {
        return $this->getAttributesLengths('tags', $start, $end);
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

    // {{{ getRecord
    public function getRecord($id)
    {
        return $this->getPrivateEntity('Record', $id);
    }
    // }}}
    // {{{ getLastRecord
    public function getLastRecord()
    {
        $result = null;

        if (
            ($user = $this->getCurrentUser())
            && ($userId = $user->getId())
        ) {
            $sql = 'SELECT r.id FROM records r WHERE r.user_id = :user AND r.deleted = false ORDER BY ADDDATE(r.start, INTERVAL r.length SECOND) DESC limit 1';
            $stmt = Mapper::getEntityManager()->getConnection()->prepare($sql);
            $stmt->execute([':user' => $userId]);
            $idList = $stmt->fetchAll();
            if (isset($idList[0])) {
                $id = $idList[0];
                $result = $this->getRecord($id);
            }
        }

        return $result;
    }
    // }}}
    // {{{ getRecords
    public function getRecords($start = null, $end = null)
    {
        $qb = Mapper::getEntityManager()->createQueryBuilder();

        $qb->select('r')
            ->from('Bh\Entity\Record', 'r')
            ->where('r.user = :user')
            ->andWhere('r.deleted = false')
            ->orderBy('r.start', 'ASC')
            ->setParameter('user', $this->getCurrentUser());

        $this->qbDateClause($qb, $start, $end);

        return $qb->getQuery()->getResult();
    }
    // }}}
    // {{{ getCurrentRecords
    public function getCurrentRecords()
    {
        $records = Mapper::findBy(
            'Record',
            [
                'user' => $this->getCurrentUser(),
                'length' => null,
            ],
            false,
            ['start' => 'ASC']
        );

        return $records;
    }
    // }}}
    // {{{ editRecord
    public function editRecord(Record $newRecord)
    {
        if ($newRecord->isRunning()) {
            foreach ($this->getCurrentRecords() as $running) {
                $running->stop();
            }
        }

        $record = $this->editEntry('Record', $newRecord);

        if ($record) {
            Mapper::commit();
        }

        return $record;
    }
    // }}}
    // {{{ stopRecord
    public function stopRecord($id)
    {
        $result = null;

        if (
            $id
            && ($record = $this->getRecord($id))
            && $record->stop()
        ) {
            Mapper::commit();
            $result = $record;
        }

        return $result;
    }
    // }}}

    // {{{ getTodo
    public function getTodo($id)
    {
        return $this->getPrivateEntity('Todo', $id);
    }
    // }}}
    // {{{ getTodos
    public function getTodos()
    {
        return $this->getPrivateEntities('Todo');
    }
    // }}}
    // {{{ editTodo
    public function editTodo(Todo $newTodo)
    {
        $this->editEntry('Todo', $newTodo);
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
    // {{{ getAttributesLengths
    protected function getAttributesLengths($attribute, $start, $end)
    {
        $qb = Mapper::getEntityManager()->createQueryBuilder();

        $qb->select('a.name')
            ->addSelect('SUM(r.length) AS length')
            ->from('Bh\Entity\Record', 'r')
            ->leftJoin("r.$attribute", 'a')
            ->where('r.user = :user')
            ->where('a.user = :user')
            ->setParameter('user', $this->getCurrentUser())
            ->andWhere('r.deleted = false')
            ->andWhere('a.deleted = false')
            ->groupBy('a.id')
            ->orderBy('length', 'DESC');

        $this->qbDateClause($qb, $start, $end);

        return $qb->getQuery()->getArrayResult();
    }
    // }}}
     // {{{ addAttribute
    protected function addAttribute($type, $name)
    {
        $attribute = null;

        $name = (is_object($name)) ? $name->__toString() : $name;
        $name = trim($name);

        if (!empty($name)) {
            $attribute = $this->getAttribute($type, $name);

            if (!$attribute) {
                $class = 'Bh\Entity\\' . $type;
                $attribute = new $class($this->getCurrentUser(), $name);
                Mapper::save($attribute);
            }
        }

        return $attribute;
    }
    // }}}

    // {{{ getPrivateEntity
    protected function getPrivateEntity($class, $id)
    {
        return Mapper::findOneBy(
            $class,
            [
                'id' => $id,
                'user' => $this->getCurrentUser(),
            ]
        );
    }
    // }}}
    // {{{ getPrivateEntities
    protected function getPrivateEntities($class)
    {
        return Mapper::findBy(
            $class,
            [
                'user' => $this->getCurrentUser(),
            ]
        );
    }
    // }}}

    // {{{ editEntry
    protected function editEntry($class, $newEntry)
    {
        $result = null;

        if (
            $newEntry->getUser() === $this->getCurrentUser()
            && (
                ($newEntry->getId() && $this->{"get$class"}($newEntry->getId()))
                || is_null($newEntry->getId())
            )
        ) {
            $newEntry->setActivity($this->addActivity($newEntry->getActivity()));
            $newEntry->setCategory($this->addCategory($newEntry->getCategory()));
            $newEntry->setTags($this->addTags($newEntry->getTags()));

            if (is_null($newEntry->getId())) {
                Mapper::save($newEntry);
            }

            Mapper::commit();

            $result = $newEntry;
        }

        return $result;
    }
    // }}}

    // {{{ qbDateClause
    protected function qbDateClause($qb, $start, $end)
    {
        if ($start) {
            $qb->andWhere('r.start >= :start');
            $qb->setParameter('start', $start);
        }
        if ($end) {
            $qb->andWhere('r.start <= :end');
            $qb->setParameter('end', $end);
        }
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
