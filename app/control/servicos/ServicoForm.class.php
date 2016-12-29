<?php

class ServicoForm extends TPage
{
    private $form;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Cadastro de Serviços');
        
        $this->form->appendPage('Serviço');
        
        $id                = new TEntry('id');
        $descricao         = new TEntry('descricao');
        $grupo_servico_id  = new TDBCombo('grupo_servico_id','eventos','GrupoServico','id','descricao','descricao');
        $grupo_trabalho_id = new TDBCombo('grupo_trabalho_id','eventos','GrupoTrabalho','id','descricao','descricao');
        $segmento_id       = new TDBCombo('segmento_id','eventos','Segmento','id','descricao','descricao');
        $situacao          = new TCombo('situacao');
        $imagem            = new TFile('imagem');
        
        
        // config
        $id->setEditable(FALSE);
        $id->setSize('80');
        $descricao->setSize('100%');
        $descricao->forceUpperCase();
        $descricao->addValidation('Descrição',new TRequiredValidator);
        $grupo_servico_id->enableSearch();
        $grupo_servico_id->setSize('100%');
        $grupo_servico_id->addValidation('Grupo Fiscal', new TRequiredValidator);
        $grupo_trabalho_id->enableSearch();
        $grupo_trabalho_id->setSize('100%');
        $grupo_trabalho_id->addValidation('Grupo de Trabalho', new TRequiredValidator);
        $segmento_id->enableSearch();
        $segmento_id->setSize('100%');
        $segmento_id->addValidation('Segmento',new TRequiredValidator);
        $situacao->setSize('100%');
        $situacao->addItems(array('A'=>'Ativo','I'=>'Inativo'));
        $situacao->addValidation('Situação',new TRequiredValidator);
        $imagem->setSize('50%');
        
                
        // labels
        $lbl_descricao = new TLabel('Descrição');
        $lbl_descricao->style = 'color:red; font-weight: bold;';
        $lbl_grupo_servico_id = new TLabel('Grupo Fiscal');
        $lbl_grupo_servico_id->style = 'color:red; font-weight: bold;';
        $lbl_grupo_trabalho_id = new TLabel('Grupo de Trabalho');
        $lbl_grupo_trabalho_id->style = 'color:red; font-weight: bold;';
        $lbl_segmento_id = new TLabel('Segmento');
        $lbl_segmento_id->style = 'color:red; font-weight: bold;';
        $lbl_situacao = new TLabel('Situação');
        $lbl_situacao->style = 'color:red; font-weight: bold;';
        
        
        // add fields
        $this->form->addFields( [ new TLabel('Código') ],
                                [ $id ]);
                                
        $this->form->addFields( [ $lbl_descricao ],
                                [ $descricao ]);
                                
        $this->form->addFields( [ $lbl_grupo_servico_id ],
                                [ $grupo_servico_id ],
                                [ $lbl_grupo_trabalho_id ],
                                [ $grupo_trabalho_id ]);
                                
        $this->form->addFields( [ $lbl_segmento_id ], 
                                [ $segmento_id ],
                                [ $lbl_situacao ],
                                [ $situacao ]);        
                                
        $this->form->addFields( [ new TLabel('Foto') ],
                                [ $imagem ]);                                                        
        
        $this->form->addAction('Salvar',new TAction(array($this,'onSave')),'fa:save blue fa-fw');
        $this->form->addAction('Cancelar', new TAction(array('ServicoList','onReload')),'fa:table red fa-fw');
        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);
        parent::add($vbox);   
            
        
    }
    
    /**
     * Carrega um objeto para edicao
     */
    public function onEdit( $param ) 
    {
        try {
            if (isset($param['key'])) {
                TTransaction::open('eventos');
                $key = $param['key'];
                $servico = new Servico($key);
                $this->form->setData($servico);
                TTransaction::close();
                $image = new TImage($servico->imagem);
                $image->style = 'width: 50%';
                $sep = new TLabel('');
                $sep->setSize('30%');
                $this->form->addFields( [$sep] , [$image] );
            }
        } catch (Exception $e) {
            new TMessage('error',$e->getMessage());
        }
        
    }

    /**
     * Persiste o objeto no banco
     */    
    public function onSave( $param )
    {
        try {
            TTransaction::open('eventos');

            $this->form->validate();

            $data = $this->form->getData();
            $servico = new Servico;
            $servico->fromArray( (array) $data);
            $servico->login = TSession::getValue('login');
            $servico->data_atualizacao = date('Y-m-d H:i:s');
            $servico->store();

            $servico = new Servico($servico->id);
            
            if ($servico->imagem != null)
            {
                $source_file   = 'tmp/'.$servico->imagem;
                $file_name     = 'servico-'.$servico->id.UtilMGConsultoria::utf8Str($servico->imagem);
                $target_file   = 'app/images/'.$file_name;
                $finfo         = new finfo(FILEINFO_MIME_TYPE);
                
                // if the user uploaded a source file
                if (file_exists($source_file) AND ($finfo->file($source_file) == 'image/png' OR $finfo->file($source_file) == 'image/jpeg'))
                {
                    // move to the target directory
                    rename($source_file, $target_file);
                    try
                    {
                        TTransaction::open('eventos');
                        // update the photo_path
                        $servico->imagem = 'app/images/'.$file_name;
                        $servico->store(); 
                        TTransaction::close();
                    }
                    catch (Exception $e) // in case of exception
                    {
                        new TMessage('error', $e->getMessage());
                        TTransaction::rollback();
                    }
                }                

                $image = new TImage($servico->imagem);
                $image->style = 'width: 50%';
                $sep = new TLabel('');
                $sep->setSize('30%');
                $this->form->addFields( [ $sep ],
                                        [$image] );
            }
            
            
            
            $this->form->setData($servico);
            TTransaction::close();
            
            new TMessage('info','Registro Salvo com Sucesso');
        
        } catch (Exception $e) {
            new TMessage('error',$e->getMessage());
            TTransaction::rollback();
        }
    }
    
}
