<?php

namespace Bh\Page;

class Records extends PRMBackend
{
    // {{{ renderContent
    public function renderContent()
    {
        $content = '';
        $records = $this->splitRecords($this->controller->getRecords());

        foreach ($records as $day => $dayRecords) {
            $content .= HTML::div(['class' => 'day'], $day);
            $content .= new RecordList($dayRecords, 'record', false, false);
        }

        return parent::renderContent(HTML::div($content));
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
