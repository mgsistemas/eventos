<?php
class ImprimeHistoriaUsuario extends TPage
{

    private $html;
    
    public function __construct()
    {
        parent::__construct();
        
        // carrega o template
        $this->html = new THtmlRenderer('app/resources/historia_usuario.html');
        
        try {
        
            TTransaction::open('eventos');
            $cliente = new Cliente(1);
            
            $replace = array();
            $replace['razao_social']  = $cliente->razao_social;
            $replace['nome_fantasia'] = $cliente->nome_fantasia;
            $replace['apelido']       = $cliente->apelido->descricao;
            
            $this->html->enableSection('main',$replace);
            
            parent::add($this->html);
            TTransaction::close();
        
        } catch (Exception $e){
            new TMessage('error',$e->getMessage());
        }
        
    }

}
