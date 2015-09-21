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
        return $this->getAttributes('Activity');
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
        return $this->getAttributes('Tag');
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
        if ($newRecord->getUser() === $this->getCurrentUser()) {
            if (
                ($newRecord->getId() && $this->getRecord($newRecord->getId()))
                || is_null($newRecord->getId())
            ) {
                $newRecord->setActivity($this->addActivity($newRecord->getActivity()));
                $newRecord->setCategory($this->addCategory($newRecord->getCategory()));
                $newRecord->setTags($this->addTags($newRecord->getTags()));

                if (is_null($newRecord->getId())) {
                    Mapper::save($newRecord);
                }

                Mapper::commit();
            }
       }
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
