<?php

namespace Bh\Page\Module;

use Bh\Page\Module\ObjectList;

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

        $tags = '';

        foreach ($record->getTags() as $tag) {
            $tags .= HTML::span(['.tag'], $tag);
        }

        return [
            'time' => $start->format('H:i') . ' - ' . $endString,
            'name' => HTML::span(['.activity'], $record->getActivity()) . HTML::span(['.category'], ' - ' . $record->getCategory()),
            'tags' => $tags,
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
