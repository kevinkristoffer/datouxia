<?php

class Core_TestController extends Zend_Controller_Action
{

    public function test1Action()
    {
        $this->_helper->layout->setLayout('core/layout');
    }

    public function test2Action()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        header('content-type:text/html;charset=utf-8');
        ob_start();
        phpinfo();
        $info = ob_get_contents();
        $file = fopen('test2.txt', 'w');
        fwrite($file, $info);
        fclose($file);
        echo 'php info wroten...';
    }

    public function upgradeAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        header('content-type:text/html;charset=utf-8');

        echo 'System upgrade...';
    }

    public function test3Action()
    {

        $this->_helper->getHelper('viewRenderer')->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();
        header('content-type:text/html;charset=utf-8');

        /*$str = 'Hello number1 ${1}, number2 ${2}, number3 ${3}';
        $replacements = array('Tony',
            'Kenny',
            'Lucy');
        preg_match_all('/\$\{(\d+)\}/', $str, $matches);
        $patterns = array();
        for ($i = 0; $i < count($matches[0]); $i++)
            $patterns[$i] = '/\$\{' . ($i + 1) . '\}/';

        echo preg_replace($patterns, $replacements, $str);

        self::hello(1,2,3,4);
        $randomPassword=Puppy_Core_Utility_String::getRandomString(10);
        echo $this->view->translator('user_modify_pass_success',$randomPassword);*/

        $keyword='';
        echo is_null($keyword);
    }
}

?>