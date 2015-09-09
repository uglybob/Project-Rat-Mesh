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
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
    // {{{ getLength
    public function getLength()
    {
        $tempEnd = (is_null($this->getEnd())) ? new \DateTime('now') : $this->getEnd();
        return $tempEnd->diff($this->getStart());
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
