<?php

namespace Bh\Entity;

class Record extends PrivateEntity
{
    protected $entry;
    protected $start;
    protected $end;

    // {{{ constructor
    public function __construct(User $user, Entry $entry, \DateTime $start = null, \DateTime $end = null)
    {
        parent::__construct($user);

        $this->entry = $entry;

        if (is_null($start)) {
            $this->start = new \DateTime('now');
        }

        $this->end = $end;
    }
    // }}}
}
