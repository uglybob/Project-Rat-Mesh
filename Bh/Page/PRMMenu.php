<?php

namespace Bh\Page;

class PRMMenu
{
    protected $links;

    // {{{ constructor
    public function __construct($user = null)
    {
        if ($user) {
            $linksLeft = [
                'home' => '/',
                'records' => '/records',
                'totals' => '/totals',
                'todos' => '/todos',
            ];
            $linksRight = [
                'logout' => '/login',
                $user->__toString() => '/user',
            ];
        } else {
            $linksLeft = [
                'home' => '/',
            ];
            $linksRight = [
                'login' => '/login',
                'register' => '/user',
            ];
        }

        $this->menu = HTML::div(['.menuBar'],
            HTML::div(['.menuLeft'], HTML::menu($linksLeft))
            . HTML::div(['.menuRight'], HTML::menu($linksRight))
        );
    }
    // }}}
    // {{{ toString
    public function __toString()
    {
        return $this->menu;
    }
    // }}}
}
