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
    public function getCategoriesLengths()
    {
        return $this->getAttributesLengths('category', 'categories');
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
    public function getActivitiesLengths()
    {
        return $this->getAttributesLengths('activity', 'activities');
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
    public function getTagsLengths()
    {
        $conn = Mapper::getEntityManager()->getConnection();
        $sql = "SELECT tags.name, SUM(TIME_TO_SEC(TIMEDIFF(end, start))) AS length
            FROM record_tag
            JOIN tags
            ON tag_id = tags.id
            JOIN records
            ON record_id=records.id
            WHERE (
                tags.user_id={$this->getCurrentUser()->getId()}
                AND records.deleted=false
                AND tags.deleted=false
            )
            GROUP BY tags.id
            ORDER BY length DESC";
        $stmt = $conn->prepare($sql);
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
    protected function getAttributesLengths($attribute, $table)
    {
        $conn = Mapper::getEntityManager()->getConnection();
        $sql = "SELECT a.name, SUM(TIME_TO_SEC(TIMEDIFF(r.end, r.start))) AS length
            FROM records AS r
            JOIN {$table} AS a
            ON r.{$attribute}_id=a.id
            WHERE (
                r.user_id = {$this->getCurrentUser()->getId()}
                AND r.deleted = false
                AND a.deleted = false
            )
            GROUP BY r.{$attribute}_id
            ORDER BY length DESC";

        $stmt = $conn->prepare($sql);
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

    // {{{ logAction
    public function logAction($action, $class, $content)
    {
        $user = $this->getCurrentUser();
        Log::log('(' . $user->getId() . ') ' . $user->getEmail() . ' : ' . $action . ' : ' . $content);
    }
    // }}}
}
