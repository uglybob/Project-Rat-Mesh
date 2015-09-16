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

        foreach ($records as $day => $dayRecords) {
            $startTotal = new \DateTime('00:00');
            $endTotal = clone $startTotal;

            foreach ($dayRecords as $dayRecord) {
               $endTotal->add($dayRecord->getLength());
            }

            $lengthTotal = $startTotal->diff($endTotal);
            $lengthString = ($lengthTotal->h > 0) ? $lengthTotal->format('%hh %Im') : $lengthTotal->format('%Im');

            $content .= HTML::div(['.title'],
                HTML::div(['.date'], $day) .
                HTML::div(['.length'], $lengthString)
            );
            $content .= new RecordList($dayRecords, 'record', false, false);
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
