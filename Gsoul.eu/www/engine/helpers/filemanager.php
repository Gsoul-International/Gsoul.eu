<?php
Class fileManager{
    function UploadFile($input_name='', $output_name='', $path='userfiles/',$check='all',$checkValues=array()){
        if($input_name==''||$path==''){
            return false;
            }
        if ($_FILES[$input_name]["error"]>0){
            return false;
            }
        $original_name=explode('.',$_FILES[$input_name]["name"]);
        if($check=='by_array'){
            if($this->CheckAllowedFileByArray(end($original_name),$checkValues)==false){
                return false;
                }
            }
        if($check=='all'){
            if($this->CheckAllowedFile(end($original_name))==false){
                return false;
                }
            }
        if($check=='word'){
            if($this->CheckAllowedFileWord(end($original_name))==false){
                return false;
                }
            }
        if($check=='image'){
            if($this->CheckAllowedFilePicture(end($original_name))==false){
                return false;
                }
            }
        if($check=='imageconfig'){
            if($this->CheckAllowedFilePictureConfig(end($original_name))==false){
                return false;
                }
            }
        if($check=='pdf'){
            if($this->CheckAllowedFilePDF(end($original_name))==false){
                return false;
                }
            }
        if($output_name==''){
            $output_name=time();
            }
        $newname=$output_name.'.'.strtolower(end($original_name));      
        if(filesize($_FILES[$input_name]["tmp_name"])>(str_replace('B','',ini_get('upload_max_filesize'))*1048576)){                         
            return false;
            }
          if(move_uploaded_file($_FILES[$input_name]["tmp_name"],$path.$newname)==true){            
              return $path.$newname;
          }else{
              return false;
          }                
        }
    function DeleteFile($path_name){
        unlink($path_name);
        return true;
        }
    function CheckAllowedFile($suffix){
        $allowed=array('jpg','jpeg','png','gif','bmp','tif','rar','zip','pdf','txt','csv','doc','docx','xls','xlsx','ppt','pptx','rtf','xml','xsd','psd','odt','ico','htm','html','css','mp3','avi','mp4','flv','7zip','7z','wmv','wma','tar','tgz','swf');        
        if(!in_array($suffix,$allowed)){
            return false;
            }
        return true;
        }
    function CheckAllowedFilePicture($suffix){
        $allowed=array('jpg','jpeg','png');        
        if(!in_array($suffix,$allowed)){
            return false;
            }
        return true;
        }
    function CheckAllowedFilePictureConfig($suffix){
        $allowed=array('jpg','jpeg','png','conf','cfg');        
        if(!in_array($suffix,$allowed)){
            return false;
            }
        return true;
        }
    function CheckAllowedFileWord($suffix){
        $allowed=array('doc','docx','rtf','odt','ods');        
        if(!in_array($suffix,$allowed)){
            return false;
            }
        return true;
        }
    function CheckAllowedFilePDF($suffix){
        $allowed=array('pdf');        
        if(!in_array($suffix,$allowed)){
            return false;
            }
        return true;
        }  
    function CheckAllowedFileByArray($suffix,$allowed=array()){          
        if(!in_array(strtolower($suffix),$allowed)){            
            return false;
            }
        return true;
        }              
    }
?>