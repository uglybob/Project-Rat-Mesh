<?php

namespace Bh\Page;

class Todos extends PRMBackend
{
    // {{{ hookConstructor
    protected function hookConstructor()
    {
        $this->stylesheets[] = '/vendor/depage/htmlform/lib/css/depage-forms.css';
        $this->title = 'Todos';
    }
    // }}}

    // {{{ renderContent
    public function renderContent()
    {
        $todos = $this->controller->getTodos();

        $elements = [];
        $elements['Category'] = $this->keys($this->controller->getCategories());
        $elements['Activity'] = $this->keys($this->controller->getActivities());
        $elements['Tag'] = $this->keys($this->controller->getTags());

        $filter = new Filter($elements);
        $content = $filter;
        $filtered = [];

        foreach($todos as $todo) {
            $inTags = false;
            foreach($todo->getTags() as $tag) {
                if (in_array($tag->__toString(), $filter->getElements('Tag'))) {
                    $inTags = true;
                    break;
                }
            }
            if (
                (in_array($todo->getCategory(), $filter->getElements('Category')) || empty($filter->getElements('Category')))
                && (in_array($todo->getActivity(), $filter->getElements('Activity')) || empty($filter->getElements('Activity')))
                && ($inTags || empty($filter->getElements('Tag')))
            ) {
                $filtered[] = $todo;
            }
        }

        $content .= new TodoList($filtered, 'todo', true, false);

        return HTML::div($content);
    }
    // }}}

    protected function keys($array)
    {
        $newArray = [];
        foreach($array as $value) {
            $newArray[$value->__toString()] = $value->__toString();
        }

        return $newArray;
    }
}
