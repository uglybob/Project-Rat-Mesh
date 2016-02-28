<?php

namespace Bh\Entity;

class Entry extends PrivateEntity
{
    // {{{ variables
    protected $text;
    protected $tags;
    protected $category;
    protected $activity;
    // }}}

    // {{{ constructor
    public function __construct($user)
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($user);
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
    // {{{ setText
    public function setText($newText)
    {
        $this->text = ($newText) ? $newText : '';
    }
    // }}}
    // {{{ addTag
    public function addTag($tag)
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }
    // }}}
    // {{{ removeTag
    public function removeTag($tag)
    {
        $this->tags->removeElement($tag);
    }
    // }}}
}
