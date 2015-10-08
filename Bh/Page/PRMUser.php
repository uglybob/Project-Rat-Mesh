<?php

namespace Bh\Page;

use Bh\Lib\Controller;

class PRMUser extends PRMPage
{
    // {{{ hookConstructor
    protected function hookConstructor()
    {
        parent::hookConstructor();

        $this->stylesheets[] = '/vendor/depage/htmlform/lib/css/depage-forms.css';
        $this->registrationForm = new RegistrationForm($this->controller, $this->getPath(1));
    }
    // }}}

    // {{{ renderContent
    public function renderContent()
    {
        return parent::renderContent($this->registrationForm);
    }
    // }}}
}
