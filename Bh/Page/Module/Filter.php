<?php

namespace Bh\Page\Module;

class Filter
{
    // {{{ constructor
    public function __construct($elements)
    {
        $this->form = new \Depage\HtmlForm\HtmlForm('filter', ['label' => 'select']);

        foreach ($elements as $name => $list) {
            $this->form->addMultiple(
                $name,
                [
                    'list' => $list,
                    'skin' => 'select',
                ]
            ); //->setValue(array_search($this->getUnit(), $this->units));
        }

        $this->form->process();
    }
    // }}}

    // {{{ getElements
    public function getElements($element)
    {
        $values = $this->form->getValues();

        $elements =  isset($values[$element]) ? $values[$element] : null;

        return $elements;
    }
    // }}}

    // {{{ toString
    public function __toString()
    {
        return $this->form->__toString();
    }
    // }}}
}
