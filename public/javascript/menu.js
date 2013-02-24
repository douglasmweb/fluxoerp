$(document).ready(function(){
	try {
        var m_itens = $('.menu .navigation > li > a').size();
		var rainbow = new Rainbow(); // by default, range is 0 to 100
		rainbow.setNumberRange(1, m_itens);
		rainbow.setSpectrum('aa0000', 'aaaa00', '00aa00', '00aaaa', '0000aa', 'aa00aa');
        var rainbowhover = new Rainbow();
        rainbowhover.setNumberRange(1, m_itens);
		rainbowhover.setSpectrum('660000', '666600', '006600', '006666', '000066', '660066');

        var i = 1;
        $('.menu .navigation > li > a').each(function(){
			var hex = '#' + rainbow.colourAt(i);
            var nexthex = '#' + rainbow.colourAt(i+1);
            $(this).css('background-color',hex);
            
            var hexhover = escurecerClarear($(this),85);

            $(this).hover(function(){
                $(this).css('background',hexhover);
            },function(){
                $(this).css('background-color',hex);
            });
            i = i+1;
            
            var m_subitems = $(this).parents('li').find('ul li a').size();
            
            if(m_subitems>0){
                var rainbow2 = new Rainbow(); // by default, range is 0 to 100
                    rainbow2.setNumberRange(1, m_subitems+1);
                    rainbow2.setSpectrum(hex,nexthex);
                
                var y = 1;
                $(this).parents('li').find('ul li a').each(function(){
        			var hex1 = '#' + rainbow2.colourAt(y);

                    $(this).css('background-color',hex1);

                    var hex1hover = escurecerClarear($(this),85);
                    
                    $(this).hover(function(){
                        $(this).css('background',hex1hover);
                    },function(){
                        $(this).css('background-color',hex1);
                    });
                    
                    y = y+1;
                });
            }
        });
        

        
	} catch (err) {
		alert(err);
	}
});    