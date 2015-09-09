<?php

namespace Bh\Page;

class RecordList extends ObjectList
{
    // {{{ getProperties
    public function getProperties($record)
    {
        $end = $record->getEnd();
        $tempEnd = (is_null($end)) ? new \DateTime('now') : $end;
        $endString = ($end) ? ' - ' . $end->format('H:i') : '';

        return [
            $record->getStart()->format('H:i') . $endString,
            $record->getActivity() . '@' . $record->getCategory(),
            implode(', ', $record->getTags()),
            $tempEnd->diff($record->getStart())->format('%H:%I'),
        ];
    }
    // }}}
}
