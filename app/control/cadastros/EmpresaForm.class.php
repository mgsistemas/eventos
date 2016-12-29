<?php

class EmpresaForm extends TPage
{

    private $form;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Empresa Contábil');
        $this->form->setName('form_empresa');
        
        $label = new TLabel('Dados Cadastrais','#7D78B6',12,'bi');
        $label->style = 'text-align:left;border-bottom: 1px solid #c0c0c0;width:100%';
        
        $this->form->appendPage('Cadastro');
        $this->form->addContent([ $label ]);
        
        // define os campos
        $id                = new TEntry('id');
        $nome              = new TEntry('nome');
        $cnpj              = new TEntry('cnpj');
        $ie                = new TEntry('ie');
        $cep               = new TEntry('cep');
        $logradouro        = new TEntry('logradouro');
        $complemento       = new TEntry('complemento');
        $bairro            = new TEntry('bairro');
        $cidade            = new TEntry('cidade');
        $estado            = new TEntry('estado');
        $telefone          = new TEntry('telefone');
        $email             = new TEntry('email');
        $conta_corrente_id = new TDBCombo('conta_corrente_id','eventos','ContaCorrente','id','descricao_completa');
        $imposto           = new TEntry('imposto');
        $brindes           = new TCombo('brindes');
        
        // config os campos
        $id->setEditable(FALSE);
        $id->setSize('15%');
        $nome->setSize('85%');
        $nome->forceUpperCase();
        $nome->addValidation('Nome da Empresa',new TRequiredValidator);
        $cnpj->setSize('60%');
        $cnpj->setMask('99.999.999/9999-99');
        $cnpj->addValidation('CNPJ', new TCNPJValidator);
        $ie->setSize('40%');
        $ie->placeholder = 'Inscrição Estadual';
        $cep->setMask('99999-999');
        $logradouro->forceUpperCase();
        $complemento->forceUpperCase();
        $bairro->forceUpperCase();
        $cidade->forceUpperCase();
        $estado->forceUpperCase();
        $action_cep = new TAction(array($this,'onCep'));
        $cep->setExitAction($action_cep);
        $cep->setSize('20%');
        $logradouro->setSize('70%');
        $complemento->setSize('30%');
        $complemento->placeholder = 'COMPLEMENTO';
        $bairro->setSize('100%');
        $cidade->setSize('85%');
        $estado->setSize('15%');
        $estado->placeholder = 'UF';
        $telefone->setMask('(99)9999-9999');
        $telefone->setSize('50%');
        $email->forceLowerCase();
        $email->setSize('100%');
        $email->addValidation('E-mail',new TEmailValidator());
        $imposto->setNumericMask(2,',','.',TRUE);
        $imposto->setSize('30%');
        $conta_corrente_id->enableSearch();
        $conta_corrente_id->setSize('100%');
        $brindes->setSize('15%');
        $brindes->addItems(array('S'=>'Sim','N'=>'Não'));
        
        
        // define os labels
        $lbl_nome = new TLabel('Nome (*)');
        $lbl_nome->style = 'color:red; font-weight: bold;';
        $lbl_cnpj = new TLabel('CNPJ (*)');
        $lbl_cnpj->style = 'color:red; font-weight: bold;';
        
        // adiciona os campos no form
        $this->form->addFields( [ new TLabel('Código')] ,
                                [ $id ]);
        $this->form->addFields( [ $lbl_nome ],
                                [ $nome ],
                                [ $lbl_cnpj ],
                                [ $cnpj, $ie]);    
        $this->form->addFields ( [ new TLabel('CEP') ] ,
                                 [ $cep ],
                                 [ new TLabel('Logradouro')],
                                 [ $logradouro, $complemento ]);   
        $this->form->addFields ( [ new TLabel('Bairro')],
                                 [ $bairro] ,
                                 [ new TLabel('Cidade') ],
                                 [ $cidade, $estado]);      
        $this->form->addFields ( [ new TLabel('Telefone')] ,
                                 [ $telefone ],
                                 [ new TLabel('E-mail','red',12,'b')] ,
                                 [ $email ]);                                                                                                  
                   
        $this->form->addFields ( [ new TLabel('Conta Corrente')] ,
                                 [ $conta_corrente_id ],
                                 [ new TLabel('Imposto') ] ,
                                 [ $imposto]);
        $this->form->addFields ( [ new TLabel('Brindes ? ' )],
                                 [ $brindes]);
                                       
        $this->form->addAction('Salvar',new TAction(array($this,'onSave')),'fa:save blue');                                                            
        $this->form->addAction('Cancelar',new TAction(array('EmpresaList','onReload')),'fa:table red');                                                            
        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);
        parent::add($vbox);
    
    }
    
    public function onEdit($param)
    {
        try {
            TTransaction::open('eventos');
            if (isset($param['key'])) {
                $key = $param['key'];
                $empresa = new Empresa($key);
                $this->form->setData($empresa);
            }
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error',$e->getMessage());
        }
    }
    
    public function onSave( $param )
    {
        try {
            TTransaction::open('eventos');
            $data = $this->form->getData();
            
            $this->form->validate();
            
            $empresa = new Empresa();
            $empresa->fromArray((array) $data);
            $empresa->login = TSession::getValue('login');
            $empresa->data_atualizacao = date('Y-m-d H:i:s');
            $empresa->store();
            
            $this->form->setData($empresa);
            TTransaction::close();
            new TMessage('info','Registro Salvo com Sucesso');
        
        } catch (Exception $e) {
            new TMessage('error',$e->getMessage());
            TTransaction::rollback();
        }
    
    }

    /* 
     *  Função de busca de Endereço pelo CEP 
     *  -   Desenvolvido Felipe Olivaes para ajaxbox.com.br 
     *  -   Utilizando WebService de CEP da republicavirtual.com.br 
     */  
    public static function onCep($param)
    {
        //var_dump($param);
        $resultado = @file_get_contents('http://republicavirtual.com.br/web_cep.php?cep='.urlencode($param['cep']).'&formato=query_string');  
        if(!$resultado){  
            $resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";  
        }  
        parse_str($resultado, $retorno);   
        $obj = new StdClass;
        $obj->logradouro = strtoupper( $retorno['tipo_logradouro'].' '.$retorno['logradouro']);
        $obj->bairro = strtoupper($retorno['bairro']);
        $obj->cidade = strtoupper($retorno['cidade']);
        $obj->estado = strtoupper($retorno['uf']);
        TForm::sendData('form_empresa', $obj);

        /*        
        $obj->PRO_ENDERECO = strtoupper( $retorno['tipo_logradouro'].' '.$retorno['logradouro']);
        $obj->PRO_BAIRRO   = strtoupper( $retorno['bairro']);
        $obj->PRO_CIDADE   = strtoupper( $retorno['cidade']);
        $obj->PRO_UF       = strtoupper( $retorno['uf']);
        */ 

    }


}
