<?php
/**
 * Pagar Active Record
 * @author  <your-name-here>
 */
class Pagar extends TRecord
{
    const TABLENAME = 'pagar';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $cliente;
    private $empresa;
    private $fornecedor;
    private $tipo_cobranca;
    private $tipo_controle;
    private $job;
    private $conta_corrente;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data_lancamento');
        parent::addAttribute('destinatario');
        parent::addAttribute('fornecedor_id');
        parent::addAttribute('empresa_id');
        parent::addAttribute('plano_conta_id');
        parent::addAttribute('nota_fiscal');
        parent::addAttribute('tipo_controle_id');
        parent::addAttribute('valor');
        parent::addAttribute('data_vencimento');
        parent::addAttribute('data_pagamento');
        parent::addAttribute('observacao');
        parent::addAttribute('job_id');
        parent::addAttribute('pagar_id');
        parent::addAttribute('banco');
        parent::addAttribute('agencia');
        parent::addAttribute('conta_corrente');
        parent::addAttribute('codigo_barras');
        parent::addAttribute('conta_corrente_id');
        parent::addAttribute('seq_pagto_antecipado');
        parent::addAttribute('retencao_imposto');
        parent::addAttribute('login');
        parent::addAttribute('data_atualizacao');
    }

    
    /**
     * Method set_cliente
     * Sample of usage: $pagar->cliente = $object;
     * @param $object Instance of Cliente
     */
    public function set_cliente(Cliente $object)
    {
        $this->cliente = $object;
        $this->cliente_id = $object->id;
    }
    
    /**
     * Method get_cliente
     * Sample of usage: $pagar->cliente->attribute;
     * @returns Cliente instance
     */
    public function get_cliente()
    {
        // loads the associated object
        if (empty($this->cliente))
            $this->cliente = new Cliente($this->cliente_id);
    
        // returns the associated object
        return $this->cliente;
    }
    
    
    /**
     * Method set_empresa
     * Sample of usage: $pagar->empresa = $object;
     * @param $object Instance of Empresa
     */
    public function set_empresa(Empresa $object)
    {
        $this->empresa = $object;
        $this->empresa_id = $object->id;
    }
    
    /**
     * Method get_empresa
     * Sample of usage: $pagar->empresa->attribute;
     * @returns Empresa instance
     */
    public function get_empresa()
    {
        // loads the associated object
        if (empty($this->empresa))
            $this->empresa = new Empresa($this->empresa_id);
    
        // returns the associated object
        return $this->empresa;
    }
    
    
    /**
     * Method set_fornecedor
     * Sample of usage: $pagar->fornecedor = $object;
     * @param $object Instance of Fornecedor
     */
    public function set_fornecedor(Fornecedor $object)
    {
        $this->fornecedor = $object;
        $this->fornecedor_id = $object->id;
    }
    
    /**
     * Method get_fornecedor
     * Sample of usage: $pagar->fornecedor->attribute;
     * @returns Fornecedor instance
     */
    public function get_fornecedor()
    {
        // loads the associated object
        if (empty($this->fornecedor))
            $this->fornecedor = new Fornecedor($this->fornecedor_id);
    
        // returns the associated object
        return $this->fornecedor;
    }
    
    
    /**
     * Method set_tipo_cobranca
     * Sample of usage: $pagar->tipo_cobranca = $object;
     * @param $object Instance of TipoCobranca
     */
    public function set_tipo_cobranca(TipoCobranca $object)
    {
        $this->tipo_cobranca = $object;
        $this->tipo_cobranca_id = $object->id;
    }
    
    /**
     * Method get_tipo_cobranca
     * Sample of usage: $pagar->tipo_cobranca->attribute;
     * @returns TipoCobranca instance
     */
    public function get_tipo_cobranca()
    {
        // loads the associated object
        if (empty($this->tipo_cobranca))
            $this->tipo_cobranca = new TipoCobranca($this->tipo_cobranca_id);
    
        // returns the associated object
        return $this->tipo_cobranca;
    }
    
    
    /**
     * Method set_tipo_controle
     * Sample of usage: $pagar->tipo_controle = $object;
     * @param $object Instance of TipoControle
     */
    public function set_tipo_controle(TipoControle $object)
    {
        $this->tipo_controle = $object;
        $this->tipo_controle_id = $object->id;
    }
    
    /**
     * Method get_tipo_controle
     * Sample of usage: $pagar->tipo_controle->attribute;
     * @returns TipoControle instance
     */
    public function get_tipo_controle()
    {
        // loads the associated object
        if (empty($this->tipo_controle))
            $this->tipo_controle = new TipoControle($this->tipo_controle_id);
    
        // returns the associated object
        return $this->tipo_controle;
    }
    
    
    /**
     * Method set_job
     * Sample of usage: $pagar->job = $object;
     * @param $object Instance of Job
     */
    public function set_job(Job $object)
    {
        $this->job = $object;
        $this->job_id = $object->id;
    }
    
    /**
     * Method get_job
     * Sample of usage: $pagar->job->attribute;
     * @returns Job instance
     */
    public function get_job()
    {
        // loads the associated object
        if (empty($this->job))
            $this->job = new Job($this->job_id);
    
        // returns the associated object
        return $this->job;
    }
    
    
    /**
     * Method set_conta_corrente
     * Sample of usage: $pagar->conta_corrente = $object;
     * @param $object Instance of ContaCorrente
     */
    public function set_conta_corrente(ContaCorrente $object)
    {
        $this->conta_corrente = $object;
        $this->conta_corrente_id = $object->id;
    }
    
    /**
     * Method get_conta_corrente
     * Sample of usage: $pagar->conta_corrente->attribute;
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
