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
        return $this->getAttributesLengths('category', 'categories', $start, $end);
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
        return $this->getAttributesLengths('activity', 'activities', $start, $end);
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
    // {{{ getTagsLengths
    public function getTagsLengths($start = null, $end = null)
    {
        $conn = Mapper::getEntityManager()->getConnection();
        $sql = "SELECT tags.name, SUM(length) AS length
            FROM record_tag
            JOIN tags
            ON tag_id = tags.id
            JOIN records
            ON record_id=records.id
            WHERE (
                tags.user_id={$this->getCurrentUser()->getId()}
                AND records.deleted=false
                AND tags.deleted=false
                {$this->sqlDateClause($start, $end, 'records')}
            )
            GROUP BY tags.id
            ORDER BY length DESC";

        $stmt = $conn->prepare($sql);
        if ($start) { $stmt->bindParam('start', $start->format("Y-m-d H:i:s")); }
        if ($end) { $stmt->bindParam('end', $end->format("Y-m-d H:i:s")); }

        $stmt->execute();
        return $stmt->fetchAll();
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
    protected function getAttributesLengths($attribute, $table, $start, $end)
    {
        $conn = Mapper::getEntityManager()->getConnection();
        $sql = "SELECT a.name, SUM(r.length) AS length
            FROM records AS r
            JOIN {$table} AS a
            ON r.{$attribute}_id=a.id
            WHERE (
                r.user_id = {$this->getCurrentUser()->getId()}
                AND r.deleted = false
                AND a.deleted = false
                {$this->sqlDateClause($start, $end, 'r')}
            )
            GROUP BY r.{$attribute}_id
            ORDER BY length DESC";

        $stmt = $conn->prepare($sql);
        if ($start) { $stmt->bindParam('start', $start->format("Y-m-d H:i:s")); }
        if ($end) { $stmt->bindParam('end', $end->format("Y-m-d H:i:s")); }

        $stmt->execute();
        return $stmt->fetchAll();
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
        $query = Mapper::getEntityManager()->createQuery("
            SELECT r
            FROM Bh\Entity\Record r
            WHERE r.user = :user
            AND r.deleted = false
            {$this->sqlDateClause($start, $end, 'r')}
            ORDER BY r.start ASC
        ");

        $query->setParameter('user', $this->getCurrentUser());
        if ($start) { $query->setParameter('start', $start); }
        if ($end) { $query->setParameter('end', $end); }

        return $query->getResult();
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

                $this->logAction(
                    'edit',
                    'Record',
                    $newRecord->getId() . '|' . implode('|',  $newRecord->getRow())
                );

                Mapper::commit();
            }
       }
    }
    // }}}

    // {{{ sqlDateClause
    protected function sqlDateClause($start, $end, $tableName)
    {
        $dateClause = '';

        if ($start) { $dateClause .= " AND $tableName.start >= :start "; }
        if ($end) { $dateClause .= " AND $tableName.start <= :end "; }

        return $dateClause;
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
