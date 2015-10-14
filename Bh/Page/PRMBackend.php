<?php

namespace Bh\Page;

class PRMBackend extends Backend
{
    // {{{ hookTemplate
    protected function hookTemplate()
    {
        $this->template = new PRMTemplate($this->controller);
    }
    // }}}
}
