<?php
class magickFunctions {
	
	function createThumbUniversal($path, $file, $newwidth, $newheight,$type) {
		
				
		$resource = NewMagickWand ();
		
		if (MagickReadImage ( $resource, $path.$file )) {
			
			$filedata = pathinfo ( $file );
			$newfile = ($type == 'thumb')?$filedata['dirname'].'/' . $filedata ['filename'] . '.thumb.' . $filedata ['extension']:$filedata['dirname'].'/' . $filedata ['filename'] . '.workfile.' . $filedata ['extension'];
			
			$resource = MagickTransformImage ( $resource, '0x0', $newwidth . 'x' . $newheight );
			
			if (MagickWriteImage ( $resource, $path . $newfile )) {
				
				return $newfile;
			}
			else 
			{
				mail('sonny@feburman.co.uk','magickFunctions - write - fail',"path: $path file:$newfile");
			}
			
			
			
		}
		else 
		{
			mail('sonny@feburman.co.uk','magickFunctions - fail',"path: $path file:$file");
		}
	
	}
	
	
	
function createThumbUniversal2($path, $file, $newwidth, $newheight,$type) {
		
				
		$resource = NewMagickWand ();
		
		if (MagickReadImage ( $resource, $path.$file )) {
			
			$filedata = pathinfo ( $file );
			$newfile = ($type == 'thmb')?$filedata['dirname'].'/' . $filedata ['filename'] . '_thmb.' . $filedata ['extension']:$filedata['dirname'].'/' . $filedata ['filename'] . '.workfile.' . $filedata ['extension'];
			
			$resource = MagickTransformImage ( $resource, '0x0', $newwidth . 'x' . $newheight );
			
			if (MagickWriteImage ( $resource, $path . $newfile )) {
				
				return $newfile;
			}
			else 
			{
				mail('sonny@feburman.co.uk','magickFunctions - write - fail',"path: $path file:$newfile");
			}
			
			
			
		}
		else 
		{
			mail('sonny@feburman.co.uk','magickFunctions - fail',"path: $path file:$file");
		}
	
	}
	

}

?>