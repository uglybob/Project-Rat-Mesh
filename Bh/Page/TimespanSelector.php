<?php

namespace Bh\Page;

class TimespanSelector
{
    protected $start;
    protected $end;

    protected $uri;
    protected $unit;
    protected $units = ['week', 'month', 'year', 'custom'];
    protected $offset;

    // {{{ constructor
    public function __construct()
    {
        $uriParts = explode('/', $_SERVER['REQUEST_URI']);
        $this->uri = $uriParts[1];

        $unitIndex = isset($uriParts[2]) ? (int) $uriParts[2] : 0;
        $this->unit = $this->units[$unitIndex];

        $this->offset = isset($uriParts[3]) ? (int) $uriParts[3] : 0;
        $offsetString = ($this->offset >= 0) ? '+' . $this->offset : $this->offset;

        if ($this->unit == 'week') {
            $this->start = new \Datetime('this week');
            $this->start->setTime(0,0);
        } else if ($this->unit == 'month') {
            $this->start = new \Datetime(date('Y') . '-' . date('m') . '-01 00:00:00');
        } else if ($this->unit == 'year') {
            $this->start = new \Datetime(date('Y') . '-01-01 00:00:00');
        }

        $this->start->modify($offsetString . ' ' . $this->unit);
    }
    // }}}

    // {{{ getStart
    public function getStart()
    {
        return $this->start;
    }
    // }}}
    // {{{ getEnd
    public function getEnd()
    {
        $end = clone $this->start;
        $end->modify('+1 ' . $this->unit);

        return $end;
    }
    // }}}
    // {{{ toString
    public function __toString()
    {
        $uri = $this->uri;
        $unit = array_search($this->unit, $this->units);
        $offset = $this->offset;

        $list = [
            '<<' => "/$uri/$unit/" . ($offset - 1),
            'week' => "/$uri/0/0",
            'month' => "/$uri/1/0",
            'year' => "/$uri/2/0",
            '>>' => "/$uri/$unit/" . ($offset + 1),
        ];

        return HTML::div(['.row', '.timespan'],
            HTML::menu($list) .
            HTML::div(['.length'], $this->getStart()->format('Y-m-d') . ' - ' . $this->getEnd()->format('Y-m-d'))
        );
    }
    // }}}
}
