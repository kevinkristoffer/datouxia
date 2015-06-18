<?php

class Puppy_Modules_Core_Widgets_Usercounter_Widget extends Puppy_Core_Widget{
	
	/* (non-PHPdoc)
	 * @see Puppy_Core_Widget::_prepareDisplay()
	 */
	protected function _prepareDisplay()
	{
		// TODO Auto-generated method stub
		$this->_view->assign('widgetMsg','This is a test widget');
	}
}