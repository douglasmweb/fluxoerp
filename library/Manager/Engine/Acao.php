<?php
class Manager_Engine_Acao extends Manager_File
{
    public function __construct($acao,$controlador,$modulo)
    {
        $this->setAcao($acao);
        $this->setModulo($modulo);
        $this->setControlador($controlador);
    }
    
    public function criar($actionName=null, $body = '        /* action body */')
    {
        if (!$this->checkControllerExiste())
        {
            return false;
        }
        
        $ScriptPath = $this->getScriptControllerPath();
        
        if(!$actionName)
            $actionName = $this->getAcao();
        
        $controllerPath = $this->getControllerPath();
        $controllerCodeGenFile = Zend_CodeGenerator_Php_File::fromReflectedFileName($controllerPath, true, true);
        $controllerCodeGenFile->getClass()->setMethod(array(
            'name' => ($actionName == 'init')?$actionName:$actionName . 'Action', 
            'body' => $body));

        $transfer = file_put_contents($controllerPath, $controllerCodeGenFile->generate());
        if($transfer && $actionName != 'init'){
            mkdir($ScriptPath,0777);
            file_put_contents($ScriptPath . '/' . $actionName . '.phtml',"action {$actionName} foi criada com sucesso!");   
            return true;
        }
    }

    public function criarAcao($actionName=null, $body = '        /* action body */')
    {
        if (!$this->checkControllerExiste())
        {
            return false;
        }
        
        if($actionName)
            $actionName = $this->getAcao();
        
        $controllerPath = $this->getControllerPath();
        $controllerCodeGenFile = Zend_CodeGenerator_Php_File::fromReflectedFileName($controllerPath, true, true);
        $controllerCodeGenFile->getClass()->setMethod(array('name' => $actionName . 'Action', 'body' => $body));

        $transfer = file_put_contents($controllerPath, $controllerCodeGenFile->generate());
        if($transfer)
            file_put_contents($this->getModulePath().'/views/scripts/index/'.$actionName.'.phtml',"");
            
        return true;
    }
    
    

    public function deletarAcao($actionName)
    {
        if (!$this->checkControllerExiste())
            return false;

        $controllerPath = $this->getControllerPath();
        $Antiga = Zend_CodeGenerator_Php_File::fromReflectedFileName($controllerPath, true, true);
        unlink($controllerPath);

        $MetodosNovos = null;

        foreach ($Antiga->getClass()->getMethods() as $Chave => $Metodo)
        {
            if ($Chave != $actionName . 'Action')
                $MetodosNovos[] = $Metodo;
        }

        $Classe = new Zend_CodeGenerator_Php_Class();
        $Classe->setName($Antiga->getClass()->getName());
        $Classe->setExtendedClass($Antiga->getClass()->getExtendedClass());

        $Nova = new Zend_CodeGenerator_Php_File();
        $Nova->setClass($Classe);
        $Nova->getClass()->setMethods($MetodosNovos);

        $transfer = file_put_contents($controllerPath, $Nova->generate());
        if($transfer)
            unlink($this->getModulePath().'/views/scripts/index/'.$actionName.'.phtml');
        
        return true;
    }
}
