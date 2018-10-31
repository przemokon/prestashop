
 $(document).on('submit', '#unsubsribe-form', function(e){
    e.preventDefault();
    
   var dataForm = $(this).serialize();        

    $.ajax({
        type: 'GET',
        url: this_path + 'blocknewsletter-unsubscribe.php',
        data: dataForm,
        dataType:"json", 
        success: function(jsonData) {
    		$('.unsubscribe-content').html('<p class="lead text-center">' + jsonData.msg + '</p>');
        }
    });


});


$(document).ready(function() {

  $('.newsletter-form button').on('click', function() {
    // $('.newsletter-form button').removeClass('btn-gender');
    // $(this).addClass('btn-gender');
    var id_gender = parseInt($(this).attr("data-gender"));
    $('.id_gender').val(id_gender);
  });

    $('.newsletter-form form').on('submit', function(e) {
        e.preventDefault();
        var obj;
        var self = $(this);
   
        if(validate_isEmail(self.find('#newsletter-input').val()) && ($.cookie('newsletter_subscribed') == null))
        {
             $.ajax({
              type: 'GET',
              url: this_path + 'blocknewsletter-ajax.php',
              data: {
                        'email': $('#newsletter-input').val(), 
                        'action': 0, 
                        'submitNewsletter': true,
                        'postcode': $('.postcode').val(),
                        'id_gender': $('.id_gender').val()
                },
              dataType:"json", 
              success: function(jsonData) {
                if(jsonData.error) {

                    self.removeClass('has-success').addClass('has-error');
                    $('.newsletter-alert').text(jsonData.error);

                } else {

                    self.removeClass('has-error').addClass('has-success');
                    $('.newsletter-alert').text(jsonData.valid);
                }

              }
            });
        } else {
            self.removeClass('has-success').addClass('has-error');
        }

        if($.cookie('newsletter_subscribed') == true) {
            self.removeClass('has-success').addClass('has-error');
            $('.newsletter-alert').text(CookieError);     
        }


    });


});