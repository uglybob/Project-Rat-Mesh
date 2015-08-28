<?php

namespace Bh\Page;

class EditRecord extends EditForm
{
    // {{{ create
    protected function create()
    {
        $this->form->addText(
            'Category',
            ['list' => $this->controller->getCategories()]
        );

        $this->form->addText('Tags');

        $this->form->addDate('Start-Date');
        $this->form->addTime('Start-Time');

        $this->form->addDate('End-Date');
        $this->form->addTime('End-Time');
    }
    // }}}
    // {{{ populate
    protected function populate()
    {
        $values = [
            'Category' => $this->object->getCategory(),
            'Tags' => implode(', ', $this->object->getTags()),
        ];

        $this->form->populate($values);
    }
    // }}}
    // {{{ instantiateObject
    protected function instantiateObject($class)
    {
        return new \Bh\Entity\Record($this->prm);
    }
    // }}}
    // {{{ save
    protected function save()
    {
        $values = $this->form->getValues();
    }
    // }}}
}
