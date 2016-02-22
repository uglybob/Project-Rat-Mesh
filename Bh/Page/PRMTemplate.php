<?php

namespace Bh\Page;

use Bh\Page\Module\PRMMenu;

class PRMTemplate extends Template
{
    // {{{ constructor
    public function __construct($controller)
    {
        parent::__construct($controller);

        $this->stylesheets[] = '/Bh/Page/css/fonts.css';
        $this->stylesheets[] = '/Bh/Page/css/layout.css';
        $this->stylesheets[] = '/Bh/Page/css/colors.css';
    }
    // }}}

    // {{{ content
    public function content($content)
    {
        return (new PRMMenu($this->controller->getCurrentUser())) . $content;
    }
    // }}}
}
