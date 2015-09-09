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
                'records' => '/records',
                'user' => '/user',
                'logout' => '/login',
            ];
        } else {
            $this->links = [
                'home' => '/',
                'register' => '/user',
                'login' => '/login',
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
