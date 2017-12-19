@extends('layouts.app')

@section('content')


        <div class="section">
          <div class="container">
            <ul class="breadcrumb"><li><a href="/home">Home</a></li><li class="active">My Contacts</li></ul>
            <div class="row">
              <div class="col-md-4">
                @include('dashboard.components.sidebar')
              </div>
              <div class="col-md-8">
                  <button id="close-add-contact-modal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-contact-modal">Add New Contact</button>



                <form id="multi-form" method="POST">
                {{csrf_field()}}
                  <div id="content"></div>

                  <button id="meeting-multi" type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-meeting-modal">Create New Meeting</button>
                  <button id="delete-multi" class="btn btn-danger">Delete Contacts</button>
                </form>

                <div id="notify"></div>
              </div>
            </div>
          </div>
        </div>


<!-- Add New Contact Modal -->
        <div class="modal fade" id="add-contact-modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add New Contact</h4>
              </div>
              <div class="modal-body">
                <form id="contact-add-form" role="form" method="POST">
                {{csrf_field()}}
                  <div class="form-group">
                    <label class="control-label" for="contact-name">Name</label>
                    <input class="form-control" name="name" id="contact-name" placeholder="Contact Name" type="text">
                  </div>
                  <div class="form-group">
                    <label class="control-label" for="contact-email">Email</label>
                    <input class="form-control" name="email" id="contact-email" placeholder="Contact Email" type="text">
                  </div>
                  <div class="form-group">
                    <label class="control-label" for="contact-mobile">Mobile</label>
                    <input class="form-control" name="mobile" id="contact-mobile" placeholder="Contact Mobile" type="text">
                  </div>
                  <button id="contact-add-btn" type="submit" class="btn btn-default">Submit</button>
                </form>
              </div>
              <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal">Close</a>
                <a class="btn btn-primary">Save changes</a>
              </div>
            </div>
          </div>
        </div>


<!-- Create New Meeting Modal -->

        <div class="modal fade" id="add-meeting-modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add New meeting</h4>
              </div>
              <div class="modal-body">
                <form id="add-meeting-form" role="form" method="POST">
                {{csrf_field()}}
                  <div class="form-group">
                    <label class="control-label" for="meeting-name">Name</label>
                    <input class="form-control" name="name" id="meeting-name" placeholder="meeting Name" type="text">
                  </div>
                  <div class="form-group">
                    <label class="control-label" for="meeting-description">Description</label>
                    <input class="form-control" name="description" id="meeting-description" placeholder="Description" type="text">
                  </div>
                  <div class="form-group">
                    <label class="control-label" for="datepicker">Meeting Time</label>
                    <input class="form-control" name="meeting_time" id="datepicker" type="text">
                    <script type="text/javascript">
                      $('#datepicker').datetimepicker({
                        format: 'Y-MM-DD HH:mm:ss'
                      });
                    </script>
                  </div>
                  <button id="add-meeting-btn" type="submit" class="btn btn-default">Submit</button>
                </form>
              </div>
              <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal">Close</a>
                <a class="btn btn-primary">Save changes</a>
              </div>
            </div>
          </div>
        </div>

        <script type="text/javascript">

        function reloadContacts() {

          $('#content').html('');

          $.ajax({
            url: '/api/contacts/all',
            type: 'POST',
            data: {
              '_token': '{{csrf_token()}}'
            },
            success: function(data) {
              $('#content').removeClass('loader');

              $('#content').append('');

              var table = $.map(data, function(item) {
                return '<tr><td><input type="checkbox" name="contacts[]" value="'+item.id+'"/></td><td>'+item.name+'</td><td>'+item.email+'</td><td>'+item.mobile+'</td></tr>';                
              }).join('');

              // console.log(table);

              $('#content').append('<table class="table table-striped table-bordered"><thead><th></th><th>Name</th><th>Email</th><th>Number</th></thead><tbody>'+table+'</tbody></table>');

              $('#content').append('');
            },

            beforeSend: function() {
              $('#content').addClass('loader');
            }
          });

        } //reload contacts

          


          $(document).ready(function() {

            reloadContacts();
  
              $('#delete-multi').click(function(e) {

                e.preventDefault();

                $.ajax({
                  url: '/api/contacts/delete',
                  type: 'POST',
                  data: $('#multi-form').serialize(),
                  success: function () {
                    reloadContacts();
                    $('#notify').html('<div class="alert alert-danger">Selected contacts deleted successfully.</div>');
                  }
                });
              }); //delete-multi click

              $('#add-meeting-btn').click(function(e) {

                e.preventDefault();

                $.ajax({
                  url: '/api/meetings/add',
                  type: 'POST',
                  data: $('#add-meeting-form').serialize()+'&'+$('#multi-form').serialize(),
                  success: function(data) {
                    if(data.success == 'true') {

                      $('#close-add-meeting-modal').trigger('click');

                    

                    $('#notify').html('<div class="alert alert-success">meeting added successfully.</div>');

                    }

                    else {

                      $('#notify').html('<div class="alert alert-danger">Errors occured '+data.errors.name+' '+data.errors.description+' '+data.errors.meeting_time+'</div>');

                    }
                  }

                });

             }); //create meetings with contact

             $('#contact-add-btn').click(function(e) {

                e.preventDefault();

                $.ajax({
                  url: '/api/contacts/add',
                  type: 'POST',
                  data: $('#contact-add-form').serialize(),
                  success: function(data) {
                    if(data.success == 'true') {

                      $('#close-add-contact-modal').trigger('click');

                    reloadContacts();

                    $('#notify').html('<div class="alert alert-success">Contact added successfully.</div>');

                    }

                    else {

                    }
                  }

                });

             });






          }); //dockument.ready


          

         
        </script>
@endsection
