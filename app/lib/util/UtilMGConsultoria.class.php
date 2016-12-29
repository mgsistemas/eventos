<?php
class UtilMGConsultoria
{

    // assume $str esteja em UTF-8
    const UTF8_FROM = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
    const UTF8_TO   = "aaaaeeiooouucAAAAEEIOOOUUC";

    /**
     * Metodo que retorna todos os projetos cujo usuario passado pode ver
     */
    public static function get_projetos($usuario_id)
    {
        try {
            TTransaction::open('projeto');
            $repository = new TRepository('ProjetoUsuario');
            $criteria = new TCriteria;
            $criteria->add(new TFilter('usuario_id','=',$usuario_id));
            $object = $repository->load($criteria);
            //var_dump($object);
            return $object;
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error','Problemas ao executar método: UtilMGConsultoria::get_projetos');
        }
    }

    /**
     * Metodo receber string e retorna sem acento e nem espaco
     */
    public static function utf8Str($str)
    {
        $keys = array();
        $values = array();
        preg_match_all('/./u', self::UTF8_FROM, $keys);
        preg_match_all('/./u', self::UTF8_TO  , $values);
        $mapping = array_combine($keys[0], $values[0]);
        $retorno = strtr($str, $mapping);
        $retorno = str_replace(' ', '_', $retorno);
        return $retorno;
    
    }
    

}
