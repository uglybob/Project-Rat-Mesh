<?php

namespace Bh\Page;

class PRMBackend extends Backend
{
    // {{{ constructor
    public function __construct($controller, $path)
    {
        $this->stylesheets[] = '/Bh/Page/css/fonts.css';
        $this->stylesheets[] = '/Bh/Page/css/layout.css';

        parent::__construct($controller, $path);
    }
    // }}}
    public function renderContent($content)
    {
        return new PRMMenu($this->controller->getCurrentUser()) . $content;
    }
}
