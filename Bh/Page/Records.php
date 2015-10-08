<?php

namespace Bh\Page;

class Records extends PRMBackend
{
    // {{{ renderContent
    public function renderContent()
    {
        $timespan = new TimespanSelector();
        $content = $timespan; 
        $records = $this->splitRecords($this->controller->getRecords($timespan->getStart(), $timespan->getEnd()));

        $totalLength = 0;
        foreach ($records as $day => $dayRecords) {
            $totalDayLength = 0;

            foreach ($dayRecords as $dayRecord) {
                $length = $dayRecord->getLength();

                if (is_null($length)) {
                    $length = abs(time() - $dayRecord->getStart()->getTimestamp());
                }

                $totalDayLength += $length;
            }

            $totalLength += $totalDayLength;

            $lengthString = RecordList::formatLength($totalDayLength);

            $list = HTML::div(['.title'],
                HTML::div(['.date'], $day) .
                HTML::div(['.length'], $lengthString)
            );
            $list .= new RecordList($dayRecords, 'record', false, false);
        }

        $content .= HTML::div(['.title', '.total'],
            HTML::div('total') .
            HTML::div(['.length'], RecordList::formatLength($totalLength))
        );

        if (!empty($records)) {
            $content .= $list;
        }

        return parent::renderContent(HTML::div($content));
    }
    // }}}
    // {{{ hookTitle
    public function hookTitle()
    {
        return 'Records';
    }
    // }}}
    // {{{ splitRecords
    public function splitRecords($records)
    {
        $result = [];

        foreach ($records as $record) {
            $result[$record->getStart()->format('l, M j')][] = $record;
        }

        return $result;
    }
    // }}}
}
