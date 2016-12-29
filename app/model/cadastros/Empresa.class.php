<?php
/**
 * Empresa Active Record
 * @author  Marcelo Gomes
 */
class Empresa extends TRecord
{
    const TABLENAME = 'empresa';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $conta_corrente;
    
    use SystemChangeLogTrait;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cnpj');
        parent::addAttribute('ie');
        parent::addAttribute('nome');
        parent::addAttribute('cep');
        parent::addAttribute('logradouro');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('estado');
        parent::addAttribute('telefone');
        parent::addAttribute('email');
        parent::addAttribute('conta_corrente_id');
        parent::addAttribute('imposto');
        parent::addAttribute('brindes');
        parent::addAttribute('exibe_brindes');
        parent::addAttribute('login');
        parent::addAttribute('data_atualizacao');
    }

    
    /**
     * Method set_conta_corrente
     * Sample of usage: $empresa->conta_corrente = $object;
     * @param $object Instance of ContaCorrente
     */
    public function set_conta_corrente(ContaCorrente $object)
    {
        $this->conta_corrente = $object;
        $this->conta_corrente_id = $object->id;
    }
    
    /**
     * Method get_conta_corrente
     * Sample of usage: $empresa->conta_corrente->attribute;
     * @returns ContaCorrente instance
     */
    public function get_conta_corrente()
    {
        // loads the associated object
        if (empty($this->conta_corrente))
            $this->conta_corrente = new ContaCorrente($this->conta_corrente_id);
    
        // returns the associated object
        return $this->conta_corrente;
    }
    


}
