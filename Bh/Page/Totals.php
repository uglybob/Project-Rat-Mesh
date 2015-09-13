<?php

namespace Bh\Page;

class Totals extends PRMBackend
{
    // {{{ renderContent
    public function renderContent()
    {
        $content = '';

        if ($this->controller->getCurrentUser()) {
            $content .= $this->attributeList('categories', $this->controller->getCategoriesLengths());
            $content .= $this->attributeList('activities', $this->controller->getActivitiesLengths());
            $content .= $this->attributeList('tags', $this->controller->getTagsLengths());
        }

        return parent::renderContent($content);
    }
    // }}}

    protected function attributeList($type, $attributes)
    {
        $list = '';

        foreach ($attributes as $attribute) {
            $length = (int) $attribute['length'];
            $hours = $length / 3600;
            $lengthString = round($hours, 1);

            $list .= HTML::div(
                HTML::div(['class' => 'title'], ucfirst($type)) .
                HTML::div(['class' => 'attribute'], $attribute['name']) .
                HTML::div(['class' => 'length'], $lengthString)
            );
        }

        return HTML::div(
            [
                'class' => 'attributeList',
                'class' => $type,
            ],
            $list
        );
    }
}
