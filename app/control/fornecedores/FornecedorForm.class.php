<?php

/**
 * FornecedorForm
 * @author Marcelo Gomes
 */
class FornecedorForm extends TPage
{
    private $form;
    private $datagrid_doc;
    private $datagrid_contatos;
    
    public function __construct() 
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Cadastro de Fornecedores');
        $this->form->setName('form_fornecedores');
        
        // dados da pagina princiaç
        $label1 = new TLabel('Dados Principais','#7D78B6',12,'bi');
        $label1->style = 'text-align:left;border-bottom:1px solid #0c0c0c;width:100%';
        
        $this->form->appendPage('Dados Cadastrais');
        $this->form->addContent( [ $label1 ]);
        
        //**** CAMPOS *****
        $id                 = new TEntry('id');
        $razao_social       = new TEntry('razao_social');
        $nome_fantasia      = new TEntry('nome_fantasia');
        $tipo_pessoa_id     = new TDBCombo('tipo_pessoa_id','eventos','TipoPessoa','id','descricao');
        $cpf_cnpj           = new TEntry('cpf_cnpj');
        $ie                 = new TEntry('ie');
        $segmento_id        = new TDBCombo('segmento_id','eventos','Segmento','id','descricao','descricao');
        $cep                = new TEntry('cep');
        $logradouro         = new TEntry('logradouro');
        $complemento        = new TEntry('complemento');
        $bairro             = new TEntry('bairro');
        $cidade             = new TEntry('cidade');
        $estado             = new TEntry('estado');
        $home_page          = new TEntry('home_page');
        $regime_tributario  = new TEntry('regime_tributario');
        $banco              = new TEntry('banco');
        $agencia            = new TEntry('agencia');
        $conta_corrente     = new TEntry('conta_corrente');
        $tipo_conta         = new TCombo('tipo_conta');
        $observacao         = new THtmlEditor('observacao');
        $situacao           = new TCombo('situacao');
        
        // config
        $id->setEditable(FALSE);
        $id->setSize('80');
        $situacao->setEditable(FALSE);
        $situacao->setSize('120');
        $situacao->addItems(array('A'=>'Ativo','I'=>'Inativo','B'=>'Bloqueado'));
        $razao_social->forceUpperCase();
        $razao_social->setSize('100%');
        $nome_fantasia->forceUpperCase();
        $nome_fantasia->setSize('100%');
        $cpf_cnpj->setSize('50%');
        $tipo_pessoa_id->setSize('50%');
        $ie->setSize('50%');
        $segmento_id->setSize('100%');
        $segmento_id->enableSearch();
        $cep->setSize('20%');
        $cep->setMask('99999-999');
        $logradouro->setSize('70%');
        $logradouro->forceUpperCase();
        $logradouro->setMaxLength(200);
        $complemento->setSize('30%');
        $complemento->forceUpperCase();
        $complemento->setMaxLength(100);
        $complemento->placeholder = 'Complemento';
        $bairro->setSize('100%');
        $bairro->forceUpperCase();
        $cidade->setSize('80%');
        $cidade->forceUpperCase();
        $estado->setSize('20%');
        $estado->setMask('AA');
        $estado->forceUpperCase();
        $home_page->setSize('100%');
        $home_page->setMaxLength(200);
        $home_page->forceLowerCase();
        $regime_tributario->setSize('100%');
        $regime_tributario->setMaxLength(200);
        $regime_tributario->forceUpperCase();
        $banco->setSize('100%');
        $banco->setMaxLength(45);
        $agencia->setSize('100%');
        $agencia->setMaxLength(45);
        $conta_corrente->setSize('100%');
        $conta_corrente->setMaxLength(45);
        $tipo_conta->setSize('100%');
        $tipo_conta->addItems(array('C'=>'Conta Corrente','P'=>'Poupança'));
        $observacao->setSize('100%','200');
        
        if (empty($id)){
            $situacao->setValue('A');
        }
        
        //acao do cep
        $action_cep = new TAction(array($this,'onCep'));
        $cep->setExitAction($action_cep);
        
                
        // scripts
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
        
        // add campos no form
        
        $this->form->addFields ( [ new TLabel('Código')],
                                 [ $id ], 
                                 [ new TLabel('Situação')],
                                 [ $situacao ] );
                                 
        // label
        $lbl_razao_social = new TLabel('Razão Social','red',12,'b');                                 
        $lbl_nome_fanatasia = new TLabel('Nome Fantasia','red',12,'b');                                 
        $this->form->addFields ( [ $lbl_razao_social ],
                                 [ $razao_social ],
                                 [ $lbl_nome_fanatasia],
                                 [ $nome_fantasia ] );    
        
        $this->form->addFields ( [ new TLabel('Tipo de Pessoa')],
                                 [ $tipo_pessoa_id ],
                                 [ new TLabel('CPF/CNPJ')],
                                 [ $cpf_cnpj] );
                                                                                              
        $this->form->addFields ([ new TLabel('Segmento','red',12,'b')] ,
                                [ $segmento_id ], 
                                [ new TLabel('Inscrição Estadual') ],
                                [ $ie ]);
        
        $this->form->addFields ( [new TLabel('CEP')] ,
                                 [$cep],
                                 [new TLabel('Logradouro')],
                                 [ $logradouro, $complemento]);
                                 
        $this->form->addFields ( [new TLabel('Bairro')],
                                 [$bairro],
                                 [new TLabel('Cidade / UF')],
                                 [$cidade, $estado]);                                 
        
        $this->form->addFields ( [ new TLabel('Regime Tributário')],
                                 [ $regime_tributario ], 
                                 [ new TLabel('Home Page')] ,
                                 [ $home_page]);
                                 
        $this->form->addFields ( [ new TLabel('Banco')],
                                 [ $banco ],
                                 [ new TLabel('Agência') ],
                                 [ $agencia]);
                                 
        $this->form->addFields ( [new TLabel('Conta Corrente')],
                                 [ $conta_corrente],
                                 [new TLabel('Tipo de Conta')],
                                 [ $tipo_conta ]);     

        $this->form->addFields ( [ new TLabel('Observação')] ,
                                 [ $observacao ] );
                                        
                                        
        // pagina 2 DOCUMENTOS
        $label2 = new TLabel('Enviar Documentos','#7D78B6',12,'bi');
        $label2->style = 'text-align:left;border-bottom:1px solid #0c0c0c;width:100%';
        
        $this->form->appendPage('Documentos');
        $this->form->addContent( [ $label2 ]);
        
        $file           = new TFile('file');
        $descricao_file = new TEntry('descricao_file');

        $this->datagrid_doc = new BootstrapDatagridWrapper(new TDataGrid);
        $column_id            = new TDataGridColumn('id','#ID','center',50);
        $column_descricao     = new TDataGridColumn('descricao','Descricao','left',300);
        $column_nomedocumento = new TDataGridColumn('nomedocumento','Nome do Arquivo','left',400);
        $column_login         = new TDataGridColumn('login','Atualizado por','center',200);
        $column_updated       = new TDataGridColumn('updated_at','Em','center',180);
        
        $this->datagrid_doc->style = 'width: 100%';
        $this->datagrid_doc->datatable = 'true';
        
        $this->datagrid_doc->addColumn($column_id);
        $this->datagrid_doc->addColumn($column_descricao);
        $this->datagrid_doc->addColumn($column_nomedocumento);
        $this->datagrid_doc->addColumn($column_login);
        $this->datagrid_doc->addColumn($column_updated);
        $this->datagrid_doc->disableDefaultClick();
        
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
        
        $this->datagrid_doc->addAction($action_del);
        $this->datagrid_doc->addAction($action_down);
        
        // config
        $file->setSize('100%');
        $descricao_file->setSize('100%');

        // adiciona ao form
        $this->form->addFields( [ new TLabel('Descrição','red',12,'b')] ,
                                [ $descricao_file ],
                                [ new TLabel('Arquivo')],
                                [ $file ]);
                                
        
        $this->datagrid_doc->createModel();
        $this->form->addFields( [$this->datagrid_doc] );
                                         

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
        
                                        
        // botoes de acao
        $this->form->addAction('Salvar',new TAction(array($this,'onSave')),'fa:save blue fa-fw');      
        $this->form->addAction('Cancelar',new TAction(array('FornecedorList','onReload')),'fa:table red fa-fw');                                  
                                                                                                      
        // add container
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);
        $vbox->add($tableScriptPessoa);
        parent::add($vbox);
        
        
        
    }
    
    
    /**
     * Carregar dados do fornecedor
     */
    public function carregarDadosFornecedor($id)
    {
        try {
        
            TTransaction::open('eventos');
            $for = new Fornecedor($id);
            $this->form->setData($for);
            
            TTransaction::close();
        
        } catch (Exception $e) {
            new TMessage('error','Erro : ' . $e->getMessage());
        }
    }
    
    
    /**
     * Metodo para editar um contato
     */
    public function onEditContato($param)
    {
        try {
            TTransaction::open('eventos');
            $contato = new FornecedorContato($param['key']);
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
            
            $this->carregarDadosFornecedor($contato->fornecedor_id);
            $this->carregarDocumentos($contato->fornecedor_id);
            $this->carregarContatos($contato->fornecedor_id);
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
            $contato = new FornecedorContato($param['key']);
            $fornecedor_id = $contato->fornecedor_id;
            $this->form->setCurrentPage(2);
            $this->carregarDocumentos($fornecedor_id);
            $this->carregarDadosFornecedor($fornecedor_id);
            $this->carregarContatos($fornecedor_id);
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
            $contato = new FornecedorContato($param['key']);
            $forId = $contato->fornecedor_id_id;
            $contato->delete();
            $this->carregarDocumentos($forId);
            $this->carregarDadosFornecedor($forId);
            $this->carregarContatos($forId);
            TTransaction::close();
            $this->form->setCurrentPage(2);
        } catch (Exception $e){
            new TMessage('error','Erro : ' . $d->getMessage());
            TTransaction::rollback();
        }
        
    }
     
    /**
     * Adiciona contato
     */
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
                $contatos = new FornecedorContato($data->id_contato);
            } else {
                $contatos = new FornecedorContato;
            }
            $contatos->fornecedor_id   = $data->id;
            $contatos->nome            = $data->nome;
            $contatos->departamento_id = $data->departamento_id;
            $contatos->email           = $data->email;
            $contatos->telefone        = $data->telefone;
            $contatos->celular         = $data->celular;
            $contatos->diames          = $data->diames;
            $contatos->situacao        = $data->situacao_contato;
            $contatos->login           = TSession::getValue('login');
            $contatos->updated_at      = date('Y-m-d H:i:s');
            
            //var_dump($grava);
            if ($grava) {
                $contatos->store();
            } else {
                return;
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
            
            $this->carregarContatos($data->id);
            $this->carregarDocumentos($data->id);
            $this->carregarDadosFornecedor($data->id);
            
        } catch (Exception $e){
            new TMessage('error','Erro : ' . $e->getMessage());
        }
    
    }
    
    /**
     * Carrega a grid de fornecedores
     */
    public function carregarContatos($key)
    {
        try {
        
            TTransaction::open('eventos');
            
            $repository = new TRepository('FornecedorContato');
            $criteria = new TCriteria;
            
            $criteria->add(new TFilter('fornecedor_id','=',$key));
            $contatos = $repository->load($criteria);
            
            //var_dump($key);
            $this->datagrid_contatos->clear();
            
            
            if ($contatos) {
                foreach($contatos as $contato) {
                    //var_dump($contato);
                    $con = new stdClass;
                    $con->id_contato           = $contato->id;
                    $con->fornecedor_id        = $contato->fornecedor_id;
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
 
    
    public function onEdit ($param )
    {
        try {
            if (isset($param['key'])) {
                TTransaction::open('eventos');
                $object = new Fornecedor($param['key']);
                $this->form->setData($object);
                $this->carregarDocumentos($object->id);
                $this->carregarDadosFornecedor($object->id);
                $this->carregarContatos($object->id);
                TTransaction::close();
            }
        } catch (Exception $e) {
            new TMessage('error',$e->getMesage());
        }
    
    }
    
    public function carregarDocumentos($key)
    {
        try {
            TTransaction::open('eventos');
            $this->datagrid_doc->clear();
            $repository = new TRepository('Documento');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('doc_id','=',$key));
            $criteria->add(new TFilter('tipo','=','F'));
            $docs = $repository->load($criteria);
            foreach($docs as $doc){
                $obj = new stdClass;
                $obj->id = $doc->id;
                $obj->descricao = $doc->descricao;
                $obj->nomedocumento = $doc->nomedocumento;
                $obj->login = $doc->login;
                $obj->updated_at = $doc->updated_at;
                $this->datagrid_doc->addItem($obj);
            }
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error','Erro : ' . $e->getMessage());
        }
    }
    
    public function onSave ($param) 
    {
        try {
            TTransaction::open('eventos');
                    
            $this->form->validate();          
            
            $data = $this->form->getData();
            $fornecedor = $this->form->getData('Fornecedor');
            $fornecedor->store();
            $this->form->setData($fornecedor);
            
            if (isset($param['file'])) {   
                if (!empty($param['file'])) {
                    // verifica se a descricao foi informada
                    if (empty($param['descricao_file'])) {
                        new TMessage('error','Erro : Descrição do Documento é obrigatória!');
                        $this->form->setData($fornecedor);
                        //$this->carregarDadosCliente($fornecedor->id);
                        $this->carregarDocumentos($fornecedor->id);
                        $this->form->setCurrentPage(1);
                        return;
                    }         
                    $source_file   = 'tmp/'.$param['file'];
                    $file_name = 'f_'.$data->id.'-'.UtilMGConsultoria::utf8Str($param['file']);
                    //$target_file   = 'storage/' . $param['file'];
                    $target_file   = 'files/docs/' . $file_name;
                    $finfo         = new finfo(FILEINFO_MIME_TYPE);
                    
                    // if the user uploaded a source file
                    if (file_exists($source_file) AND ($finfo->file($source_file) == 'image/png' OR $finfo->file($source_file) == 'image/jpeg'))
                    {
                        // move to the target directory
                        rename($source_file, $target_file);
                    }
                    
                    // grava o documento
                    $doc = new Documento;
                    $doc->doc_id        = $data->id;
                    $doc->tipo          = 'F';
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
            $this->carregarDocumentos($fornecedor->id);
            //$this->carregarContatos($cliente->id);
            
            
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error',$e->getMessage());
            TTransaction::rollback();
        }
    }
    
    public function onDeleteDoc()
    {
    }
    
    public function onDownload()
    {
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
        TForm::sendData('form_fornecedores', $obj);

        /*        
        $obj->PRO_ENDERECO = strtoupper( $retorno['tipo_logradouro'].' '.$retorno['logradouro']);
        $obj->PRO_BAIRRO   = strtoupper( $retorno['bairro']);
        $obj->PRO_CIDADE   = strtoupper( $retorno['cidade']);
        $obj->PRO_UF       = strtoupper( $retorno['uf']);
        */ 

    }
    
    
    
}
