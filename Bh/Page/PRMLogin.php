<?php

namespace Bh\Page;

class PRMLogin extends PRMPage
{
    // {{{ constructor
    public function __construct($controller, $path)
    {
        parent::__construct($controller, $path);

        $this->stylesheets[] = '/vendor/depage/htmlform/lib/css/depage-forms.css';
    }
    // }}}
    // {{{ hookTitle
    public function hookTitle()
    {
        return 'Login';
    }
    // }}}
    // {{{ renderContent
    public function renderContent()
    {
        return parent::renderContent(new LoginForm($this->controller));
    }
    // }}}
}
