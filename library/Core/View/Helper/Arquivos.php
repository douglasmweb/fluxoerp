<?php
class Core_View_Helper_Arquivos
{
    function _findexts($filename)
    {
        $filename = strtolower($filename);
        $exts = preg_split("[/\\.]", $filename);
        $n = count($exts) - 1;
        $exts = explode('.', $exts[$n]);
        $c = count($exts);
        $exts = $exts[$c - 1];
        return $exts;
    }
}
