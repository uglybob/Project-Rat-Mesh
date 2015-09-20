<?php

namespace Bh\Entity;

class Record extends Entry
{
    protected $start;
    protected $length;
    protected $tags;

    // {{{ constructor
    public function __construct($user)
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->start = new \DateTime('now');
        $this->length = null;

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
        $endDateTime = $this->formatDateTime($end);

        if (is_null($end)) {
            $this->length = null;
        } else {
            $this->length = abs($end->getTimestamp() - $this->start->getTimestamp());
        }
    }
    // }}}
    // {{{ getEnd
    public function getEnd()
    {
        $end = null;

        if (!is_null($this->length)) {
            $end = clone $this->start;
            $end->modify('+' . $this->getLength() . ' seconds');
        }

        return $end;
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

    // {{{ getTags
    public function getTags()
    {
        return $this->tags->toArray();
    }
    // }}}
    // {{{ setTags
    public function setTags($newTags)
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($newTags as $newTag) {
            $this->addTag($newTag);
        }
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
}
