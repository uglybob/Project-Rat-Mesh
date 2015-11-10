<?php

namespace Bh\Page;

abstract class EditEntry extends EditForm
{
    // {{{ create
    protected function create()
    {
        $this->form->addText(
            'Activity',
            [
                'list' => $this->controller->getActivities(),
                'autocomplete' => false,
            ]

        );
        $this->form->addText(
            'Category',
            [
                'list' => $this->controller->getCategories(),
                'required' => true,
                'autocomplete' => false,
            ]
        )->setRequired();

        $this->form->addText(
            'Tags',
            [
                'list' => $this->controller->getTags(),
                'autocomplete' => false,
            ]
        );

        $this->form->addText('Text', ['autocomplete' => false]);
    }
    // }}}
    // {{{ populate
    protected function populate()
    {
        $values = [
            'Activity' => $this->object->getActivity(),
            'Category' => $this->object->getCategory(),
            'Tags' => implode(', ', $this->object->getTags()),
            'Text' => $this->object->getText(),
        ];

        $this->form->populate($values);
    }
    // }}}
    // {{{ save
    protected function save()
    {
        $values = $this->form->getValues();

        $this->object->setActivity($values['Activity']);
        $this->object->setCategory($values['Category']);
        $this->object->setText($values['Text']);
        $this->object->setTags(explode(',', $values['Tags']));
    }
    // }}}
}
