<?php

namespace Bh\Page;

class Todos extends PRMBackend
{
    // {{{ renderContent
    public function renderContent()
    {
        $list = new TodoList($this->controller->getTodos(), 'todo', false, false);

        return HTML::div($list);
    }
    // }}}
    // {{{ hookTitle
    public function hookTitle()
    {
        return 'Todos';
    }
    // }}}
}
