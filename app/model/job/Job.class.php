<?php
/**
 * Job Active Record
 * @author  <your-name-here>
 */
class Job extends TRecord
{
    const TABLENAME = 'job';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $empresa;
    private $cliente;
    private $system_user;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('cliente_id');
        parent::addAttribute('contato_atendimento');
        parent::addAttribute('email_contato');
        parent::addAttribute('telefone_contato');
        parent::addAttribute('data_atendimento');
        parent::addAttribute('data_retorno');
        parent::addAttribute('hora_retorno');
        parent::addAttribute('descricao');
        parent::addAttribute('executor_id');
        parent::addAttribute('empresa_id');
        parent::addAttribute('participantes');
        parent::addAttribute('situacao');
        parent::addAttribute('data_termino');
        parent::addAttribute('hora_termino');
        parent::addAttribute('flag_lida');
        parent::addAttribute('flag_atrasada');
        parent::addAttribute('email_enviado');
        parent::addAttribute('brindes');
        parent::addAttribute('tags');
        parent::addAttribute('login');
        parent::addAttribute('data_atualizacao');
    }

    
    /**
     * Method set_empresa
     * Sample of usage: $job->empresa = $object;
     * @param $object Instance of Empresa
     */
    public function set_empresa(Empresa $object)
    {
        $this->empresa = $object;
        $this->empresa_id = $object->id;
    }
    
    /**
     * Method get_empresa
     * Sample of usage: $job->empresa->attribute;
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
     * Method set_cliente
     * Sample of usage: $job->cliente = $object;
     * @param $object Instance of Cliente
     */
    public function set_cliente(Cliente $object)
    {
        $this->cliente = $object;
        $this->cliente_id = $object->id;
    }
    
    /**
     * Method get_cliente
     * Sample of usage: $job->cliente->attribute;
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
     * Method set_system_user
     * Sample of usage: $job->system_user = $object;
     * @param $object Instance of SystemUser
     */
    public function set_system_user(SystemUser $object)
    {
        $this->system_user = $object;
        $this->system_user_id = $object->id;
    }
    
    /**
     * Method get_system_user
     * Sample of usage: $job->system_user->attribute;
     * @returns SystemUser instance
     */
    public function get_system_user()
    {
        // loads the associated object
        if (empty($this->system_user))
            $this->system_user = new SystemUser($this->system_user_id);
    
        // returns the associated object
        return $this->system_user;
    }
    


}
