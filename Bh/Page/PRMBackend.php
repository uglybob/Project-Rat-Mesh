<?php

namespace Bh\Page;

use Bh\Lib\Html;

class PRMBackend extends Backend
{
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
