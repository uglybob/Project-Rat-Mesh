<?php

namespace Bh\Entity;

class Record extends Entry
{
    protected $start;
    protected $end;
    protected $tags;

    // {{{ constructor
    public function __construct($user)
    {
        $this->start = new \DateTime('now');
        $this->end = null;

        parent::__construct($user);
    }
    // }}}

    // {{{ setStart
    public function setStart($start)
    {
        $this->start = $this->formatDateTime($start);
    }
    // }}}
    // {{{ setEnd
    public function setEnd($end)
    {
        $this->end = $this->formatDateTime($end);
    }
    // }}}
    // {{{ formatDateTime
    public function formatDateTime($input)
    {
        if (is_a($input, 'DateTime')) {
            $output = $input;
        } elseif (is_null($input)) {
            $output = null;
        } else {
            $output = new \DateTime();
            $output->setTimestamp($input);
        }

        return $output;
    }
    // }}}

    // {{{ addTag
    public function addTag($tag)
    {
        $this->tags[] = $tag;
    }
    // }}}
    // {{{ removeTag
    public function removeTag($tag)
    {
        $this->tags->removeElement($tag);
    }
    // }}}

    // {{{ getRow
    public function getRow()
    {
        $length = null;

        if ($this->end) {
            $length = $this->end->diff($this->start)->format('%H:%I');
        }

        return [
            $this->getStart()->format('H:i d.m.Y'),
            $this->getActivity(),
            '@',
            $this->getCategory(),
            implode(', ',$this->getTags()),
            $length,
        ];
    }
    // }}}
}
