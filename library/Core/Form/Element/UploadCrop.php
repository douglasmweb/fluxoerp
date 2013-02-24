<?php
require_once 'Zend/Form/Element/Xhtml.php';

class Core_Form_Element_UploadCrop extends Zend_Form_Element_Xhtml
{
    public $helper = 'formLabel';
    public $src;
    public $value;
    public $textoLink;
    public $caminhoComponent;
    protected $max_width;
    protected $thumb_width;
    protected $thumb_height;
    protected $max_file_size;
    
    public function setImagemBotao($path)
    {
        $this->src = (string) $path;
        return $this;
    }

    public function getImagemBotao()
    {
        return $this->src;
    }
    
    
    public function setTextoLink($texto)
    {
        $this->textoLink = (string) $texto;
        return $this;
    }
    

    
    public function getTextoLink()
    {
        return "
<script type='text/javascript' charset='utf-8'>
  $(document).ready(function(){
        $(\"a[rel^='prettyPhoto']\").prettyPhoto({
            deeplinking:false,
            callback: function(){
                
                //alert('teste');
            }
        });
  });
</script>

        <a href='".$this->_view->url(array(
        'module' => 'default',
        'controller' => 'upload-images',
        'action' => 'upload',
        'field' => $this->getName(),
        'max_file_size'=> $this->max_file_size,
        'max_width'=> $this->max_width,
        'thumb_width'=> $this->thumb_width,
        'thumb_height'=> $this->thumb_height
        ))."?iframe=true&width=990&height=430
        &field=".$this->getName()."
        
        ' rel='prettyPhoto[iframes]'>" . 
                (($this->textoLink!=null)?$this->textoLink:$this->getImagemBotao()) . "</a>";
    }
    
    public function setCaminhoComponent($caminho)
    {
        if(!(substr($caminho,0,1) == '/')){
            $caminho = '/'.$caminho;
        }
        $this->caminhoComponent = $caminho;
        return $this;
    }
    
    public function getCaminhoComponent()
    {
        return $this->caminhoComponent;
    }


    
    function render()
    {
        $nome_elemento = $this->getName();
        
        $headLink = new Zend_View_Helper_HeadLink();
        $headScript = new Zend_View_Helper_HeadScript();
        $headScript->appendFile('/javascript/prettyphoto/js/jquery.prettyPhoto.js');
        $headLink->appendStylesheet('/javascript/prettyphoto/css/prettyPhoto.css');
        
        //var_dump(get_class_methods(),$append->headLink());
        $img = "<img src='/images/upload/thumb_{$this->getValue()}' id='img_{$nome_elemento}' />";
        
        $hidden = "<input type='hidden' name='{$nome_elemento}' id='{$nome_elemento}' value='".$this->getValue()."' />";
        $botao = $this->getTextoLink();
        $content = $img."$hidden".$botao;
        foreach ($this->getDecorators() as $decorator) {
            $decorator->setElement($this);
            $content = $decorator->render($content);
        }
        return $content;
        
    }/**/
    
    
    public function setMaxWidth($valor)
    {
        $this->max_width = $valor;
        return $this;
    }
    
    public function setThumbWidth($valor)
    {
        $this->thumb_width = $valor;
        return $this;
    }
    
    public function setThumbHeight($valor)
    {
        $this->thumb_height = $valor;
        return $this;
    }
    
    public function setMaxFileSize($valor)
    {
        $this->max_file_size = $valor;
        return $this;
    }
    
    public function getMaxWidth()
    {
        return $this->max_width;
    }
    
    public function getThumbWidth()
    {
        return $this->thumb_width;
    }
    
    public function getThumbHeight()
    {
        return $this->thumb_height;
    }
    
    public function getMaxFileSize()
    {
        return $this->max_file_size;
    }
}
