<?php

class myTemplateClass
{
	var $htmlPath;
	var $htmlFile;
	var $htmlPathFileMerge;
	
function mergeFilePath()

{
	$this->htmlPathFileMerge =$this->htmlPath.$this->htmlFile;
	return $this->htmlPathFileMerge;
}

function generateHtml($tags)
{
	
	if (is_array($tags)) 
	{
	}else 'Tags should be in array!';
	

	$page=file_get_contents($this->htmlFile);
	
	if ($page) 
	{
	foreach ($tags as $key=>$value)	
	{
		
		$page=str_replace('{'.$key.'}',$value,$page);
		
	}
	
	}else echo 'unable to return file contents';
	return $page;
}




}
?>