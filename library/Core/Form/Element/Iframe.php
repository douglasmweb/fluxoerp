<?php
class Core_Form_Element_Iframe extends Zend_Form_Element
{
    public $src = null;
    public $width = 600;
    public $height = 600;
    
    public function init()
    {
        $this->addPrefixPath('My_Decorator', 'My/Decorator/', 'decorator')
        ->addValidator('Regex', false, array('/^[a-z0-9]{6,}$/i'))
        ->addDecorator('TextItem')
        ->setAttrib('size', 30)
        ->setAttrib('maxLength', 45)
        ->setAttrib('class', 'text');
    }
    
    public function loadDefaultDecorators()
    {
        $this->addDecorator('ViewHelper')
             ->addDecorator('DisplayError')
             ->addDecorator('Label')
             ->addDecorator('HtmlTag',
                            array('tag' => 'div', 'class' => 'element'));
    }
    
    public function setSrc($array){
        $this->src = $array;
    }
    
    public function setWidth($String){
        $this->width = $String;
    }
    
    public function setHeight($String){
        $this->height = $String;
    }
    
    public function render(){
        $view = new Zend_View_Helper_Url();
        
        $out = '';
        $out .= '<iframe width="'.$this->width.'" height="'.$this->height.'" src="'.$view->url($this->src).'">';
        $out .= '</iframe>';
        
        return $out;
    }
    
    
}
