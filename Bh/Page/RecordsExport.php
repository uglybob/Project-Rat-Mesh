<?php

namespace Bh\Page;

class RecordsExport extends PRMBackend
{
    // {{{ renderContent
    public function renderContent()
    {
        $content = '';
        $list = '';
        $records = $this->splitRecords($this->controller->getRecords());

        $totalLength = 0;
        foreach ($records as $day => $dayRecords) {
            $filtered = [];
            $totalDayLength = 0;

            foreach ($dayRecords as $dayRecord) {
                foreach($dayRecord->getTags() as $tag) {
                    if ($tag->getName() == $this->getPath(1)) {
                        $filtered[] = $dayRecord;
                        $length = $dayRecord->getLength();

                        if (is_null($length)) {
                            $length = abs(time() - $dayRecord->getStart()->getTimestamp());
                        }
                        $totalDayLength += $length;
                    }
                }
            }

            if (!empty($filtered)) {
                $totalLength += $totalDayLength;

                $lengthString = round($totalDayLength / 3600, 2);

                $list .= HTML::div(['.title'],
                    HTML::div(['.date'], $day) .
                    HTML::div(['.length'], $lengthString)
                );
                $list .= new RecordList($filtered, 'record', false, false);
            }
        }

        $content .= HTML::div(['.title', '.total'],
            HTML::div('total') .
            HTML::div(['.length'], round($totalLength / 3600, 2))
        );

        $content .= $list;

        return HTML::div($content);
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
