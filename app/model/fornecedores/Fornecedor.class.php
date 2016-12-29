<?php
/**
 * Fornecedor Active Record
 * @author  <your-name-here>
 */
class Fornecedor extends TRecord
{
    const TABLENAME = 'fornecedor';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $segmento;
    private $tipo_pessoa;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('razao_social');
        parent::addAttribute('nome_fantasia');
        parent::addAttribute('tipo_pessoa_id');
        parent::addAttribute('cpf_cnpj');
        parent::addAttribute('ie');
        parent::addAttribute('cep');
        parent::addAttribute('logradouro');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('estado');
        parent::addAttribute('data_cadastro');
        parent::addAttribute('segmento_id');
        parent::addAttribute('home_page');
        parent::addAttribute('regime_tributario');
        parent::addAttribute('banco');
        parent::addAttribute('agencia');
        parent::addAttribute('conta_corrente');
        parent::addAttribute('observacao');
        parent::addAttribute('tipo_conta');
        parent::addAttribute('situacao');
        parent::addAttribute('login');
        parent::addAttribute('data_atualizacao');
    }

    
    /**
     * Method set_segmento
     * Sample of usage: $fornecedor->segmento = $object;
     * @param $object Instance of Segmento
     */
    public function set_segmento(Segmento $object)
    {
        $this->segmento = $object;
        $this->segmento_id = $object->id;
    }
    
    /**
     * Method get_segmento
     * Sample of usage: $fornecedor->segmento->attribute;
     * @returns Segmento instance
     */
    public function get_segmento()
    {
        // loads the associated object
        if (empty($this->segmento))
            $this->segmento = new Segmento($this->segmento_id);
    
        // returns the associated object
        return $this->segmento;
    }
    
    
    /**
     * Method set_tipo_pessoa
     * Sample of usage: $fornecedor->tipo_pessoa = $object;
     * @param $object Instance of TipoPessoa
     */
    public function set_tipo_pessoa(TipoPessoa $object)
    {
        $this->tipo_pessoa = $object;
        $this->tipo_pessoa_id = $object->id;
    }
    
    /**
     * Method get_tipo_pessoa
     * Sample of usage: $fornecedor->tipo_pessoa->attribute;
     * @returns TipoPessoa instance
     */
    public function get_tipo_pessoa()
    {
        // loads the associated object
        if (empty($this->tipo_pessoa))
            $this->tipo_pessoa = new TipoPessoa($this->tipo_pessoa_id);
    
        // returns the associated object
        return $this->tipo_pessoa;
    }
    


}
