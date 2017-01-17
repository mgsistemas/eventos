<?php
/**
 * ReceberForm
 * @author Marcelo Gomes
 * @package app/control/financeiro
 */
class ReceberForm extends TPage
{
    
    private $form;
    private $datagrid;
    private $datagrid_doc;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Contas a Receber');
        $this->form->setName('form_Receber');
        $this->form->style = 'width: 100%';
        $this->form->appendPage('Dados da Conta a Receber');
        
        $this->form->addAction('Salvar',new TAction(array($this, 'onSave')),'fa:save blue');
        $this->form->addAction('Cancelar',new TAction(array('ReceberList', 'onReload')),'fa:table red');

        // campos
        $id                  = new TEntry('id');
        $data_lancamento     = new TDate('data_lancamento');
        $data_emissao        = new TDate('data_emissao');
        $cliente_id          = new TDBCombo('cliente_id','eventos','Cliente','id','razao_social','razao_social');
        $tipo_cobranca_id    = new TDBCombo('tipo_cobranca_id','eventos','TipoCobranca','id','descricao','descricao');
        $plano_conta_id      = new TDBCombo('plano_conta_id','eventos','PlanoConta','id','descricao','descricao');
        $data_credito        = new TDate('data_credito');
        $empresa_id          = new TDBCombo('empresa_id','eventos','Empresa','id','nome','nome');
        $codigo_barras       = new TEntry('codigo_barras');
        $data_pagamento      = new TDate('data_pagamento');
        $data_vencimento     = new TDate('data_vencimento');
        $observacao          = new THtmlEditor('observacao');
        $situacao            = new TCombo('situacao');      
        $job_id              = new TDBSeekButton('job_id','eventos','form_Receber','Job','nome','job_id','nome_job');
        $nome_job            = new TEntry('nome_job');
        $apelido             = new TEntry('apelido');
        $cnpj                = new TEntry('cnpj');
        $valor_total_job     = new TEntry('valor_total_job');
        
        // config campos
        $id->setEditable(FALSE);
        $data_lancamento->setEditable(FALSE);
        $id->setSize('80');
        $data_lancamento->setSize('100');
        $data_emissao->setSize('100');
        $cliente_id->setSize('100%');
        $cliente_id->enableSearch();
        $tipo_cobranca_id->setSize('100%');
        $tipo_cobranca_id->enableSearch();
        $plano_conta_id->setSize('100%');
        $plano_conta_id->enableSearch();
        $data_credito->setSize('100');
        $empresa_id->setSize('100%');
        $empresa_id->enableSearch();
        $codigo_barras->setSize('100%');
        $data_pagamento->setSize('100');
        $data_vencimento->setSize('100');
        $observacao->setSize('100%','250');
        $situacao->setSize('20%');
        $situacao->addItems(array('A' => 'Pendente','I' => 'Pago'));
        $apelido->setEditable(FALSE);
        $cnpj->setEditable(FALSE);
        $nome_job->setEditable(FALSE);
        $job_id->setExitAction(new TAction(array($this,'onValorTotalJob')));
        $valor_total_job->setEditable(FALSE);
        $valor_total_job->setNumericMask(2,',','.');
        $job_id->setSize('30%');
        $nome_job->setSize('70%');
        $cnpj->setSize('50%');
        $apelido->setSize('50%');
        $tipo_cobranca_id->setSize('100%');
        $codigo_barras->setSize('100%');
        $tipo_cobranca_id->addValidation('Tipo de Cobrança', new TRequiredValidator);
        $plano_conta_id->enableSearch();
        $empresa_id->enableSearch();
        $plano_conta_id->setSize('100%');
        $empresa_id->setSize('100%');
        $plano_conta_id->addValidation('Plano de Contas',new TRequiredValidator);
        $empresa_id->addValidation('Empresa Contábil', new TRequiredValidator);
        
        // config data
        $data_credito->setMask('dd/mm/yyyy');
        $data_emissao->setMask('dd/mm/yyyy');
        $data_lancamento->setMask('dd/mm/yyyy');
        $data_pagamento->setMask('dd/mm/yyyy');
        $data_vencimento->setMask('dd/mm/yyyy');
        $data_vencimento->addValidation('Data de Vencimento', new TRequiredValidator);
        $data_emissao->addValidation('Data de Emissão', new TRequiredValidator);
        
        $data_lancamento->setValue(date('d/m/Y'));  
        $data_emissao->setValue(date('d/m/Y'));
        $situacao->setValue('A');
        
        // actions
        $cliente_id->setChangeAction(new TAction(array($this,'onChangeCliente')));
        
        $this->form->addFields([ new TLabel('Código') ],
                               [ $id ] ,
                               [ new TLabel('Data Lançamento')] ,
                               [ $data_lancamento]);
        $this->form->addFields([ new TLabel('Data Emissão','red',12,'b') ],
                               [ $data_emissao ]);
        $this->form->addFields( [ new TLabel('Cliente','red',12,'b') ],
                                [ $cliente_id ],
                                [ new TLabel('CNPJ / Apelido') ] ,
                                [ $cnpj, $apelido]);      
        $this->form->addFields( [ new TLabel('JOB') ],
                                [ $job_id, $nome_job ] ,
                                [ new TLabel(' Total JOB') ],
                                [ $valor_total_job] );
        
        $this->form->addFields( [new TLabel('Tipo de Cobrança','red',12,'b')] ,
                                [ $tipo_cobranca_id ] ,
                                [ new TLabel('Código de Barras')] ,
                                [ $codigo_barras ]);                                                                                         
                
        $this->form->addFields( [ new TLabel('Plano de Contas','red',12,'b')] ,
                                [ $plano_conta_id ], 
                                [ new TLabel('Empresa Contábil','red',12,'b')] ,
                                [ $empresa_id ]);    
                                
        $this->form->addFields( [ new TLabel('Data de Vencimento','red',12,'b')],
                                [ $data_vencimento ]);
        $this->form->addFields( [ new TLabel('Data de Pagamento')] ,
                                [ $data_pagamento ]);
        $this->form->addFields( [ new TLabel('Situação','red',12,'b')],
                                [ $situacao ]);
        
        $frame = new TFrame('valores');
        $frame->setLegend('Valores');
        $frame->setIsWrapped(TRUE);
        
        // campos
        $tipo_controle_id    = new TDBCombo('tipo_controle_id','eventos','TipoControle','id','descricao','descricao');
        $nota_fiscal         = new TEntry('nota_fiscal');
        $valor_bruto         = new TEntry('valor_bruto');
        $iss                 = new TEntry('iss');
        $inss                = new TEntry('inss');
        $pis_cofins          = new TEntry('pis_cofins');
        $valor_liquido       = new TEntry('valor_liquido');
        $valor_total_receber = new TEntry('valor_total_receber');
        
        $this->form->addField($tipo_controle_id);        
        $this->form->addField($nota_fiscal);        
        $this->form->addField($valor_bruto);        
        $this->form->addField($iss);        
        $this->form->addField($inss);        
        $this->form->addField($pis_cofins);        
        $this->form->addField($valor_liquido);
        
        // config
        $nota_fiscal->setSize('100');
        $valor_bruto->setNumericMask(2,',','.', TRUE);
        $valor_bruto->setSize('120');
        $tipo_controle_id->enableSearch();
        $tipo_controle_id->setSize('300');
        $iss->setSize('120');
        $iss->setNumericMask(2,',','.', TRUE);
        $inss->setSize('120');
        $inss->setNumericMask(2,',','.',TRUE);
        $iss->setExitAction(new TAction(array($this,'onCalcularTotal')));
        $pis_cofins->setSize('120');
        $pis_cofins->setNumericMask(2,',','.', TRUE);
        $valor_liquido->setSize('120');
        $valor_liquido->setNumericMask(2,',','.', TRUE);
        $inss->setExitAction(new TAction(array($this,'onCalcularTotal')));
        $pis_cofins->setExitAction(new TAction(array($this,'onCalcularTotal')));
        $valor_bruto->setExitAction(new TAction(array($this,'onCalcularTotal')));
        
        
        $table = new TTable;
        $row = $table->addRow();
        $row->addCell(new TLabel('Controle'));
        $row->addCell(new TLabel('Nota Fiscal'));
        $row->addCell(new TLabel('Valor Bruto'));
        $row->addCell(new TLabel('ISS'));
        $row->addCell(new TLabel('INSS'));
        $row->addCell(new TLabel('PIS/COFINS'));
        $row->addCell(new TLabel('LÍQUIDO'));
        
        $row = $table->addRow();
        $row->addCell($tipo_controle_id);
        $row->addCell($nota_fiscal);
        $row->addCell($valor_bruto);
        $row->addCell($iss);
        $row->addCell($inss);
        $row->addCell($pis_cofins);
        $row->addCell($valor_liquido);
        
        $button_add_controle = new TButton('add_controle');
        $button_add_controle->setValue('Adiciona');
        $button_add_controle->setImage('ico_add.png');
        $action_add_controle = new TAction(array($this,'onAddControle'));
        $button_add_controle->setAction($action_add_controle,'Adicionar');
        $row->addCell($button_add_controle);
        $this->form->addField($button_add_controle);
        
        $frame->add($table);
        
        // cria a data grid e preenche
        $this->datagrid = new TDataGrid;
        
        $column_id            = new TDataGridColumn('id', 'Nr.Controle', 'right',20);
        $column_controle      = new TDataGridColumn('tipo_controle->descricao', 'Controle', 'left',250);
        $column_nota_fiscal   = new TDataGridColumn('nota_fiscal','Nota Fiscal','center',100);
        $column_valor_bruto   = new TDataGridColumn('valor_bruto','Valor Bruto','right',120);
        $column_iss           = new TDataGridColumn('iss','ISS','right',120);
        $column_inss          = new TDataGridColumn('inss','INSS','right',120);
        $column_pis_cofins    = new TDataGridColumn('pis_cofins','PIS/COFINS','right',120);
        $column_valor_liquido = new TDataGridColumn('valor_liquido','Valor Líquido','right',120);
       
        
        
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_controle);
        $this->datagrid->addColumn($column_nota_fiscal);
        $this->datagrid->addColumn($column_valor_bruto);
        $this->datagrid->addColumn($column_iss);
        $this->datagrid->addColumn($column_inss);
        $this->datagrid->addColumn($column_pis_cofins);
        $this->datagrid->addColumn($column_valor_liquido);
        
        // transformer
        $column_valor_bruto->setTransformer(function($value, $object, $row){
            return number_format($value,2,',','.');
        });
        
        $column_iss->setTransformer(function($value, $object, $row){
            if(!empty($value)) {
                return number_format($value,2,',','.');
            } else {
                return number_format(0,2,',','.');
            }
        });
        $column_inss->setTransformer(function($value, $object, $row){
            if (!empty($value)) {
                return number_format($value,2,',','.');
            } else {
                return number_format(0,2,',','.');
            }
        });
        $column_pis_cofins->setTransformer(function($value, $object, $row){
            if (!empty($value)) {
                return number_format($value,2,',','.');
            } else {
                return number_format(0,2,',','.');
            }
        });
        $column_valor_liquido->setTransformer(function($value, $object, $row){
            return number_format($value,2,',','.');
        });
        
        
        // acao exclusao data grid
        $action_del_item = new TDataGridAction(array($this,'onExcluirItemControle'));
        $action_del_item->setImage('ico_delete.png');
        $action_del_item->setField('id');
        $action_del_item->setParameter('rec',$id);
        
        $this->datagrid->addAction($action_del_item);
        $this->datagrid->disableDefaultClick();
        
        // cria o modelo
        $this->datagrid->createModel();
        
        $frame->add($this->datagrid);                                    
        
        $this->form->addFields( [ $frame ]);
        

        // valor total a receber
        $valor_total_receber->setEditable(FALSE);
        $valor_total_receber->setNumericMask(2,',','.');
        $valor_total_receber->setValue(0);
        $valor_total_receber->style = 'font-size: 16px; font-weight:bold;';
        $this->form->addFields( [],[],[ new TLabel('Valor Total')], [$valor_total_receber]);


        // documentos ###################################################################################
        $this->form->appendPage('Documento');
        
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
        
        
        $this->datagrid_doc->createModel();
        $this->form->addFields( [$this->datagrid_doc] );
        
        
                                                                                                                        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);
        parent::add($vbox);
        
    }
    
    /**
     * Adicionar um novo registro de controle
     */
    public function onAddControle ($param)
    {
        try {
            TTransaction::open('eventos');
            $data = $this->form->getData();
            
            $erro = FALSE;
            $msg_erro = '';
            
            $valor_bruto   = str_replace('.', '', $param['valor_bruto']);
            $iss           = str_replace('.', '', $param['iss']);
            $inss          = str_replace('.', '', $param['inss']);
            $pis_cofins    = str_replace('.', '', $param['pis_cofins']);
            $valor_liquido = str_replace('.', '', $param['valor_liquido']);
            
            $valor_bruto   = (double) str_replace(',', '.', $valor_bruto);
            $iss           = (double) str_replace(',', '.', $iss);
            $inss          = (double) str_replace(',', '.', $inss);
            $pis_cofins    = (double) str_replace(',', '.', $pis_cofins);
            $valor_liquido = $valor_bruto - ($iss + $inss + $pis_cofins);
                
            if ($param['id'] == NULL) {
                $msg_erro = 'Para inserir um novo controle é preciso criar contas a Receber primeiro!';
                new TMessage('error',$msg_erro);
                return;
            }
            
            if ($data->tipo_controle_id == NULL) {
                $erro = TRUE;
                $msg_erro .= '<br>Tipo de Controle é obrigatório!';
            }
            
            if ($data->valor_bruto == NULL) {
                $erro = TRUE;
                $msg_erro .= '<br>Valor Bruto a receber é obrigatório!';
            }
            
            if ($erro) {
                new TMessage('error',$msg_erro);
                return;
            }
            
            // salvar
            $receber_controle = new ReceberControle;
            $receber_controle->receber_id       = $data->id;
            $receber_controle->nota_fiscal      = $data->nota_fiscal;
            $receber_controle->tipo_controle_id = $data->tipo_controle_id;
            $receber_controle->valor_bruto      = $valor_bruto;
            $receber_controle->iss              = $iss;
            $receber_controle->inss             = $inss;
            $receber_controle->pis_cofins       = $pis_cofins;
            $receber_controle->valor_liquido    = $valor_liquido;
            
            $receber_controle->store();
            
            // limap os campos para nova entrada
            $data->tipo_controle_id = NULL;
            $data->nota_fiscal      = NULL;
            $data->valor_bruto      = NULL;
            $data->iss              = NULL;
            $data->inss             = NULL;
            $data->pis_cofins       = NULL;
            $data->valor_liquido    = NULL;
            
            TTransaction::close();
            $this->form->setData($data);
            
            $this->onCarregarGrid($data->id);
            $this->onCalcularValorTotalAReceber($data->id);
            $this->onCarregarDocumentos($data->id);
            $this->onCarregarRecebimento($data->id);
            
        } catch (Exception $e) {
            new TMessage('error',$e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Metodo para excluir um item de controle
     */
    public function onExcluirItemControle($param)
    {
        TTransaction::open('eventos');
        $ctrl_receber = new ReceberControle($param['id']);
        $receber = new Receber($ctrl_receber->receber_id);
        $this->form->setData($receber);
        TTransaction::close();
        
        $this->onCalcularValorTotalAReceber($receber->id);
        $this->onCarregarDocumentos($receber->id);
        $this->onCarregarRecebimento($receber->id);
        $this->onCarregarGrid($receber->id);
        
        $sim = new TAction(array($this,'onDeletarItem'));
        $sim->setParameter('key',$param['id']);
        
        new TQuestion('Confirma a exclusão do Controle?',$sim);
    
    }
    
    /**
     * Metodo que exclui o registro fisicamente
     */
    public function onDeletarItem($param)
    {
        try {
            TTransaction::open('eventos');
            $ctrl_receber = new ReceberControle($param['key']);
            $receber = new Receber($ctrl_receber->receber_id);
            $this->form->setData($receber);
            
            // excluir
            $ctrl_receber->delete();
            
            TTransaction::close();
            
            $this->onCarregarGrid($receber->id);
            $this->onCalcularValorTotalAReceber($receber->id);
            $this->onCarregarDocumentos($receber->id);
            $this->onCarregarRecebimento($receber->id);
            
        } catch (Exception $e) {
            new TMessage('error',$e->getMessage());
        }
    }
    
    /**
     * Metodo responsavel por calcular o valor total a receber
     * Este valor é a soma da coluna "valor_liquido" do modelo ReceberControle
     */
    public function onCalcularValorTotalAReceber($key)
    {
        try {
            // abre a transacao
            TTransaction::open('eventos');
            
            // obtem os objetos
            $valor = 0;
            
            $repository = new TRepository('ReceberControle');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('receber_id','=',$key));
            $objects = $repository->load($criteria);

            foreach ($objects as $object) {
                $valor = $valor + $object->valor_liquido;
            }
            
            $data = new stdClass;
            
            $data->valor_total_receber = number_format($valor,2,',','.');
            
            $this->form->setData($data);
            
            TTransaction::close();
        } catch (Exception $e){
            new TMessage('error',$e->getMessage());
        }
    }

    /**
    * Metodo que calcula o valor total de controle
    */
    public static function onCalcularTotal($param)
    {
        $valor_bruto   = str_replace('.', '', $param['valor_bruto']);
        $iss           = str_replace('.', '', $param['iss']);
        $inss          = str_replace('.', '', $param['inss']);
        $pis_cofins    = str_replace('.', '', $param['pis_cofins']);
        
        $valor_bruto   = (double) str_replace(',', '.', $valor_bruto);
        $iss           = (double) str_replace(',', '.', $iss);
        $inss          = (double) str_replace(',', '.', $inss);
        $pis_cofins    = (double) str_replace(',', '.', $pis_cofins);
        
        $obj = new stdClass;
        $impostos = $iss + $inss + $pis_cofins;
        $total = $valor_bruto - $impostos;
        $obj->valor_liquido = number_format($total,2,'.',',');
        
        TForm::sendData('form_Receber',$obj);
        
    }
    
    
    /**
     * Metodo que carrega a grid de registtros de controles
     */
    public function onCarregarGrid($key)
    {
        try {
            $this->datagrid->clear();
            TTransaction::open('eventos');
            // carrega datagrid
            $repository = new TRepository('ReceberControle');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('receber_id','=',$key));
            //var_dump($criteria);
            $objects = $repository->load($criteria);
            //var_dump($objects);
            foreach($objects as $object) {
                $this->datagrid->addItem($object);                    
            }
            TTransaction::close();
        } catch (Exception $e){
            new TMessage('error',$e->getMessage());
        }
    }
    
    /**
     * Metodo edit
     */
    public function onEdit ($param)
    {
    
        try {
        
            if (isset($param['key'])) {
                $key = $param['key'];
                if ($key != '') {
                    TTransaction::open('eventos');
                    $receber = new Receber($key);
                    
                    $receber->cnpj = $receber->cliente->cpf_cnpj;
                    $receber->apelido = $receber->cliente->apelido->descricao;
                    $receber->nome_job = $receber->job->nome;
                    $receber->valor_total_job = number_format(2,2,'.',',');
                    $receber->data_credito = TDate::date2br($receber->data_credito);
                    $receber->data_lancamento = TDate::date2br($receber->data_lancamento);
                    $receber->data_emissao = TDate::date2br($receber->data_emissao);
                    $receber->data_pagamento = TDate::date2br($receber->data_pagamento);
                    $receber->data_vencimento = TDate::date2br($receber->data_vencimento);
                    
                    
                    // carrega a grid
                    $this->onCarregarGrid($receber->id);
                    $this->onCalcularValorTotalAReceber($receber->id);
                    $this->onCarregarDocumentos($receber->id);
                    
                    $this->form->setData($receber);
                    TTransaction::close();
                }
            
            }
        
        } catch (Exception $e){
            new TMessage('error',$e->getMessage());
        }
    
    }
    
    /**
     * Salvar os dados
     */
    public function onSave ($param)
    {
        try {
            TTransaction::open('eventos');
            
            $receber = $this->form->getData('Receber');
            
            $this->form->validate();

            $receber->data_credito = TDate::date2us($receber->data_credito);
            $receber->data_emissao = TDate::date2us($receber->data_emissao);
            $receber->data_lancamento = TDate::date2us($receber->data_lancamento);
            $receber->data_pagamento = TDate::date2us($receber->data_pagamento);
            $receber->data_vencimento= TDate::date2us($receber->data_vencimento);
            
            $receber->login = TSession::getValue('login');
            $receber->data_atualizacao = date('Y-m-d H:i:s');
            
            $receber->store();            

            if (isset($param['file'])) {   
                if (!empty($param['file'])) {
                    // verifica se a descricao foi informada
                    if (empty($param['descricao_file'])) {
                        new TMessage('error','Erro : Descrição do Documento é obrigatória!');
                        $this->form->setData($receber);
                        
                        $this->onCalcularValorTotalAReceber($receber->id);
                        $this->onCarregarDocumentos($receber->id);
                        $this->onCarregarRecebimento($receber->id);
                        
                        $this->form->setCurrentPage(1);
                        return;
                    }         
                    $source_file   = 'tmp/'.$param['file'];
                    $file_name = 'rec_'.$receber->id.'-'.UtilMGConsultoria::utf8Str($param['file']);
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
                    $doc->doc_id        = $receber->id;
                    $doc->tipo          = 'R';
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



            $receber->data_credito = TDate::date2br($receber->data_credito);
            $receber->data_emissao = TDate::date2br($receber->data_emissao);
            $receber->data_lancamento = TDate::date2br($receber->data_lancamento);
            $receber->data_pagamento = TDate::date2br($receber->data_pagamento);
            $receber->data_vencimento= TDate::date2br($receber->data_vencimento);
            
            $this->form->setData($receber);
            
            TTransaction::close();
            
            
            // recarregar tudo
            $this->onCarregarDocumentos($receber->id);
            $this->onCarregarGrid($receber->id);
            $this->onCalcularValorTotalAReceber($receber->id);
            
            
        } catch (Exception $e) {
            new TMessage('error',$e->getMessage());
            TTransaction::rollback();
        }
    }
    
    public function onCarregarDocumentos($key)
    {
        try {
            TTransaction::open('eventos');
            $this->datagrid_doc->clear();
            $repository = new TRepository('Documento');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('doc_id','=',$key));
            $criteria->add(new TFilter('tipo','=','R'));
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
    
    
    public static function onValorTotalJob($param)
    {
        try {
            // TODO  implementar rotina apos implementar orcamento
            $obj = new stdClass;
            $obj->valor_total_job = number_format(2,2,',','.');
            TForm::sendData('form_Receber',$obj);
        } catch (Exception $e){
        }
    }
    
    public static function onChangeCliente($param)
    {
        try {
            TTransaction::open('eventos');
            $cliente = new Cliente($param['cliente_id']);
            $obj = new stdClass;
            $obj->apelido = $cliente->apelido->descricao;
            $obj->cnpj = $cliente->cpf_cnpj;
            TForm::sendData('form_Receber',$obj);
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error',$e->getMessage());
            
        }
       
    }

    /**
     * Metodo que carrega os dados de receber
     */
    public function onCarregarRecebimento($key)
    {
        try {
            TTransaction::open('eventos');
            $receber = new Receber($key);
            
            $receber->nome_job = $receber->job->nome;
            $receber->cnpj = $receber->cliente->cpf_cnpj;
            $receber->apelido = $receber->cliente->apelido->descricao;
            $receber->data_emissao = TDate::date2br($receber->data_emissao);
            $receber->data_credito = TDate::date2br($receber->data_credito);
            $receber->data_lancamento = TDate::date2br($receber->data_lancamento);
            $receber->data_pagamento = TDate::date2br($receber->data_pagamento);
            $receber->data_vencimento = TDate::date2br($receber->data_vencimento);
            
            $this->form->setData($receber);
            TTransaction::close();
        } catch (Exception $e){
            new TMessage('error',$e->getMessage());
        }
    }

    /**
     * Caixa de confirmação de exclusao
     */
    public function onDeleteDoc($param)
    {
         $action1  = new TAction(array($this, 'onExcluirDoc'));
         $action1->setParameter('key', $param['key']);
         new TQuestion('Confirma a Exclusão',$action1);
    }
    
    /**
     * Metodo que exclui o arquivo
     */
    public function onExcluirDoc($param)
    {
        try {
            TTransaction::open('eventos');
            $doc = new Documento($param['key']);
            $recId = $doc->doc_id;
            $nomeDoc = $doc->nomedocumento;
            $doc->delete();
            TTransaction::close();
            $this->onCalcularValorTotalAReceber($recId);
            $this->onCarregarGrid($recId);
            $this->onCarregarDocumentos($recId);
            $this->onCarregarRecebimento($recId);
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
            $this->onCarregarRecebimento($doc->doc_id);
             
            $this->form->setCurrentPage(1);
            TTransaction::close();
        } catch (Exception $e){
            new TMessage('error','Erro : ' . $e->getMessage());
        }
    }


}
