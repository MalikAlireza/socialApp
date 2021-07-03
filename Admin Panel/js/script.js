function process(){
	processing = true;
	$('#p2').show();	
}
function endprocess(){
	processing = false;
	$('#p2').hide();
}
function is_processing(){
	return processing;

}

function tokenGenerate(){
	if ( is_processing() === true ) {
		return ;
	}
	process();
	$.post( base_path+'/ajax/generateToken.php', { token : jstoken }, function(result) {
   		if(result){
   		 	jstoken = result;
		}
	})
	.done(function() {
		})
	.fail(function(errors) {
		console.log(errors);
	});
	endprocess();
}
$(document).on('click', '.delete-btn', function(e){
	e.preventDefault();

	if ( is_processing() === true ) {
		return 'this';
	}
	
	var del = $(this);
	process();
	
	var data = {
		action : del.data('action'),
		id : del.data('id'),
		token: jstoken,
	}

	var axp = $.ajax({
		url: base_path+'ajax/delete.php', 
		data: data ,
		type: 'POST',
	});

	axp.done(function(html, textStatus, jqXHR){
		if ( html === true ) {
			$.toast({
			    text: "The "+data.action+" was successfuly deleted!",
			    afterHidden: function () {
			    	location.reload();
			    }
			});
		}else{

		}
	});

	axp.fail(function(data, textStatus, jqXHR){
		console.log(textStatus);
	});

	tokenGenerate();

	endprocess();
});

$(document).on('click', '.clear-btn', function(e){
	e.preventDefault();

	if ( is_processing() === true ) {
		return 'this';
	}
	
	var del = $(this);
	process();
	
	var data = {
		action : del.data('action'),
		id : del.data('id'),
		token: jstoken,
	}

	var axp = $.ajax({
		url: base_path+'ajax/clear.php', 
		data: data ,
		type: 'POST',
	});

	axp.done(function(html, textStatus, jqXHR){
		if ( html === true ) {
			$.toast({
			    text: "The "+data.action+" was successfuly cleared!",
			    afterHidden: function () {
			    	location.reload();
			    }
			});
		}else{

		}
	});

	axp.fail(function(data, textStatus, jqXHR){
		console.log(textStatus);
	});

	tokenGenerate();

	endprocess();
});


$(document).ready(function(){
	$('#p2').hide();
	$('#toast-closer').click(function(){
		$('#toast').hide();
	})
})