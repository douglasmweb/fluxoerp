<?
class Core_Layout_Menu extends Manager_File
{
    protected $_navigation = null;
    protected $_secao;

    function __construct()
    {
        $this->_secao = new Creator_Model_Modulo();
        $result = $this->_secao->fetchAll();

        $this->_controlador = new Creator_Model_Controlador();

        $file = $this->getPath() . "/configs/navigation.xml";

        $dom = new DomDocument('1.0', 'iso-8859-1');
        if (file_exists($file))
            unlink($file);

        $dom->formatOutput = true;
        $DOMDocument->preserveWhiteSpace = false;

        $configdata = $dom->createElement('configdata');
        $configdata = $dom->appendChild($configdata);
        $nav = $dom->createElement('nav');
        $nav = $configdata->appendChild($nav);
        foreach ($result as $v)
        {
            $resultControladores = $v->findDependentRowset('Creator_Model_Controlador', 'Modulo');

            $node = $dom->createElement($v->tabela);
            $node = $nav->appendChild($node);

            $label = $dom->createElement('label', $v->titulo);
            $uri = $dom->createElement('uri', '#');

            if ($resultControladores->count() > 0)
            {
                $pages = $dom->createElement('pages');

                foreach ($resultControladores as $vControlador)
                {
                    $subnode = $dom->createElement($vControlador->tabela);
                    $sublabel = $dom->createElement('label', $vControlador->titulo);
                    $submodulo = $dom->createElement('module', $v->tabela);
                    $subcontro = $dom->createElement('controller', $vControlador->tabela);
                    $subaction = $dom->createElement('action', 'index');
                    $suburi = $dom->createElement('uri', '#');
                    $subnode->appendChild($sublabel);
                    $subnode->appendChild($submodulo);
                    $subnode->appendChild($subcontro);
                    $subnode->appendChild($subaction);
                    $subnode->appendChild($suburi);
                    $pages->appendChild($subnode);
                }
                $node->appendChild($pages);
            }

            $node->appendChild($label);
            $node->appendChild($uri);
            //$node->appendChild($pages);

        }

        $dom->normalizeDocument();
        $dom->save($file);
        var_dump($dom->saveHTML());

    }
}
