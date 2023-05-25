<?php
Class MHMthumb{
  public function thumb($fromPath='',$toPath='',$width,$height,$transparentBackground=true,$backgroundColorR=0,$backgroundColorG=0,$backgroundColorB=0,$compresion=2){
    $is=@getimagesize($fromPath);
		$is_width=$is[0];
		$is_height=$is[1];  
		$is_type=$is[2];
		unset($is);
		if($is_type==IMAGETYPE_JPEG){
      $src=imagecreatefromjpeg($fromPath);
    }elseif($is_type==IMAGETYPE_PNG){
      $src=imagecreatefrompng($fromPath);
    }else{
      return false;
    }
    if(!$src){return false;}
		$thumb=imagecreatetruecolor($width,$height);		
		if($transparentBackground==true){		  
		  imagealphablending($thumb,false);
      $transparency=imagecolorallocatealpha($thumb,0,0,0,127);
      imagefill($thumb,0,0,$transparency);
      imagesavealpha($thumb,true);
    }else{
      $colored=imagecolorallocate($thumb,$backgroundColorR,$backgroundColorG,$backgroundColorB);    
      imagefill($thumb,0,0,$colored);      
    }
		$sizes=$this->calculateImageSize($is_width,$is_height,$width,$height);
    imagecopyresampled($thumb,$src,$sizes->fromX,$sizes->fromY,0,0,$sizes->toX,$sizes->toY,$is_width,$is_height);    
    return imagepng($thumb,$toPath,$compresion);     
    }
  private function calculateImageSize($lastW,$lastH,$newW,$newH){
    $wh=new stdClass();        
    if($lastW>$newW||$lastH>$newH){
      $scaleW=$newW/$lastW;
  		$scaleH=$newH/$lastH;
  		$wh->scale=$scaleW<=$scaleH?$scaleW:$scaleH;  						
  		if($scale>1){$scale=1;}
  		$wh->toX=floor($wh->scale*$lastW);
  		$wh->toY=floor($wh->scale*$lastH);  		 
    }else{
      $wh->toX=floor($lastW);
  		$wh->toY=floor($lastH);
    }
    $wh->fromX=floor(($newW-$wh->toX)/2);
  	$wh->fromY=floor(($newH-$wh->toY)/2);       
    return $wh; 
    }
  }