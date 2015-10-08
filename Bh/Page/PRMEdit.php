<?php

namespace Bh\Page;

use Bh\Lib\Controller;

class PRMEdit extends PRMBackend
{
    // {{{ hookConstructor
    protected function hookConstructor()
    {
        parent::hookConstructor();

        $this->stylesheets[] = '/vendor/depage/htmlform/lib/css/depage-forms.css';

        $class = ucfirst($this->getPath(1));
        $formType = 'Bh\Page\Edit' . $class;

        $this->editForm = new $formType($this->controller, $class, $this->getPath(2));

        $this->title = 'Edit ' . $class;
    }
    // }}}

    // {{{ renderContent
    public function renderContent()
    {
        return parent::renderContent($this->editForm);
    }
    // }}}
}
