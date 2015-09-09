<?php

namespace Bh\Page;

class RecordList extends ObjectList
{
    // {{{ getProperties
    public function getProperties($record)
    {
        $end = (is_null($record->getEnd())) ? new \DateTime('now') : $record->getEnd();

        return [
            $record->getStart()->format('H:i d.m.Y'),
            $record->getActivity() . '@' . $record->getCategory(),
            implode(', ', $record->getTags()),
            $end->diff($record->getStart())->format('%H:%I'),
        ];
    }
    // }}}
}
