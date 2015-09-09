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
            'start' => $record->getStart()->format('H:i') . $endString,
            'activity' => $record->getActivity() . '@' . $record->getCategory(),
            'tags' => implode(', ', $record->getTags()),
            'length' => $tempEnd->diff($record->getStart())->format('%H:%I'),
        ];
    }
    // }}}
}
