<?php

namespace Bh\Page;

class Totals extends PRMBackend
{
    // {{{ hookConstructor
    protected function hookConstructor()
    {
        $this->stylesheets[] = '/vendor/depage/htmlform/lib/css/depage-forms.css';
        $this->title = 'Totals';
    }
    // }}}
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

        return $content;
    }
    // }}}

    // {{{ attributeList
    protected function attributeList($type, $attributes)
    {
        $list = '';
        $maxLength = (isset($attributes[0]['length'])) ? $attributes[0]['length'] : null;

        foreach ($attributes as $attribute) {
            $length = (int) $attribute['length'];
            $hours = $length / 3600;
            $lengthString = round($hours, 1);

            $percentage = round(600 * $length / $maxLength);

            $list .= HTML::div(['.totalRow'],
                HTML::div(['.bar', 'style' => "width : {$percentage}px"])
                . HTML::div(['.row'],
                    HTML::div(['.attribute'], $attribute['name'])
                    . HTML::div(['.length'], $lengthString)
                )
            );
        }

        return HTML::div(
            ['.attributeList', ".$type"],
            HTML::div(
                ['.title'], ucfirst($type)
            )
            . $list
        );
    }
    // }}}
}
