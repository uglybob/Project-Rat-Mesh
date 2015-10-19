<?php

namespace Bh\Page;

class TodoList extends ObjectList
{
    // {{{ getProperties
    public function getProperties($todo)
    {
        return [
            'text' => $todo->getText(),
            'activity' => $todo->getActivity() . '@' . $todo->getCategory(),
            'tags' => implode(', ', $todo->getTags()),
        ];
    }
    // }}}
}
