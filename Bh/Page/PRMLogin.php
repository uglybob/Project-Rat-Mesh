<?php

namespace Bh\Page;

class PRMLogin extends Login
{
    // {{{ hookTitle
    protected function hookTitle()
    {
        return "Login";
    }
    // }}}
    // {{{ hookTemplate
    protected function hookTemplate()
    {
        $this->template = new PRMTemplate($this->controller);
    }
    // }}}
}
