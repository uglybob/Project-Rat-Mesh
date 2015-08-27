<?php

namespace Bh\Lib;

use Bh\Entity\Record;
use Bh\Entity\Category;

class PRM extends Controller
{
     // {{{ getCategory
    public function getCategory($name)
    {
        $category = $this->mapper->findOneBy(
            'Category',
            [
                'user' => $this->getCurrentUser(),
                'name' => $name,
            ]
        );

        return $category;
    }
    // }}}
     // {{{ addCategory
    public function addCategory($name)
    {
        $category = $this->getCategory($name);

        if (!$category) {
            $category = new Category($this->getCurrentUser(), $name);
            $this->save($category);
            $this->commit();
        }

        return $category;
    }
    // }}}

    // {{{ addRecord
    public function addRecord($categoryName)
    {
        $record = new Record($this->getCurrentUser());
        $category = $this->addCategory($categoryName);
        $record->setCategory($category);

        $this->save($record);
        $this->commit();
    }
    // }}}
}
