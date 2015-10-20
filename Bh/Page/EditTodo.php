<?php

namespace Bh\Page;

class EditTodo extends EditEntry
{
    // {{{ save
    protected function save()
    {
        parent::save();

        $this->controller->editTodo($this->object);
    }
    // }}}
    // {{{ redirect
    protected function redirect()
    {
        Page::redirect('/todos');
    }
    // }}}

    // {{{ instantiateObject
    protected function instantiateObject()
    {
        $this->object = new \Bh\Entity\Todo($this->controller->getCurrentUser());
    }
    // }}}
}
