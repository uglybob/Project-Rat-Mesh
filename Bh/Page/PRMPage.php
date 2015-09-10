<?php

namespace Bh\Page;

class PRMPage extends Page
{
    // {{{ constructor
    public function __construct($controller, $path)
    {
        $this->stylesheets[] = '/Bh/Page/css/fonts.css';
        $this->stylesheets[] = '/Bh/Page/css/layout.css';
        $this->stylesheets[] = '/Bh/Page/css/colors.css';

        parent::__construct($controller, $path);
    }
    // }}}
    // {{{ hookTitle
    public function hookTitle()
    {
        return 'Project Rat Mesh';
    }
    // }}}
    // {{{ renderContent
    public function renderContent($content)
    {
        return (new PRMMenu($this->controller->getCurrentUser())) . $content;
    }
    // }}}
}
