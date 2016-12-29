<?php

/**
 * ClienteForm
 * 
 * @version   1.0
 * @package   eventos
 * @author    Marcelo Gomes
 * @copyright Copyright (c) 2012 MG Serviços em TI
 * @license   
 */
 
 class ClienteForm extends TPage
 {
 
    private $form;
    private $datagrid;
    private $datagrid_contatos;
     
    public function __construct()
    {
        parent::__construct();
         
        $this->form = new BootstrapFormBuilder;
        $this->form->setName('form_clientes');
        $this->form->setFormTitle('Clientes');
        $this->style = 'width: 100%';
        
        $label_cliente = new TLabel('Dados Cadastrais','#7D778B6', 12, 'bi');
        $label_cliente->style = 'text-align:left; border-bottom:1px solid #c0c0c0;width:100%';
         
         
        $id                    = new TEntry('id');
        $apelido_id            = new TDBCombo('apelido_id','eventos','Apelido','id','descricao','descricao');
        $razao_social          = new TEntry('razao_social');
        $nome_fantasia         = new TEntry('nome_fantasia');
        $tipo_pessoa_id        = new TDBCombo('tipo_pessoa_id','eventos','TipoPessoa','id','descricao');
        $cpf_cnpj              = new TEntry('cpf_cnpj');
        $ie                    = new TEntry('ie');
        $situacao              = new TCombo('situacao');
        $cep                   = new TEntry('cep');
        $logradouro            = new TEntry('logradouro');
        $complemento           = new TEntry('complemento');
        $bairro                = new TEntry('bairro');
        $cidade                = new TEntry('cidade');
        $estado                = new TEntry('estado');
        $boleto                = new TCombo('boleto');
        $pedidocompra          = new TCombo('pedidocompra');
        $prazopagto            = new TEntry('prazopagto');
        $regrafaturamento      = new TEntry('regrafaturamento');
        $observacaofaturamento = new THtmlEditor('observacaofaturamento');
        $observacao            = new THtmlEditor('observacao');
         
        // config campos
        $id->setEditable(FALSE);
        $id->setSize('80');
        $apelido_id->setSize('90%');
        $apelido_id->enableSearch();
        $razao_social->forceUpperCase();
        $razao_social->setSize('100%');
        $nome_fantasia->forceUpperCase();
        $nome_fantasia->setSize('100%');
        $tipo_pessoa_id->setSize('120');
        $tipo_pessoa_id->setValue(1);
        $cpf_cnpj->setMask('999.999.999-99');
        $cpf_cnpj->setSize('100%');
        $ie->setSize('100%');
        $situacao->setSize('100%');
        $cep->setMask('99999-999');
        $logradouro->forceUpperCase();
        $logradouro->setSize('70%');
        $complemento->forceUpperCase();
        $complemento->setSize('30%');
        $bairro->setSize('100%');
        $bairro->forceUpperCase();
        $cidade->setSize('80%');
        $cidade->forceUpperCase();
        $estado->setSize('20%');
        $estado->forceUpperCase();
        $estado->setMask('AA');
        $boleto->setSize('50%');
        $pedidocompra->setSize('50%');
        $prazopagto->setSize('100%');
        $regrafaturamento->setSize('100%');
        $observacaofaturamento->setSize('100%',200);
        $observacao->setSize('100%',200);
        
        //acao do cep
        $action_cep = new TAction(array($this,'onCep'));
        $cep->setExitAction($action_cep);
        
        // opcoes sim_nao
        $op_sim_nao = array(
            'S' => 'Sim',
            'N' => 'Não'
        );
        
        // opcoe de situacao
        $op_situacao = array (
            'A' => 'Ativo',
            'I' => 'Inativo',
            'B' => 'Bloqueado'
        );
        $situacao->addItems($op_situacao);
        $situacao->enableSearch();
        
        # boleto e pedido de compra
        $boleto->addItems($op_sim_nao);
        $pedidocompra->addItems($op_sim_nao);

        // adiciona paginas        
        $this->form->appendPage('Dados Principais');
        $this->form->addContent([$label_cliente]);

        // botao adicionar apelido
        $action_add_apelido = new TAction(array($this,'onAddApelido'));
        $action_add_apelido->setParameter('cliente_id',$id);
        $btn_add_apelido = new TButton('add_apelido');
        $btn_add_apelido->setImage('ico_add.png');
        $btn_add_apelido->setAction($action_add_apelido);
        $btn_add_apelido->setValue('Adicionar um Novo Apelido');
        $btn_add_apelido->setTip('Adicionar um novo apelido');
        $btn_add_apelido->setSize('10%');
         
        // adiciona os campos
        $this->form->addFields ( [ new TLabel('Código') ] ,
                                 [ $id ],
                                 [ new TLabel('Apelido') ],
                                 [ $apelido_id, $btn_add_apelido ] );
                                 
        $this->form->addFields ( [ new TLabel('Razão Social') ] ,
                                 [ $razao_social ] ,
                                 [ new TLabel('Nome Fantasia ')],
                                 [ $nome_fantasia ] );
                                 
        $this->form->addFields ( [ new TLabel('Tipo de Pessoa') ],
                                 [ $tipo_pessoa_id ] ,
                                 [ new TLabel('CPF / CNPJ') ],
                                 [ $cpf_cnpj ] );    
        $this->form->addFields ( [ new TLabel('Inscrição Estadual')] ,
                                 [ $ie ], 
                                 [ new TLabel('Situação ') ],
                                 [ $situacao ] );
        # endereco                              
        $label_cliente = new TLabel('Dados de Localização','#7D778B6', 12, 'bi');
        $label_cliente->style = 'text-align:left; border-bottom:1px solid #c0c0c0;width:100%';
        $this->form->addContent([$label_cliente]);

        $this->form->addFields ( [ new TLabel('CEP') ] ,
                                 [ $cep ],
                                 [ new TLabel('Logradouro / Complemento') ],
                                 [ $logradouro, $complemento]);      
        $this->form->addFields ( [ new TLabel('Bairro')] ,
                                 [ $bairro] ,
                                 [ new TLabel('Cidade / Estado') ],
                                 [ $cidade, $estado]);                                                                                             
        
        # dados complementares                              
        $label_cliente = new TLabel('Dados Complementares','#7D778B6', 12, 'bi');
        $label_cliente->style = 'text-align:left; border-bottom:1px solid #c0c0c0;width:100%';
        $this->form->addContent([$label_cliente]);
        
        $this->form->addFields ( [ new TLabel('Boleto / Pedido de Compra')] ,
                                 [ $boleto, $pedidocompra],
                                 [ new TLabel('Prazo de Pagamento') ],
                                 [ $prazopagto ]);
                                 
        $this->form->addFields ( [ new TLabel('Regra de Faturamento') ],
                                 [ $regrafaturamento]);
                                 
        $this->form->addFields ( [ new TLabel('Obs Faturamento') ],
                                 [ $observacaofaturamento ],
                                 [ new TLabel('Observação') ],
                                 [ $observacao ]);                                                                  
                                    
         
        // adicionar acoes
        //******************************************************************
        //script para definir a mascara do cpf/cn
        $script = new TElement('script'); 
        $script->type = 'text/javascript';
        $javascript = " 
            $(document).on('change','select[name=\"tipo_pessoa_id\"]' , function(event){
                //alert('Entrou');
                $('input[name=\"cpf_cnpj\"]').val('');
               
                $('select[name=\"tipo_pessoa_id\"] > option:selected').each(function(){
                    tipoPessoa = $(this).text();
                });
                if(tipoPessoa.toLowerCase() == 'física') {
                    $('input[name=\"cpf_cnpj\"]').val('');
                    $('input[name=\"cpf_cnpj\"]').attr({onkeypress:'return tentry_mask(this,event,\"999.999.999-99\")'});                        
                 }
                 if(tipoPessoa.toLowerCase() == 'jurídica') {
                     $('input[name=\"cpf_cnpj\"]').val('');
                     $('input[name=\"cpf_cnpj\"]').attr({onkeypress:'return tentry_mask(this,event,\"99.999.999/9999-99\")'});                    
                  }

            });";
        $script->add($javascript); 
        $tableScriptPessoa = new TTable;
        $tableScriptPessoa->addRow()->addCell($script);
        //*****************************************************************
        
        
        // pagina documentos
        $this->form->appendPage('Documentos');
        $label_documentos = new TLabel('Documentos','#7D778B6', 12, 'bi');
        $label_documentos->style = 'text-align:left; border-bottom:1px solid #c0c0c0;width:100%';
        $this->form->addContent([$label_documentos]);
        
        # campos
        $file           = new TFile('file');
        $descricao_file = new TEntry('descricao_file');
        
        $file->setSize('400');
        $descricao_file->setSize('100%');
        
        $this->form->addFields ( [ new TLabel('Descrição')] , 
                                 [ $descricao_file ] ,
                                 [ new TLabel('Arquivo') ],
                                 [ $file] );
                                 
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $column_id            = new TDataGridColumn('id','#ID','center',50);
        $column_descricao     = new TDataGridColumn('descricao','Descricao','left',300);
        $column_nomedocumento = new TDataGridColumn('nomedocumento','Nome do Arquivo','left',400);
        $column_login         = new TDataGridColumn('login','Atualizado por','center',200);
        $column_updated       = new TDataGridColumn('updated_at','Em','center',180);
        
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_nomedocumento);
        $this->datagrid->addColumn($column_login);
        $this->datagrid->addColumn($column_updated);
        $this->datagrid->disableDefaultClick();
        
        $column_updated->setTransformer(function($value, $object, $row){
            $data = new DateTime($value);
            return $data->format('d/m/Y H:m:s');
        });
        
        // acoes
        $action_del  = new TDataGridAction(array($this,'onDeleteDoc'));
        $action_del->setImage('ico_delete.png');
        $action_del->setField('id');
        
        $action_down = new TDataGridAction(array($this,'onDownload'));
        $action_down->setImage('ico_down.png');
        $action_down->setField('id');
        
        $this->datagrid->addAction($action_del);
        $this->datagrid->addAction($action_down);
        
        
        $this->datagrid->createModel();
        $this->form->addFields( [$this->datagrid] );
                                         

        // contatos
        $this->form->appendPage('Contatos');
        $label_contatos = new TLabel('Contatos','#7D778B6', 12, 'bi');
        $label_contatos->style = 'text-align:left; border-bottom:1px solid #c0c0c0;width:100%';
        $this->form->addContent([$label_contatos]);
        
        # campos
        $id_contato       = new TEntry('id_contato');
        $nome_contato     = new TEntry('nome');
        $departamento_id  = new TDBCombo('departamento_id','eventos','Departamento','id','descricao','descricao');
        $email_contato    = new TEntry('email');
        $telefone_contato = new TEntry('telefone');
        $celular_contato  = new TEntry('celular');
        $diames           = new TEntry('diames');
        $situacao_contato = new TCombo('situacao_contato');
        
        
        $nome_contato->setSize('90%');
        $nome_contato->forceUpperCase();
        $email_contato->setSize('100%');
        $email_contato->forceLowerCase();
        $telefone_contato->setSize('50%');
        $celular_contato->setSize('50%');
        $diames->setSize('33%');
        $telefone_contato->setMask('(99)9999-9999');
        $celular_contato->setMask('(99)99999-9999');
        $diames->setMask('99/99');
        $departamento_id->enableSearch();
        $id_contato->setSize('10%');
        $id_contato->setEditable(FALSE);
        $situacao_contato->setSize('80%');
        
        $op_ativo_inativo = array('A'=>'Ativo', 'I'=>'Inativo');
        $situacao_contato->addItems($op_ativo_inativo);
        
        $btn_adicionar_contato = new TButton('add_contato');
        $btn_adicionar_contato->setLabel('Adicionar');
        $btn_adicionar_contato->setImage('ico_add.png');
        $btn_adicionar_contato->setAction(new TAction(array($this,'onAddContato')),'Salvar');
        $btn_adicionar_contato->setSize('20%');
        
        $this->form->addFields( [ new TLabel('ID / Nome')] ,
                                [ $id_contato, $nome_contato ],
                                [ new TLabel('Departamento')],
                                [ $departamento_id ] );
        $this->form->addFields( [ new TLabel('E-mail') ],
                                [ $email_contato ],
                                [ new TLabel('Dia-Ms')],
                                [ $diames]);           
        $this->form->addFields( [ new TLabel('Telefone / Celular') ],
                                [ $telefone_contato, $celular_contato] ,
                                [ new TLabel('Situação')], 
                                [ $situacao_contato, $btn_adicionar_contato ]);                                                     

        # grid de contatos
        $this->datagrid_contatos = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid_contatos->style = 'width: 100%';
        $this->datagrid_contatos->datatable = 'true';
        
        $column_nome_contato = new TDataGridColumn('nome','Nome','left',300);
        $column_departamento = new TDataGridColumn('departamento_contato','Departamento','left',250);
        $column_email        = new TDataGridColumn('email','E-mail','left',250);
        $column_telefone     = new TDataGridColumn('telefone','Telefone','center',150);
        $column_celular      = new TDataGridColumn('celular','Celular','center',150);
        $column_aniversario  = new TDataGridColumn('diames','Aniversário','center',100);
        $column_situacao     = new TDataGridColumn('situacao','Situação','center',80);
        
        $this->datagrid_contatos->addColumn($column_nome_contato);
        $this->datagrid_contatos->addColumn($column_departamento);
        $this->datagrid_contatos->addColumn($column_email);
        $this->datagrid_contatos->addColumn($column_telefone);
        $this->datagrid_contatos->addColumn($column_celular);
        $this->datagrid_contatos->addColumn($column_aniversario);
        $this->datagrid_contatos->addColumn($column_situacao);
        
        # transformer em colunas
        $column_situacao->setTransformer(function($value, $object, $row){
            $lbl = new TLabel('');
            if ($value == 'A') {
                $lbl->class = 'label label-success';
                $lbl->setValue('Ativo');
            } else {
                $lbl->class = 'label label-danger';
                $lbl->setValue('Inativo');
            }
            return $lbl;
        });
        
        $action_edit_contato  = new TDataGridAction(array($this,'onEditContato'));
        $action_edit_contato->setImage('ico_edit.png');
        $action_edit_contato->setField('id_contato');
        
        $action_del_contato = new TDataGridAction(array($this,'onDeleteContato'));
        $action_del_contato->setImage('ico_delete.png');
        $action_del_contato->setField('id_contato');
        
        $this->datagrid_contatos->addAction($action_edit_contato);
        $this->datagrid_contatos->addAction($action_del_contato);
        //$this->datagrid_contatos->disableDefaultClick();
        
        $this->datagrid_contatos->createModel();
        $this->form->addFields( [ $this->datagrid_contatos ]);
        
        // acoes
        $this->form->addAction('Salvar',new TAction(array($this,'onSave')),'fa:save blue');
        $this->form->addAction('Cancelar', new TAction(array('ClienteList','onReload')),'fa:close red');

         
        // wrap the page content using vertial box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);
        $vbox->add($tableScriptPessoa);
         
        parent::add($vbox);
     
    }
    
    /**
    * input dialog para registrar um novo apelido
    */
    public function onAddApelido($param)
    {
        try {
            $input_form = new TQuickForm('input_apelido_form');
            $input_form->style = 'padding: 20px;';
            
            $descricao = new TEntry('descricao');
            $descricao->forceUpperCase();
            
            $action_save_apelido = new TAction(array($this,'onSaveApelido'));
            $action_save_apelido->setParameter('cliente_id',$param['id']);
            $input_form->addQuickField('Descriçao', $descricao, 200, new TRequiredValidator);
            $input_form->addQuickAction('Salvar',$action_save_apelido,'ico_save.png');
            
            $this->carregarContatos($param['id']);
            $this->carregarDadosCliente($param['id']);
            $this->carregarDataGrid($param['id']);
            
            new TInputDialog('Novo Apelido',$input_form);
        
        } catch (Exception $e) {
            new TMessage('error','Erro : ' . $e->getMessage() . '<br>'.$e->getTrace());
        }
    }
    
    public function onSaveApelido($param)
    {
        try {
            TTransaction::open('eventos');
            
            //var_dump($param);
            
            $apelido = new Apelido;
            $apelido->descricao = $param['descricao'];
            $apelido->login = TSession::getValue('login');
            $apelido->updated_at = date('Y-m-d H:i:s');
            $apelido->store();
            
            $obj = array();
            $repository = new TRepository('Apelido');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('id','>',0));
            $apelidos = $repository->load($criteria);
            $i = 0;
            foreach($apelidos as $ap){
                $obj[$i] = $ap->descricao;
                $i++;
            }
                        
            TCombo::reload('form_clientes','apelido_id',$obj);
            $this->carregarContatos($param['cliente_id']);
            $this->carregarDadosCliente($param['cliente_id']);
            $this->carregarDataGrid($param['cliente_id']);
            $this->form->setCurrentPage(0);
            
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error','Erro : ' . $e->getMessage() . '<br>'.$e->getTrace());
        }
    }
    
    /**
     * Metodo para editar um contato
     */
    public function onEditContato($param)
    {
        try {
            TTransaction::open('eventos');
            $contato = new ClienteContato($param['key']);
            $data = new stdClass;
            $data->id_contato       = $contato->id;
            $data->nome             = $contato->nome;
            $data->departamento_id  = $contato->departamento_id;
            $data->email            = $contato->email;
            $data->telefone         = $contato->telefone;
            $data->celular          = $contato->celular;
            $data->diames           = $contato->diames;
            $data->situacao_contato = $contato->situacao;
            
            $this->form->setData($data);
            
            $this->carregarDadosCliente($contato->cliente_id);
            $this->carregarDataGrid($contato->cliente_id);
            $this->carregarContatos($contato->cliente_id);
            $this->form->setCurrentPage(2);
            
            TTransaction::close();
        } catch (Exception $e){
            new TMessage('error','Erro : ' . $e->getMessage());
        }
    }
    
    /**
     * Question exclusao contato
     */
    public function onDeleteContato($param)
    {
        try {       
            TTransaction::open('eventos'); 
            $contato = new ClienteContato($param['key']);
            $cliente_id = $contato->cliente_id;
            $this->form->setCurrentPage(2);
            $this->carregarDataGrid($cliente_id);
            $this->carregarDadosCliente($cliente_id);
            $this->carregarContatos($cliente_id);
            $action_c  = new TAction(array($this, 'onExcluirContato'));
            $action_c->setParameter('key', $param['key']);
            new TQuestion('Confirma a Exclusão',$action_c);
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error',$e->getMessage());
            TTransaction::rollback();
        }
    
    }    
    
    /**
    * Exclusao do contato
    */
    public function onExcluirContato($param)
    {
        try {
            TTransaction::open('eventos');
            $contato = new ClienteContato($param['key']);
            $cliId = $contato->cliente_id;
            $contato->delete();
            $this->carregarDataGrid($cliId);
            $this->carregarDadosCliente($cliId);
            $this->carregarContatos($cliId);
            TTransaction::close();
            $this->form->setCurrentPage(2);
        } catch (Exception $e){
            new TMessage('error','Erro : ' . $d->getMessage());
            TTransaction::rollback();
        }
        
    }
     
    public function onAddContato($param) 
    {
        try {
            TTransaction::open('eventos');
            $data = $this->form->getData();
            
            $grava = TRUE;
            // varifica se os
            if (empty($data->nome)) {
                new TMessage('error','Erro : Nome do Contato é obrigatório');
                $grava = FALSE;
            }
            
            if (isset($data->id_contato)) {
                $contatos = new ClienteContato($data->id_contato);
            } else {
                $contatos = new ClienteContato;
            }
            $contatos->cliente_id      = $data->id;
            $contatos->nome            = $data->nome;
            $contatos->departamento_id = $data->departamento_id;
            $contatos->email           = $data->email;
            $contatos->telefone        = $data->telefone;
            $contatos->celular         = $data->celular;
            $contatos->diames          = $data->diames;
            $contatos->situacao        = $data->situacao_contato;
            $contatos->login           = TSession::getValue('login');
            $contatos->updated_at      = date('Y-m-d H:i:s');
            
            if ($grava) {
                $contatos->store();
            }
            
            
            // limpa os campos para nova edição
            $data->id_contato       = "";
            $data->nome             = "";
            $data->departamento_id  = "";
            $data->email            = "";
            $data->telefone         = "";
            $data->celular          = "";
            $data->diames           = "";
            $data->situacao_contato = "";
            
            $this->form->setData($data);
            $this->form->setCurrentPage(2);
            TTransaction::close();
            
            $this->carregarDadosCliente($data->id);
            $this->carregarDataGrid($data->id);
            $this->carregarContatos($data->id);
        } catch (Exception $e){
            new TMessage('error','Erro : ' . $e->getMessage());
        }
    
    }
    
    public function carregarContatos($cliente_id)
    {
        try {
        
            TTransaction::open('eventos');
            
            $repository = new TRepository('ClienteContato');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('cliente_id','=',$cliente_id));
            $contatos = $repository->load($criteria);
            $this->datagrid_contatos->clear();
            if ($contatos) {
                foreach($contatos as $contato) {
                    $con = new stdClass;
                    $con->id_contato           = $contato->id;
                    $con->nome                 = $contato->nome;
                    $con->departamento_contato = $contato->departamento->descricao;
                    $con->email                = $contato->email;
                    $con->telefone             = $contato->telefone;
                    $con->celular              = $contato->celular;
                    $con->diames               = $contato->diames;
                    $con->situacao             = $contato->situacao;
                    $this->datagrid_contatos->addItem($con); 
                }
            }
            
            TTransaction::close();
        
        } catch (Exception $e) {
            new TMessage('error','Erro : ' . $e->getMessage());
        }
    }
 
    public function show()
    {
        parent::show();
    }
 
    /**
     * Caixa de confirmação de exclusao
     */
    public function onDeleteDoc($param)
    {
         $action1  = new TAction(array($this, 'onExcluir'));
         $action1->setParameter('key', $param['key']);
         new TQuestion('Confirma a Exclusão',$action1);
    }
    
    /**
     * Metodo que exclui o arquivo
     */
    public function onExcluir($param)
    {
        try {
            TTransaction::open('eventos');
            $doc = new Documento($param['key']);
            $cliId = $doc->doc_id;
            $nomeDoc = $doc->nomedocumento;
            $doc->delete();
            $this->carregarDataGrid($cliId);
            $this->carregarDadosCliente($cliId);
            TTransaction::close();
            unlink('files/docs/'.$nomeDoc);
            $this->form->setCurrentPage(1);
        } catch (Exception $e){
            new TMessage('error','Erro : ' . $d->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Metodo que faz o download do arquivo
     */
    public function onDownload($param)
    {
        try {
            TTransaction::open('eventos');
            $doc = new Documento($param['key']);
            
            if (!empty($doc->nomedocumento)) {
                parent::openFile('files/docs/'.$doc->nomedocumento); 
            }
            
            $this->carregarDataGrid($doc->doc_id);
            $this->carregarDadosCliente($doc->doc_id);
             
            $this->form->setCurrentPage(1);
            TTransaction::close();
        } catch (Exception $e){
            new TMessage('error','Erro : ' . $e->getMessage());
        }
    }
    
    public function carregarDadosCliente($id)
    {
        try {
        
            TTransaction::open('eventos');
            $cli = new Cliente($id);
            $this->form->setData($cli);
            
            TTransaction::close();
        
        } catch (Exception $e) {
            new TMessage('error','Erro : ' . $e->getMessage());
        }
    }
    
    public function carregarDataGrid($key)
    {
        try {
            TTransaction::open('eventos');
            $this->datagrid->clear();
            $repository = new TRepository('Documento');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('doc_id','=',$key));
            $criteria->add(new TFilter('tipo','=','C'));
            $docs = $repository->load($criteria);
            foreach($docs as $doc){
                $obj = new stdClass;
                $obj->id = $doc->id;
                $obj->descricao = $doc->descricao;
                $obj->nomedocumento = $doc->nomedocumento;
                $obj->login = $doc->login;
                $obj->updated_at = $doc->updated_at;
                $this->datagrid->addItem($obj);
            }
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error','Erro : ' . $e->getMessage());
        }
    }
        
    public function onSave( $param )
    {
        try {
            TTransaction::open('eventos');
            $this->form->validate();          
            
            $data = $this->form->getData();
            $cliente = $this->form->getData('Cliente');
            $cliente->store();
            $this->form->setData($cliente);
            
            if (isset($param['file'])) {   
                if (!empty($param['file'])) {
                    // verifica se a descricao foi informada
                    if (empty($param['descricao_file'])) {
                        new TMessage('error','Erro : Descrição do Documento é obrigatória!');
                        $this->form->setData($cliente);
                        $this->carregarDadosCliente($cliente->id);
                        $this->carregarDataGrid($cliente->id);
                        $this->form->setCurrentPage(1);
                        return;
                    }         
                    $source_file   = 'tmp/'.$param['file'];
                    $file_name = 'c_'.$data->id.'-'.UtilMGConsultoria::utf8Str($param['file']);
                    //$target_file   = 'storage/' . $param['file'];
                    $target_file   = 'files/docs/' . $file_name;
                    //$finfo         = new finfo(FILEINFO_MIME_TYPE);
                    
                    // if the user uploaded a source file
                    if (file_exists($source_file))
                    {
                        // move to the target directory
                        rename($source_file, $target_file);
                    }
                    
                    // grava o documento
                    $doc = new Documento;
                    $doc->doc_id        = $data->id;
                    $doc->tipo          = 'C';
                    $doc->descricao     = $param['descricao_file'];
                    $doc->nomedocumento = $file_name;
                    $doc->login         = TSession::getValue('login');
                    $doc->created_at    = date('Y-m-d H:m:s');
                    $doc->updated_at    = date('Y-m-d H:m:s');
                    $doc->store();
                    
                    
                    // se foi enviado um documento, seta a segunda aba
                    $this->form->setCurrentPage(1);
                }
            } else {
                $this->form->setCurrentPage(0);
            }           
            
            // carrega a grid
            $this->carregarDataGrid($cliente->id);
            $this->carregarContatos($cliente->id);
              
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error',"<span style='color: red'><b>Erro</b></span>".$e->getMessage());
        }
    }
 
    public function onEdit( $param) 
    {
        try {
            TTransaction::open('eventos');
            if (isset($param['key'])) {         
                $key = $param['key'];
                $cliente = new Cliente($key);
                $this->form->setData($cliente);
                $this->id_contato = '';
                // carrega a grid de documentos
                $this->carregarDataGrid($cliente->id);
                $this->carregarContatos($cliente->id);
            }
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', 'Erro :' . $e->getMessage());
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
        TForm::sendData('form_clientes', $obj);

        /*        
        $obj->PRO_ENDERECO = strtoupper( $retorno['tipo_logradouro'].' '.$retorno['logradouro']);
        $obj->PRO_BAIRRO   = strtoupper( $retorno['bairro']);
        $obj->PRO_CIDADE   = strtoupper( $retorno['cidade']);
        $obj->PRO_UF       = strtoupper( $retorno['uf']);
        */ 

    }
    
    
 
 }
