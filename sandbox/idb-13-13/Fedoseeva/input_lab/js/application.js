$(document).ready(function(){
	var max_digit_length = 16
	$("[name='for_card']").on('keydown',function(){
		if(event.code.match(/\d/g) != null){
			if ($(this).val().match(/\d/g).length < max_digit_length){
				if($(this).val().match(/\d/g).length%4 == 0 && $(this).val().match(/\d/g).length > 0){
					var temp = $(this).val()
					$(this).val(temp + " ")
				}
			}
		}
		
	})

	$("[name='card_date']").on('keydown',function(){
		if(event.code.match(/\d/g) != null){
			if ($(this).val().match(/\d/g).length < 4){
				if($(this).val().match(/\d/g).length%2 == 0 && $(this).val().match(/\d/g).length > 0){
					var temp = $(this).val()
					$(this).val(temp + "/")
				}
			}
		}
		
	})
})