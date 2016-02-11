<?php

namespace Bh\Page;

class TimespanSelector
{
    protected $units = ['week', 'month', 'year', 'custom'];

    // {{{ constructor
    public function __construct()
    {
        $this->form = new \Depage\HtmlForm\HtmlForm('timespan', ['label' => 'select']);

        $unit = $this->form->addSingle(
            'Unit',
            [
                'list' => $this->units,
                'skin' => 'select',
            ]
        )->setValue(array_search($this->getUnit(), $this->units));
        $this->form->addDate('Start')->setValue($this->getStart()->format('Y-m-d'));
        $this->form->addDate('End')->setValue($this->getEnd()->format('Y-m-d'));

        $this->form->process();
    }
    // }}}

    // {{{ getUnit
    public function getUnit()
    {
        $values = $this->form->getValues();
        $unitInt = isset($values['Unit']) ? $values['Unit'] : 0;
        $unit = $this->units[$unitInt];

        return $unit;
    }
    // }}}
    // {{{ getStart
    public function getStart()
    {
        $values = $this->form->getValues();

        if (isset($values['Start'])) {
            $start = new \Datetime($values['Start']);
        } else {
            if ($this->getUnit() == 'week') {
                $start = new \Datetime('this week');
                $start->setTime(0,0);
            } else if ($this->getUnit() == 'month') {
                $start = new \Datetime(date('Y') . '-' . date('m') . '-01 00:00:00');
            } else if ($this->getUnit() == 'year') {
                $start = new \Datetime(date('Y') . '-01-01 00:00:00');
            }
        }

        return $start;
    }
    // }}}
    // {{{ getEnd
    public function getEnd()
    {
        $values = $this->form->getValues();

        if (
            ($this->getUnit() == 'custom')
            && (isset($values['End']))
        ) {
            $end = new \Datetime($values['End']);
        } else {
            $end = clone $this->getStart();
            $end->modify('+1 ' . $this->getUnit());
        }

        return $end;
    }
    // }}}

    // {{{ toString
    public function __toString()
    {
        return $this->form->__toString();
    }
    // }}}
}
