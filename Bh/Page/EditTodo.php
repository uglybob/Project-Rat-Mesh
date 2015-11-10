<?php

namespace Bh\Page;

class EditTodo extends EditEntry
{
    // {{{ create
    protected function create()
    {
        parent::create();

        $todos = $this->controller->getTodos();
        $id = $this->object->getId();
        $parentList = ['null' => null];

        foreach ($todos as $todo) {
            if ($todo->getId() != $id) {
                $parentList[$todo->getId()] = $todo;
            }
        }

        $this->form->addSingle(
            'Parent',
            [
                'skin' => 'select',
                'list' => $parentList,
                'autocomplete' => false,
            ]
        );
    }
    // }}}
    // {{{ populate
    protected function populate()
    {
        parent::populate();

        $parent = $this->object->getParent();

        if (!is_null($parent)) {
            $values = [
                'Parent' => $parent->getId(),
            ];

            $this->form->populate($values);
        }
    }
    // }}}
    // {{{ save
    protected function save()
    {
        parent::save();

        $values = $this->form->getValues();

        $parent = $this->controller->getTodo($values['Parent']);
        $this->object->setParent($parent);

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
