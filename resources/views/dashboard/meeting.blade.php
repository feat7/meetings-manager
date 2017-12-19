@extends('layouts.app')

@section('content')


        <div class="section">
          <div class="container">
            <ul class="breadcrumb"><li><a href="/home">Home</a></li><li class="active">My meeting-contacts</li></ul>
            <div class="row">
              <div class="col-md-4">
                @include('dashboard.components.sidebar')
              </div>
              <div class="col-md-8">

                  <button id="send-sms" class="btn btn-info">Send Remainder (SMS)</button>




                <form id="multi-form" method="POST">
                {{csrf_field()}}
                  <div id="content"></div>


                  <button id="close-add-contact-modal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-contact-modal">Add New Contact</button>
                  <button id="delete-multi" class="btn btn-danger">Delete meeting-contacts</button>
                </form>

                <div id="notify"></div>
              </div>
            </div>
          </div>
        </div>



        <div class="modal fade" id="add-contact-modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add New Contacts</h4>
              </div>
              <div class="modal-body">
                <form id="contact-add-form" role="form" method="POST">
                {{csrf_field()}}
                  <div id="contact-add-auto"></div>
                </form>
              </div>
              <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal">Close</a>
                <a id="contact-add-btn" class="btn btn-primary">Submit</a>
              </div>
            </div>
          </div>
        </div>

        <script type="text/javascript">

        function reloadmeetingcontacts() {

          $('#content').html('');

          $.ajax({
            url: '/api/meetings/show/{{$meeting}}',
            type: 'POST',
            data: {
              '_token': '{{csrf_token()}}'
            },
            success: function(data) {
              $('#content').removeClass('loader');

              $('#content').append('');

              var table = $.map(data['meeting_contacts'], function(item) {
                return '<tr><td><input type="checkbox" name="contacts[]" value="'+item.contact.id+'"/></td><td>'+item.contact.name+'</td><td>'+item.contact.email+'</td><td>'+item.contact.mobile+'</td></tr>';                
              }).join('');

              // console.log(table);

              $('#content').append('<table class="table table-striped table-bordered"><thead><th></th><th>Name</th><th>Email</th><th>Number</th></thead><tbody>'+table+'</tbody></table>');

              $('#content').append('');
            },

            beforeSend: function() {
              $('#content').addClass('loader');
            }
          });

        } //reload meeting-contacts

          


          $(document).ready(function() {

            reloadmeetingcontacts();
  
              $('#delete-multi').click(function(e) {

                e.preventDefault();

                $.ajax({
                  url: '/api/meeting-contacts/{{$meeting}}/delete',
                  type: 'POST',
                  data: $('#multi-form').serialize(),
                  success: function (data) {
                    reloadmeetingcontacts();
                    
                    $('#notify').html('<div class="alert alert-danger">Selected meeting-contacts deleted successfully.</div>');
                  }
                });
              }); //delete-multi click


             var meetingData;

                // Load All Contacts Form

                $.ajax({
                  url: '/api/contacts/all',
                  type: 'POST',
                  data: {
                    '_token': '{{csrf_token()}}'
                  },
                  success: function(data) {

                        var table = $.map(data, function(item) {
                          return '<tr><td><input type="checkbox" name="contacts[]" value="'+item.id+'"/></td><td>'+item.name+'</td><td>'+item.email+'</td><td>'+item.mobile+'</td></tr>';                
                        }).join('');

                        meetingData = $.map(data, function(item) {
                          return item.mobile;
                        }).join(',');

                        // console.log(table);

                        $('#contact-add-auto').append('<table class="table table-striped table-bordered"><thead><th></th><th>Name</th><th>Email</th><th>Number</th></thead><tbody>'+table+'</tbody></table>');

                        $('#contact-add-auto').append('');
                            }
                }); //ajax ends




                //Add contacts to meeting
                $('#contact-add-btn').click(function(e) {

                      // console.log($('#contact-add-form').serialize());


                e.preventDefault();

                $.ajax({
                  url: '/api/meeting-contacts/{{$meeting}}/add',
                  type: 'POST',
                  data: $('#contact-add-form').serialize(),
                  success: function(data) {
                    if(data.success == 'true') {

                      console.log(data);


                      $('#close-add-contact-modal').trigger('click');


                    reloadmeetingcontacts();

                    $('#notify').html('<div class="alert alert-success">Selected contacts added successfully.</div>');

                    }

                    else {

                      $('#notify').html('<div class="alert alert-danger">Errors occured '+data.errors.name+' '+data.errors.description+' '+data.errors.meeting_time+'</div>');

                    }
                  }

                });

             }); //create meetings with contact


          $('#send-sms').click(function(e) {

            e.preventDefault();


            $.ajax({
                url: '/sms/send',
                type: 'POST',
                data: {
                  '_token': '{{csrf_token()}}',
                  'msg': 'Meeting Remainder. By {{Auth::user()->first_name}} {{Auth::user()->last_name}}. You have a meeting.',
                  'phone': meetingData,
                },
                success: function(data) {
                  $('#notify').html('<div class="alert alert-success">Meeting Notification sent to All contacts in this meeting.</div>');
                }
              });

          });
          




          }); //dockument.ready


          

         
        </script>
@endsection
