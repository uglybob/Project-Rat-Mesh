<?php

namespace Bh\Page;

class PRMPage extends Page
{
    // {{{ constructor
    public function __construct($controller, $path)
    {
        $this->stylesheets[] = '/Bh/Page/css/fonts.css';
        $this->stylesheets[] = '/Bh/Page/css/layout.css';

        parent::__construct($controller, $path);
    }
    // }}}
    // {{{ renderContent
    public function renderContent($content)
    {
        $links = [
            'home' => '/',
            'login' => '/login',
            'records' => '/records',
            'user' => '/user',
        ];

        return Html::menu($links) . $content;
    }
    // }}}
}
