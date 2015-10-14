<?php

namespace Bh\Page;

class PRMPage extends Page
{
    // {{{ hookTitle
    protected function hookTitle()
    {
        return 'Project Rat Mesh';
    }
    // }}}
    // {{{ hookTemplate
    protected function hookTemplate()
    {
        $this->template = new PRMTemplate($this->controller);
    }
    // }}}
}
