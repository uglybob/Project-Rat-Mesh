<?php

namespace Bh\Page;

class PRMPage extends Page
{
    // {{{ hookConstructor
    protected function hookConstructor()
    {
        $this->stylesheets[] = '/Bh/Page/css/fonts.css';
        $this->stylesheets[] = '/Bh/Page/css/layout.css';
        $this->stylesheets[] = '/Bh/Page/css/colors.css';

        $this->title = 'Project Rat Mesh';
    }
    // }}}
    // {{{ renderContent
    public function renderContent($content)
    {
        return (new PRMMenu($this->controller->getCurrentUser())) . $content;
    }
    // }}}
}
