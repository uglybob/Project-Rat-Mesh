<?php

namespace Bh\Page;

class PRMEdit extends Edit
{
    // {{{ hookTitle
    protected function hookTitle()
    {
        return "Edit $this->class";
    }
    // }}}
    // {{{ hookTemplate
    protected function hookTemplate()
    {
        $this->template = new PRMTemplate($this->controller);
    }
    // }}}
}
