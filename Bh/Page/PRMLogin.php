<?php

namespace Bh\Page;

class PRMLogin extends PRMPage
{
    // {{{ hookConstructor
    protected function hookConstructor()
    {
        parent::hookConstructor();

        $this->stylesheets[] = '/vendor/depage/htmlform/lib/css/depage-forms.css';

        $this->title = 'Login';

        $this->form = new LoginForm($this->controller);
    }
    // }}}
    // {{{ renderContent
    public function renderContent()
    {
        return parent::renderContent(new LoginForm($this->controller));
    }
    // }}}
}
