<?php

namespace Bh\Page;

class TodoList extends ObjectList
{
    // {{{ getProperties
    public function getProperties($todo)
    {
        return [
            'text' => $todo->getText(),
            'activity' => $record->getActivity() . '@' . $record->getCategory(),
            'tags' => implode(', ', $record->getTags()),
        ];
    }
    // }}}
}
