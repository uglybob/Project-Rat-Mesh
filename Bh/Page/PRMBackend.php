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
        $links = [
            'home' => '/',
            'login' => '/login',
            'records' => '/records',
            'user' => '/user',
        ];

        return Html::menu($links) . $content;
    }
}
