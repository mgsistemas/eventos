<?php
/**
 * Cliente Active Record
 * @author  <your-name-here>
 */
class Cliente extends TRecord
{
    const TABLENAME = 'clientes';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $tipo_pessoa;
    private $apelido;
    
    use SystemChangeLogTrait;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('razao_social');
        parent::addAttribute('nome_fantasia');
        parent::addAttribute('tipo_pessoa_id');
        parent::addAttribute('tipopessoa');
        parent::addAttribute('cpf_cnpj');
        parent::addAttribute('segmento_id');
        parent::addAttribute('ie');
        parent::addAttribute('cep');
        parent::addAttribute('logradouro');
        parent::addAttribute('complemento');
        parent::addAttribute('bairro');
        parent::addAttribute('cidade');
        parent::addAttribute('estado');
        parent::addAttribute('observacao');
        parent::addAttribute('situacao');
        parent::addAttribute('boleto');
        parent::addAttribute('pedidocompra');
        parent::addAttribute('prazopagto');
        parent::addAttribute('regrafaturamento');
        parent::addAttribute('observacaofaturamento');
        parent::addAttribute('login');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('apelido_id');
    }

    
    /**
     * Method set_tipo_pessoa
     * Sample of usage: $cliente->tipo_pessoa = $object;
     * @param $object Instance of TipoPessoa
     */
    public function set_tipo_pessoa(TipoPessoa $object)
    {
        $this->tipo_pessoa = $object;
        $this->tipo_pessoa_id = $object->id;
    }
    
    /**
     * Method get_tipo_pessoa
     * Sample of usage: $cliente->tipo_pessoa->attribute;
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
    
    
    /**
     * Method set_apelido
     * Sample of usage: $cliente->apelido = $object;
     * @param $object Instance of Apelido
     */
    public function set_apelido(Apelido $object)
    {
        $this->apelido = $object;
        $this->apelido_id = $object->id;
    }
    
    /**
     * Method get_apelido
     * Sample of usage: $cliente->apelido->attribute;
     * @returns Apelido instance
     */
    public function get_apelido()
    {
        // loads the associated object
        if (empty($this->apelido))
            $this->apelido = new Apelido($this->apelido_id);
    
        // returns the associated object
        return $this->apelido;
    }
    


}
