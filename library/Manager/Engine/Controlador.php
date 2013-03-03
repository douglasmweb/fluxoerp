<?php
class Manager_Engine_Controlador extends Manager_File
{
    function __construct($controlador, $modulo)
    {
        $this->setModulo($modulo);
        $this->setControlador($controlador);
    }

    function criar($default=false)
    {
        $controllerPath = $this->getControllerPath();
        if (!$this->checkControllerExiste())
        {
            $Classe = new Zend_CodeGenerator_Php_Class();
            $Classe->setName(ucfirst($this->getModulo()) . '_' . $this->getControllerName() . 'Controller');
            $Classe->setExtendedClass('Zend_Controller_Action');
            $Nova = new Zend_CodeGenerator_Php_File();
            $Nova->setClass($Classe);

            
            $transfer = file_put_contents($controllerPath, $Nova->generate());
            if($transfer){
                $acao = new Manager_Engine_Acao("init",$this->getControlador(),$this->getModulo());
                $acao->criar();
                $acao = new Manager_Engine_Acao("index",$this->getControlador(),$this->getModulo());
                $acao->criar();
                return true;
            }else{
                return false;
            }
        } else
        {
            throw new Exception("Já existe um controlador '{$this->_controlador}' no módulo {$this->_modulo}", 001);
            return false;
        }
    }
    
    function editar($data)
    {
        if ($this->checkControllerExiste())
        {
            $controllerPath = $this->getControllerPath();
            $ClasseAntiga = Zend_CodeGenerator_Php_File::fromReflectedFileName($controllerPath, true, true);
            $MetodosAntigos = $ClasseAntiga->getClass()->getMethods();
            $novos_metodos = array();
            
            foreach($MetodosAntigos as $k => $v)
            {
                $novos_metodos[$k] = $v;
            }
            
            $NovaClasse = new Zend_CodeGenerator_Php_Class();
            $NovaClasse->setName($ClasseAntiga->getClass()->getName());
            if($data['crud'] > 0)
                $NovaClasse->setExtendedClass('Core_Controller_Action');
            else
                $NovaClasse->setExtendedClass('Zend_Controller_Action');
            $NovaClasse->setMethods($novos_metodos);
            
            $Nova = new Zend_CodeGenerator_Php_File();
            $Nova->setClass($NovaClasse);
            
            $transfer = file_put_contents($controllerPath, $Nova->generate());
            if($transfer){
                $this->CopyScripts($this->getPath().'/modules/creator/views/scripts/padrao/',$this->getScriptControllerPath());
                return true;
            }else{
                return false;
            }
        }
    }

    function deletar()
    {
        $retorno = false;
        $pathScripts = $this->getScriptControllerPath();
        $controllerPath = $this->getControllerPath();
        
        $model = new Manager_Engine_Model($this->getModulo(),$this->getControlador());
        $model->deletar();
        
        $form = new Manager_Engine_Form($this->getModulo(),$this->getControlador());
        $form->deletar();
        
        if ($pathScripts)
        {
            //var_dump($controllerPath);die;
            $retorno = $this->delTree($pathScripts);
            $retorno = unlink($controllerPath);
        } else
        {
            throw new Exception('Você não pode deletar o módulo default.');
        }
        return $retorno;
    }
}
