<?php
/**
 * FornecedorContato Active Record
 * @author  <your-name-here>
 */
class FornecedorContato extends TRecord
{
    const TABLENAME = 'fornecedor_contatos';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $departamento;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('fornecedor_id');
        parent::addAttribute('nome');
        parent::addAttribute('departamento_id');
        parent::addAttribute('email');
        parent::addAttribute('telefone');
        parent::addAttribute('celular');
        parent::addAttribute('diames');
        parent::addAttribute('login');
        parent::addAttribute('situacao');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    
    /**
     * Method set_departamento
     * Sample of usage: $fornecedor_contato->departamento = $object;
     * @param $object Instance of Departamento
     */
    public function set_departamento(Departamento $object)
    {
        $this->departamento = $object;
        $this->departamento_id = $object->id;
    }
    
    /**
     * Method get_departamento
     * Sample of usage: $fornecedor_contato->departamento->attribute;
     * @returns Departamento instance
     */
    public function get_departamento()
    {
        // loads the associated object
        if (empty($this->departamento))
            $this->departamento = new Departamento($this->departamento_id);
    
        // returns the associated object
        return $this->departamento;
    }
    


}
