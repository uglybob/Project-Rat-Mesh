<?php

namespace Bh\Page;

class RecordList extends ObjectList
{
    // {{{ getProperties
    public function getProperties($record)
    {
        $length = $record->getLength();
        $start = $record->getStart();
        $end = $record->getEnd();
        $endString = ($end) ? $end->format('H:i') : '';

        if (is_null($length)) {
            $length = abs(time() - $start->getTimestamp());
        }

        return [
            'start' => $start->format('H:i') . ' - ' . $endString,
            'activity' => $record->getActivity() . ' - ' . $record->getCategory(),
            'tags' => implode(', ', $record->getTags()),
            'length' => self::formatLength($length),
        ];
    }
    // }}}

    // {{{ formatLength
    public static function formatLength($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);

        if ($hours > 0) {
            $lengthString = $hours . 'h ' . sprintf('%02dm', $minutes);
        } else {
            $lengthString = $minutes . 'm';
        }

        return $lengthString;
    }
    // }}}
}
