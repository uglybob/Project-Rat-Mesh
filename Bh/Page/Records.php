<?php

namespace Bh\Page;

class Records extends Backend
{
    public function renderContent()
    {
        return new ObjectList($this->controller->getRecords(), 'record');
    }
}
