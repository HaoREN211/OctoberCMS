$(window).load(function(){
function preSubmit() {
	if($('#ck-input').val()){
	 for(var instanceName in CKEDITOR.instances)
    	CKEDITOR.instances[instanceName].updateElement();
	}
	return true;
}


function disableHide(sclass) {
	$(sclass).addClass('hidden');
	$(sclass).attr('disabled', true);
	$(sclass).attr('disabled', true);
}

function enableshow(sclass) {
	$(sclass).removeClass('hidden');
	$(sclass).attr('disabled', false);
	$(sclass).attr('disabled', false);
}

function endsChange(hide) {
	disableHide('.e_all');
    if (hide == true){
    	$('#Ends').val('NEVER').change();
    }

	var end_at = $('#Ends').val().toLowerCase();
	enableshow('.e_'+end_at);

}

function yearOnChange() {
	disableHide('.yr_all');    
	var on = $('#year_on').val().toLowerCase();
	enableshow('.yr_'+on);
}

$( "#FREQ" ).on( "change", function() {
    disableHide('.r_all');
	var repeat = $('#FREQ').val().toLowerCase();
	enableshow('.r_'+repeat);
	endsChange(true);
	yearOnChange();
});

$( "#month_on" ).on( "change", function() {
	disableHide('.mo_all');
    
	var on = $('#month_on').val().toLowerCase();
	enableshow('.mo_'+on);
});

$( "#year_on" ).on( "change", function() {	
	yearOnChange();
});


$( "#Ends" ).on( "change", function() {
	endsChange(false);
});

if($('#ck-input').val()) CKEDITOR.replace( 'text' );


$('#EventForm').submit(function()
{
    preSubmit();
	return true;
});
});