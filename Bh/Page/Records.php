<?php

namespace Bh\Page;

class Records extends ObjectList
{
    public function renderContent()
    {
        return $this->renderList($this->controller->getRecords(), 'Record');
    }
    protected function loadProperties($record)
    {
        return [
            $record->getStart()->format('H:i d.m.Y'),
            $record->getActivity(),
            '@',
            $record->getCategory(),
            implode(', ',$record->getTags()),
        ];
    }
}
