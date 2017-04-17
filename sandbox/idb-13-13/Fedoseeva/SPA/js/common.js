$(function() {

	var countMess = 0;

	$('.form__button').click(function(event) {
		var userName = $('#user').val();
		if(userName != ''){
			$.ajax({
              type: "POST",
              url: "../php/userName.php",
              data: {userName:userName},

              success: function(data)
              {
                  $('.conteiner').empty();
					        $('.conteiner').append(data);
					        load_messes();
					        $('.type__button').click(function(event) {
									 var mess = $('.type__input').val();
							       $.ajax({
							                type: "POST",
							                url: "../php/addMessage.php",
							                data: {mess:mess},

							                success: function(data)
							                {
							                    load_messes();
							                    $(".type__input").val('');
							                }
							        });
									});
              }
      });

		}
	});
	
  $(".type__button").click(function(event) {
		 var mess = $('.type__input').val();

       $.ajax({
                type: "POST",
                url: "../php/addMessage.php",
                data: {mess:mess},

                success: function(data)
                {
                    load_messes();
                    $(".type__input").val('');
                }
        });
		});
       
    function load_messes()
    {
        $.ajax({
                type: "POST",
                url:  "../php/loadMessages.php",
                data: "req=ok",
                success: function(data)
                {
                    $(".chat__view").empty();
                    $(".chat__view").append(data);
                    $(".chat__view").scrollTop(90000);
                }
        });
    }

    load_messes();
	setInterval(load_messes,3000)

});
