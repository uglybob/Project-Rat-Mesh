<?php

namespace Bh\Page;

class EditRecord extends EditForm
{
    // {{{ create
    protected function create()
    {
        $this->form->addText(
            'Activity',
            ['list' => $this->controller->getActivities()]
        );
        $this->form->addText(
            'Category',
            ['list' => $this->controller->getCategories()]
        )->setRequired();

        $this->form->addText('Tags');

        $this->form->addDate('Start-Date');
        $this->form->addTime('Start-Time');

        $this->form->addDate('End-Date');
        $this->form->addTime('End-Time');
    }
    // }}}
    // {{{ populate
    protected function populate()
    {
        $values = [
            'Activity' => $this->object->getActivity(),
            'Category' => $this->object->getCategory(),
            'Tags' => implode(', ', $this->object->getTags()),
        ];

        if ($start = $this->object->getStart()) {
            $values['Start-Date'] = $this->toDate($start);
            $values['Start-Time'] = $this->toTime($start);
        }
        if ($end = $this->object->getEnd()) {
            $values['End-Date'] = $this->toDate($end);
            $values['End-Time'] = $this->toTime($end);
        }

        $this->form->populate($values);
    }
    // }}}
    // {{{ save
    protected function save()
    {
        $values = $this->form->getValues();

        $this->object->setStart($this->toDateTime($values['Start-Time'], $values['Start-Date']));
        $this->object->setEnd($this->toDateTime($values['End-Time'], $values['End-Date']));
        $this->object->setActivity($values['Activity']);
        $this->object->setCategory($values['Category']);
        $this->object->setTags(explode(',', $values['Tags']));

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
        return $this->format($dateTime, 'd.m.Y');
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
            $result = \DateTime::createFromFormat('H:i d.m.Y', $time . ' ' . $date);
        }

        return $result;
    }
    // }}}
}
