<?php

namespace Bh;

use Bh\Entity\Record;

require_once('vendor/autoload.php');

class PRMAPI {
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
        return $this->prm->getEntities('Categories');
    }

    public function getTags()
    {
        return $this->prm->getEntities('Tags');
    }

    public function getActivities()
    {
        return $this->prm->getEntities('Activities');
    }

    public function editRecord($id, $start, $end, $activity, $category, $tags, $text)
    {
        if ($id) {
            $record = $this->prm->getRecord($id);
        } else {
            $record = new Record($this->prm->getCurrentUser());
        }

        $record->setStart((new \DateTime())->setTimestamp($start));
        $record->setEnd((new \DateTime())->setTimestamp($end));
        $record->setActivity($activity);
        $record->setCategory($category);
        $record->setTags($tags);
        $record->setText($text);

error_log($record->__toString());
        $this->prm->editRecord($record);
    }
}

$options = ['uri' => 'http://localhost/'];
$server = new \SoapServer(null, $options);
$server->setClass('Bh\PRMAPI');
$server->setPersistence(SOAP_PERSISTENCE_SESSION);
$server->handle();
