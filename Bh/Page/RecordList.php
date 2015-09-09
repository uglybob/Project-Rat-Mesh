<?php

namespace Bh\Page;

class RecordList extends ObjectList
{
    // {{{ getProperties
    public function getProperties($record)
    {
        $end = $record->getEnd();
        $endString = ($end) ? ' - ' . $end->format('H:i') : '';
        $length = $record->getLength();

        $lengthString = ($length->h > 0) ? $length->format('%hh %Im') : $length->format('%Im');

        return [
            'start' => $record->getStart()->format('H:i') . $endString,
            'activity' => $record->getActivity() . '@' . $record->getCategory(),
            'tags' => implode(', ', $record->getTags()),
            'length' => $lengthString,
        ];
    }
    // }}}
}
