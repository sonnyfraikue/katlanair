$(window).load(function(){

$('.pinfo, .pinfot').focus(function(e) {
	 $('.hlp').fadeTo("slow", 0.3);
	 $('#hlp_'+e.target.id).fadeTo("slow", 1);
	 
	 $(this).live('focus blur keyup', function(e) {
		 var elementId	=	e.target.id;
		 var elementName	=	e.target.name;
		 
		 if(elementName != 'web')
		 {
			
		 
			// alert(elementName)
		 var loadholder	=	document.getElementById(elementId+'_pr');
		 	// if(loadholder.innerHTML == false)
		 if($(loadholder).html() == '')
		 	 {
		 var loadingImg	=	document.createElement('img');
		 	 loadingImg.src	=	'../images/greenloader.gif';
		 	 loadholder.appendChild(loadingImg);
		 	 }
		 setTimeout(function(e){
			// alert($('#'+elementId).val());
			 var elementValue	=	$('#'+elementId).val();
			 
			 //here i inted to carry out my save and replace the spinning wheel with a tick
			 myusefulInstance.savefield('users',elementName,elementValue,'user_id',userid, {
					"onFinish": function(response){  
					 if(response) 
					 {
						 document.getElementById(elementId+'_pr').innerHTML	=	response;
					 }
					}  
					});
			 
			 
		 },2000)
		 
		 } 
		 
		 
	 })
	 
	
	 
	 
	});
});