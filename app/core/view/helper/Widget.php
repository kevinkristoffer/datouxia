<?php

class Puppy_Core_View_Helper_Widget extends Zend_View_Helper_Abstract
{

    public function widget($module, $name, array $params = array())
    {
        $module = strtolower($module);
        $name = strtolower($name);

        // TODO: Should we add cache control

        $widgetClass = 'Puppy_Modules_' . $module . '_Widgets_' . $name . '_Widget';

        if (!class_exists($widgetClass)) {
            // TODO: Should we inform to user that the widget does not exist
            return '';
        }

        $widget = new $widgetClass ($module, $name);
        return $widget->display($params);
    }
}