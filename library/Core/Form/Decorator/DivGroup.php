<?php
class Core_Form_Decorator_DivGroup extends Zend_Form_Decorator_Abstract
{
  public function render($content)
  {
    $js="$('#extra_questions').toggle();return false;";
    //'<a href="javascript:void;" onclick="'.$js.'">Toggle Tags Visibility</a>'.
    return '<ul id="extra_questions">'.$content.'</ul>';
  }
}