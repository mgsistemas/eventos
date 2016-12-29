<?php
/**
 * ContaCorrente Active Record
 * @author  Marcelo Gomes
 */
class ContaCorrente extends TRecord
{
    const TABLENAME = 'conta_corrente';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    use SystemChangeLogTrait;
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('banco');
        parent::addAttribute('agencia');
        parent::addAttribute('conta');
        parent::addAttribute('descricao_interna');
        parent::addAttribute('login');
        parent::addAttribute('data_atualizacao');
    }


    public function get_descricao_completa()
    {
        return $this->descricao_interna . ' - Banco: ' . $this->banco . ' - Agência : ' . $this->agencia . '- Conta : ' . $this->conta;
    }
    

}
