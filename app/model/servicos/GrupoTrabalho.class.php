<?php
/**
 * GrupoTrabalho Active Record
 * @author  <your-name-here>
 */
class GrupoTrabalho extends TRecord
{
    const TABLENAME = 'grupo_trabalho';
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
        parent::addAttribute('ordem');
    }


}
