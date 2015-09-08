<?php

namespace Bh\Page;

class PRMPage extends Page
{
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
