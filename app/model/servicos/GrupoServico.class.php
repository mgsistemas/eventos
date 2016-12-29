<?php
/**
 * GrupoServico Active Record
 * @author  <your-name-here>
 */
class GrupoServico extends TRecord
{
    const TABLENAME = 'grupo_servico';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('login');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('situacao');
    }


}
