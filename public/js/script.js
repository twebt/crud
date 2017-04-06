$(function() {

  var crud = {

    'create': function() {

      $('.form-group').removeClass('has-error');
      $('.alert-danger').remove();

      // serialize data
      var formData = $('#form-add-user').serialize();

      $.ajax({
        type      : 'POST',
        url       : 'index.php?action=add',
        data      : formData,
        dataType  : 'json',
        beforeSend : function() {
          $('.loading').removeClass('hidden');
          console.log(formData);
        },
        success: function(data) {
          $('.loading').addClass('hidden');
          
          if (! data.success) {

            if (data.errors.username) {
              $('#form-add-user #username-group').addClass('has-error');
              $('#form-add-user #username-group').append('<div class="alert alert-danger fadeIn" role="alert">' + data.errors.username + '</div>');
            }

            if (data.errors.first_name) {
              $('#form-add-user #first-name-group').addClass('has-error');
              $('#form-add-user #first-name-group').append('<div class="alert alert-danger fadeIn" role="alert">' + data.errors.first_name + '</div>');
            }

            if (data.errors.last_name) {
              $('#form-add-user #last-name-group').addClass('has-error');
              $('#form-add-user #last-name-group').append('<div class="alert alert-danger fadeIn" role="alert">' + data.errors.last_name + '</div>');
            }

            if (data.errors.status) {
              $('#form-add-user #status-group').addClass('has-error');
              $('#form-add-user #status-group').append('<div class="alert alert-danger fadeIn" role="alert">' + data.errors.status + '</div>');
            }

            if (data.errors.user_exist) {
              $('#form-add-user #username-group').addClass('has-error');
              $('#form-add-user #username-group').append('<div class="alert alert-danger fadeIn" role="alert">' + data.errors.user_exist + '</div>');
            }
          }
          else {
            $('#form-add-user .alert-success').text(data.message).fadeIn();

            $('.modal-header .close').click(function() {
              $('.loading').removeClass('hidden');
              location.reload(true);
            });
          }
        },

        error: function(xhr, resp, text, data) {
          console.log(data);
        }

      });
    },

    'edit': function(data) {

      $('.form-group').removeClass('has-error');
      $('.alert-danger').remove();

     /* var formData = {
        'username'        : $('#form-edit-user #username').val(),
        'first_name'      : $('#form-edit-user #first_name').val(),
        'last_name'       : $('#form-edit-user #last_name').val(),
        'status'          : $('#form-edit-user #status').val(),
        'edit_user_id'    : $('#form-edit-user #edit_user_id').val()
      }*/
     

      $.ajax({
        type      : 'POST',
        url       : 'index.php?action=edit',
        data      :  data,
        dataType  : 'json',
        beforeSend: function(data) {
          $('.loading').removeClass('hidden');
        },
        success: function(data) {

          console.log(data);

          $('.loading').addClass('hidden');
          
          if (! data.success) {

            if (data.errors.first_name) {
              $('.form-edit-user .first-name-group').addClass('has-error');
              $('.form-edit-user .first-name-group').append('<div class="alert alert-danger fadeIn" role="alert">' + data.errors.first_name + '</div>');
            }

            if (data.errors.last_name) {
              $('.form-edit-user .last-name-group').addClass('has-error');
              $('.form-edit-user .last-name-group').append('<div class="alert alert-danger fadeIn" role="alert">' + data.errors.last_name + '</div>');
            }

            if (data.errors.status) {
              $('.form-edit-user .status-group').addClass('has-error');
              $('.form-edit-user .status-group').append('<div class="alert alert-danger fadeIn" role="alert">' + data.errors.status + '</div>');
            }

            if (data.errors.user_exist) {
              $('.form-edit-user .username-group').addClass('has-error');
              $('.form-edit-user .username-group').append('<div class="alert alert-danger fadeIn" role="alert">' + data.errors.user_exist + '</div>');
            }
          }
          else {
            $('.form-edit-user .alert-success').text(data.message).fadeIn();

            $('.modal-header .close').click(function() {
              $('.loading').removeClass('hidden');
              location.reload(true);
            });
          }
        },
        error   : function(xhr, resp, text, data) {
          console.log(data);
        }
      });

    },

    'delete': function(id) {
      $.ajax({
        type      : 'GET',
        url       : 'index.php?action=delete',
        data      :  'delete_id=' + id,
        dataType  : 'json',
        beforeSend : function() {
          $('.loading').removeClass('hidden');
        },
        success: function(data) {

          if (! data.success) {

            if (data.errors.userId) {
              $('#messageModal .modal-title-errors').text(data.errors_title);
              $('#messageModal #errors').text(data.errors.userId);
              $('#messageModal').modal('show');
            }

          } 
          else {
            $('#messageModal .modal-title-success').text(data.success_title);
            $('#messageModal #success').text(data.message);
            $('#messageModal').modal('show');

            $('.modal-header .close').click(function() {
              location.reload(true);
            });
          }
        },
        error: function(xhr, resp, text, data) {
          alert(text);
        }
      });
    }
  }

  // create new user
  $('#form-add-user').on('submit', function (e) {
    e.preventDefault();
    crud.create(); 
  });

  // edit user
  $('.form-edit-user').each(function() {
    $(this).on('submit', function(e) {
      e.preventDefault();
      var data = $(this).serialize();
      crud.edit(data);
    });
  });

  // delete user
  $('.delete-user').click(function(e) {

    e.preventDefault();

    if (confirm("Are you sure to delele this?")) {
      var id = $(this).find('.d-user-id').val(); // get user_id
      crud.delete(id); // and run delete operation
    } else {
      return false;
    }

  });

});
