<?php

namespace Bh\Page;

class Totals extends PRMBackend
{
    // {{{ renderContent
    public function renderContent()
    {
        $timespan = new TimespanSelector();
        $content = $timespan;

        $start = $timespan->getStart();
        $end = $timespan->getEnd();

        if ($this->controller->getCurrentUser()) {
            $content .= $this->attributeList('categories', $this->controller->getCategoriesLengths($start, $end));
            $content .= $this->attributeList('activities', $this->controller->getActivitiesLengths($start, $end));
            $content .= $this->attributeList('tags', $this->controller->getTagsLengths($start, $end));
        }

        return parent::renderContent($content);
    }
    // }}}
    // {{{ hookTitle
    public function hookTitle()
    {
        return 'Totals';
    }
    // }}}

    // {{{ attributeList
    protected function attributeList($type, $attributes)
    {
        $list = '';

        foreach ($attributes as $attribute) {
            $length = (int) $attribute['length'];
            $hours = $length / 3600;
            $lengthString = round($hours, 1);

            $list .= HTML::div(['.row'],
                HTML::div(['.attribute'], $attribute['name']) .
                HTML::div(['.length'], $lengthString)
            );
        }

        return HTML::div(
            ['.attributeList', ".$type"],
            HTML::div(['.title'], ucfirst($type)) . $list
        );
    }
    // }}}
}
