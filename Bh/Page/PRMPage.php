<?php

namespace Bh\Page;

use Bh\Lib\Html;

class PRMPage extends Page
{
    public function renderContent($content)
    {
        $links = [
            'login' => '/login',
            'records' => '/records',
            'user' => '/user',
        ];

        return Html::menu($links) . $content;
    }
}
