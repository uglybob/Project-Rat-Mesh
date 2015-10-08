<?php

namespace Bh\Page;

use Bh\Lib\Controller;

class PRMUser extends PRMPage
{
    // {{{ constructor
    public function __construct(Controller $controller, array $path)
    {
        parent::__construct($controller, $path);

        $this->stylesheets[] = '/vendor/depage/htmlform/lib/css/depage-forms.css';

        $id = isset($path[1]) ? $path[1] : null;

        $this->registrationForm = new RegistrationForm($controller, $id);
    }
    // }}}

    // {{{ renderContent
    public function renderContent()
    {
        return parent::renderContent($this->registrationForm);
    }
    // }}}
}
