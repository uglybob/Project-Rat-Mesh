<?php

namespace Bh\Page;

class Records extends PRMBackend
{
    public function renderContent()
    {
        return parent::renderContent(new ObjectList($this->controller->getRecords(), 'record'));
    }
}
