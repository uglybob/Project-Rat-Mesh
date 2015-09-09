<?php

namespace Bh\Page;

class Records extends PRMBackend
{
    // {{{ renderContent
    public function renderContent()
    {
        $content = new RecordList($this->controller->getRecords(), 'record');

        return parent::renderContent($content);
    }
    // }}}
}
