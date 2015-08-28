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
        )->setRequired();

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
    // {{{ save
    protected function save()
    {
        $id = ($this->object) ? $this->object->getId() : null;
        $values = $this->form->getValues();

        $this->controller->editRecord(
            $id,
            new \DateTime(),
            new \DateTime(),
            $values['Category'],
            explode(',', $values['Tags'])
        );
    }
    // }}}
    // {{{ redirect
    protected function redirect()
    {
        Page::redirect('/Records');
    }
    // }}}
}
