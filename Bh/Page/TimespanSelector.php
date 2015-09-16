<?php

namespace Bh\Page;

class TimespanSelector
{
    // {{{ constructor
    public function __construct()
    {
        $uriParts = explode('/', $_SERVER['REQUEST_URI']);
        $this->uri = $uriParts[1];
        $this->unit = isset($uriParts[2]) ? (int) $uriParts[2] : 0;
        $this->offset = isset($uriParts[3]) ? (int) $uriParts[3] : 0;

        if ($this->unit === 0) {
            $this->start = new \Datetime();
            $this->start->setTimestamp(strtotime('previous monday'));
            if ($this->offset >= 0) {
                $this->start->add(new \DateInterval('P' . ($this->offset * 7) . 'D'));
            } else {
                $offset = -$this->offset;
                $this->start->sub(new \DateInterval('P' . ($offset * 7) . 'D'));
            }
        }
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
        if ($this->unit === 0) {
            $end = clone $this->start;
            $end->add(new \DateInterval('P7D'));
            return $end;
        }
    }
    // }}}
    // {{{ toString
    public function __toString()
    {
        $uri = $this->uri;
        $unit = $this->unit;
        $offset = $this->offset;

        $list = [
            '<<' => "/$uri/$unit/" . ($offset - 1),
            'week' => "/$uri/0/0",
            'month' => "/$uri/1/0",
            'year' => "/$uri/2/0",
            '>>' => "/$uri/$unit/" . ($offset + 1),
        ];

        return HTML::menu($list);
    }
    // }}}
}
