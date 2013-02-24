$(document).ready(function(){
    $('table tr:odd').each(function(){
        $(this).addClass('odd');
    });
    $('table tr:even').each(function(){
        $(this).addClass('even');
    });
    
	$(".TabelaLista tbody tr:odd").addClass("odd");
	$(".TabelaLista tbody tr:even").addClass("even");
	$(".TabelaLista tbody tr").hover(function() {
		$(this).css({'background' : '#F3F3F3'}); 
		} , function() {
		$(this).css({'background' : ''}); 
	});
});

