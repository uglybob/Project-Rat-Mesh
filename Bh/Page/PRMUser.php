<?php

namespace Bh\Page;

class PRMUser extends User
{
    // {{{ hookTitle
    protected function hookTitle()
    {
        return 'User';
    }
    // }}}
    // {{{ hookTemplate
    protected function hookTemplate()
    {
        $this->template = new PRMTemplate($this->controller);
    }
    // }}}
}
