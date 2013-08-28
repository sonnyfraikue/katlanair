<?php

class myEmailMappingClass
{
	var $emailPage;
	


function generateEmailBody($tags,$page)
{
	
	if (is_array($tags)) 
	{
	}else 'Tags should be in array!';
	
	
	
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