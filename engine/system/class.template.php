<?php

class Template
{
    private $search = array();
    private $replace = array();
    private $content;

    public function __construct($path)
    {
        if(!file_exists($path))
            throw new NoTemplateFile($path.' plik nie istnieje!');
        
        $this->content = file_get_contents($path);
    }

    public function addSearchReplace($s, $r = null)
    {
        $this->search[] = '{%'.$s.'%}';
        $this->replace[] = $r;
    }

    public function getContent()
    {
        return str_replace($this->search, $this->replace, $this->content);
    }
    
    public function clearSearchReplace(){
        unset($this->search);
         unset($this->replace);
    }
}

?>
