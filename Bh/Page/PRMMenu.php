<?php

namespace Bh\Page;

class PRMMenu
{
    protected $links;

    // {{{ constructor
    public function __construct($backend = false)
    {
        if ($backend) {
            $this->links = [
                'home' => '/',
                'login' => '/login',
                'records' => '/records',
                'user' => '/user',
            ];
        } else {
            $this->links = [
                'home' => '/',
                'login' => '/login',
                'register' => '/user',
            ];
        }
    }
    // }}}
    // {{{ toString
    public function __toString()
    {
        return Html::menu($this->links) . $content;
    }
    // }}}
}
