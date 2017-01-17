<?php

class PagarForm extends TPage
{

    private $form;
    private $datagrid_doc;

    public function __construct()
    {
        parent::__construct();
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Cadastro de Contas a Pagar');
        $this->form->setName('form_Pagar');
        
        // primeira aba
        $label1 = new TLabel('Dados de Contas a Pagar','#7D78B6',12,'bi');
        $label1->style = 'text-align:left;border-bottom: 1px solid #c0c0c0;width:100%';
        
        $this->form->appendPage('Dados Principais');
        $this->form->addContent([$label1]);
        
        // criteria ativos
        $ativos = new TCriteria;
        $ativos->add(new TFilter('situacao','=','A'));
        
        // criteria plano contas
        $crit_plano_conta = new TCriteria;
        $crit_plano_conta->add(new TFilter('situacao','=','A'));
        $crit_plano_conta->add(new TFilter('tipo','=','P'));
        
        $id                = new TEntry('id');
        $data_lancamento   = new TDate('data_lancamento');
        $fornecedor_id     = new TDBCombo('fornecedor_id','eventos','Fornecedor','id','razao_social','razao_social',$ativos);
        $destinatario      = new TEntry('destinatario');
        $plano_conta_id    = new TDBCombo('plano_conta_id','eventos','PlanoConta','id','descricao_plano_conta','descricao',$crit_plano_conta);
        $nota_fiscal       = new TEntry('nota_fiscal');
        $tipo_controle_id  = new TDBCombo('tipo_controle_id','eventos','TipoControle','id','descricao','descricao');
        $codigo_barras     = new TEntry('codigo_barras');
        $empresa_id        = new TDBCombo('empresa_id','eventos','Empresa','id','nome','nome');
        $conta_corrente_id = new TDBCombo('conta_corrente_id','eventos','ContaCorrente','id','descricao_completa','id'); 
        $job_id            = new TDBSeekButton('job_id','eventos','form_Pagar','Job','nome','job_id','nome_job');
        $nome_job          = new TEntry('nome_job');
        
        // config
        $id->setEditable(FALSE);
        $id->setSize('80');
        $data_lancamento->setMask('dd/mm/yyyy');
        $data_lancamento->setEditable(FALSE);
        $data_lancamento->setValue(date('d/m/Y'));
        $data_lancamento->setSize('100');
        $fornecedor_id->enableSearch();
        $fornecedor_id->setSize('100%');
        $fornecedor_id->addValidation('Fornecedor', new TRequiredValidator);
        $destinatario->setSize('100%');
        $destinatario->addValidation('Destinatário', new TRequiredValidator);
        $plano_conta_id->setSize('100%');
        $plano_conta_id->enableSearch();
        $plano_conta_id->addValidation('Plano de Contas',new TRequiredValidator);
        $fornecedor_id->setChangeAction(new TAction(array($this,'onSelectFornecedor')));
        $nota_fiscal->setSize('100%');
        $tipo_controle_id->enableSearch();
        $tipo_controle_id->setSize('100%');
        $tipo_controle_id->addValidation('Tipo de Controle', new TRequiredValidator);
        $codigo_barras->setSize('100%');
        $empresa_id->enableSearch();
        $empresa_id->setSize('100%');
        $empresa_id->addValidation('Empresa Contábil', new TRequiredValidator);
        $conta_corrente_id->enableSearch();
        $conta_corrente_id->setSize('100%');
        $job_id->setSize('100');
        $nome_job->setEditable(FALSE);
        $nome_job->setSize('31%');
        
        
        $this->form->addFields( [new TLabel('Código')] ,
                                [ $id ],
                                [new TLabel('Data Lançamento')],
                                [ $data_lancamento] );
        $this->form->addFields( [new TLabel('Fornecedor','red',12,'b')] ,
                                [ $fornecedor_id ]);                                
        $this->form->addFields( [new TLabel('Destinatário','red',12,'b')] ,
                                [ $destinatario]);   
        $this->form->addFields( [new TLabel('Plano de Contas','red',12,'b')],
                                [ $plano_conta_id],
                                [ new TLabel('Documento Fiscal')],
                                [ $nota_fiscal ]);
                                
        $this->form->addFields( [ new TLabel('Tipo de Controle','red',12,'b')],
                                [ $tipo_controle_id] ,
                                [ new TLabel('Código de Barras')],
                                [ $codigo_barras]);                                                                                                 

        $this->form->addFields( [ new TLabel('Empresa Contábil','red',12,'b')],
                                [ $empresa_id] ,
                                [ new TLabel('Conta Corrente')] ,
                                [ $conta_corrente_id] );
                                
        $this->form->addFields( [ new TLabel('JOB')] ,
                                [ $job_id, $nome_job]);                                
        // add action                                
        $this->form->addAction('Salvar',new TAction(array($this,'onSave')),'fa:save blue fa-fw');                                
        $this->form->addAction('Cancelar',new TAction(array('PagarList','onReload')),'fa:table red fa-fw');
        
        // add form parent
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);
        parent::add($vbox);                                
    }
    
    public function onEdit($param)
    {
        
    }
    
    public function onSave($param)
    {
        try {
        
            $pagar = $this->form->getData('Pagar');
            
            $this->form->validate();
            
            $this->form->setData($pagar);
        
        } catch (Exception $e){
            new TMessage('error',$e->getMessage());
        }
    }


    /**
    * Selecionar um fornecedor
    */
    public static function onSelectFornecedor($param)
    {
        try {
            TTransaction::open('eventos');
            $fornecedor = new Fornecedor($param['key']);
            $obj = new stdClass;
            $obj->destinatario = $fornecedor->razao_social;
            TForm::sendData('form_Pagar',$obj);
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error',$e->getMessage());
        }
        
    }

}


