<?php

namespace Bh\Page;

class EditRecord extends EditEntry
{
    // {{{ create
    protected function create()
    {
        parent::create();

        $this->form->addDate('Start-Date', ['autocomplete' => false]);
        $this->form->addTime('Start-Time', ['autocomplete' => false]);

        $now = new \DateTime('now');

        $this->form->addDate(
            'End-Date',
            [
                'list' => [$this->toDate($now)],
                'autocomplete' => false,
            ]
        );
        $this->form->addTime(
            'End-Time',
            [
                'list' => [$this->toTime($now)],
                'autocomplete' => false,
            ]
        );
    }
    // }}}
    // {{{ populate
    protected function populate()
    {
        parent::populate();

        $values = [];

        if ($start = $this->object->getStart()) {
            $values['Start-Date'] = $this->toDate($start);
            $values['Start-Time'] = $this->toTime($start);
        }
        if (!$this->object->isRunning() && $end = $this->object->getEnd()) {
            $values['End-Date'] = $this->toDate($end);
            $values['End-Time'] = $this->toTime($end);
        }

        $this->form->populate($values);
    }
    // }}}
    // {{{ save
    protected function save()
    {
        parent::save();

        $values = $this->form->getValues();

        $this->object->setStart($this->toDateTime($values['Start-Time'], $values['Start-Date']));
        $this->object->setEnd($this->toDateTime($values['End-Time'], $values['End-Date']));

        $this->controller->editRecord($this->object);
    }
    // }}}
    // {{{ redirect
    protected function redirect()
    {
        Page::redirect('/records');
    }
    // }}}

    // {{{ instantiateObject
    protected function instantiateObject()
    {
        $this->object = new \Bh\Entity\Record($this->controller->getCurrentUser());
    }
    // }}}

    // {{{ format
    protected function format($dateTime, $format)
    {
        $result = '';

        if ($dateTime) {
            $result = $dateTime->format($format);
        }

        return $result;
    }
    // }}}
    // {{{ toDate
    protected function toDate($dateTime)
    {
        return $this->format($dateTime, 'Y-m-d');
    }
    // }}}
    // {{{ toTime
    protected function toTime($dateTime)
    {
        return $this->format($dateTime, 'H:i');
    }
    // }}}

    // {{{ toDateTime
    protected function toDateTime($time, $date)
    {
        $result = null;
        $time = trim($time);
        $date = trim($date);

        if (
            !empty($time)
            && !empty($date)
        ) {
            $result = \DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
        }

        return $result;
    }
    // }}}
}
