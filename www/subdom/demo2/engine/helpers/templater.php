<?php
/*
* Tento soubor slouží pro obsluhu šablon 
*/
class Templater {
    var $vars;     
    public function __construct(){                        
        }
    function add($name='',$value=''){
        $this->vars[$name]=$value;                    
        }
    function fetch($path=''){                
        $path='engine/templates/'.$path;
        $handle=fopen($path,'r');
        if(!$handle){
            return false;
            }  
        $contents=fread($handle,filesize($path));            
        fclose($handle);
        $contents=trim($contents);
        $contents2=$this->eval_html($contents);
        unset($contents);
        if(isset($_GET['mhm-vars'])&&$_GET['mhm-vars']=='1'){        
            $path2=str_replace(array('/','.'),array('',''),$path);
            $rand=rand().'_'.$path2.'_tpl';
            $contents='<div class="kernel_templates"><a onclick="javascript:$(\'.'.$rand.'\').show();">'.$path.'</a><div style="display:none" class="'.$rand.'"><a onclick="javascript:$(\'.'.$rand.'\').hide();">CLOSE</a><br>';
            if(is_array($this->vars)){
                foreach ($this->vars as $var => $val){   
                    if(is_string($val)){                                               
                        if(strpos($val,'<div class="kernel_templates">')===false){}else{                                              
                            $contents.='<b>'.$var.'</b> is type SUB-TEMPLATE<br>';
                            continue; //nebudeme zobrazovat podsablony
                            }
                        }                 
                    $contents.='<b>'.$var.':</b> '.print_r($val,true).'<br>';
                    }
                }
            $contents.='</div></div>';
            $contents2=$contents.$contents2;
            }
        return $contents2;
        }
    function eval_html($string){
        ob_start();
        if(is_array($this->vars)){
            foreach ($this->vars as $var => $val){
                $$var = $val;
                }
            }        
        $er = error_reporting(E_ERROR | E_PARSE);  //tohle nechyta eval, set_error_handler('eval_error_callback');, parse chyby jsou na hlubsi urovni :(
        eval('?'.'>'.$string.'<'.'?');
        error_reporting($er);
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
        }
    }

?>