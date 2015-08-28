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
        $this->form->addDate('date');
    }
    // }}}
    // {{{ save
    protected function save()
    {
        $values = $this->form->getValues();
    }
    // }}}
    // {{{ populate
    protected function populate()
    {
        $values = [
            'Category' => $this->object->getCategory()->getName(),
        ];

        $this->form->populate($values);
    }
    // }}}
}
