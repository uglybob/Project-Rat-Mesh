<?php

namespace Bh\Page;

use Bh\Lib\Controller;

class PRMEdit extends PRMBackend
{
    // {{{ constructor
    public function __construct(Controller $controller, array $path)
    {
        parent::__construct($controller, $path);

        $this->stylesheets[] = '/vendor/depage/htmlform/lib/css/depage-forms.css';

        $this->class = ucfirst($path[1]);
        $formType = 'Bh\Page\Edit' . $this->class;

        $id = isset($path[2]) ? $path[2] : null;
        $this->editForm = new $formType($controller, $this->class, $id);
    }
    // }}}

    // {{{ hookTitle
    public function hookTitle()
    {
        return 'Edit ' . $this->class;
    }
    // }}}
    // {{{ renderContent
    public function renderContent()
    {
        return parent::renderContent($this->editForm->__toString());
    }
}
