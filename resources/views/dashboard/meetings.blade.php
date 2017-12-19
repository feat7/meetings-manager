@extends('layouts.app')

@section('content')


        <div class="section">
          <div class="container">
            <ul class="breadcrumb"><li><a href="/home">Home</a></li><li class="active">My meetings</li></ul>
            <div class="row">
              <div class="col-md-4">
                @include('dashboard.components.sidebar')
              </div>
              <div class="col-md-8">



                <form id="multi-form" method="POST">
                {{csrf_field()}}
                  <div id="content"></div>


                  <button id="close-add-meeting-modal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-meeting-modal">Add New meeting</button>
                  <button id="delete-multi" class="btn btn-danger">Delete meetings</button>
                </form>

                <div id="notify"></div>
              </div>
            </div>
          </div>
        </div>


        <div class="modal fade" id="add-meeting-modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add New meeting</h4>
              </div>
              <div class="modal-body">
                <form id="meeting-add-form" role="form" method="POST">
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
                  <button id="meeting-add-btn" type="submit" class="btn btn-default">Submit</button>
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

        function reloadmeetings() {

          $('#content').html('');

          $.ajax({
            url: '/api/meetings/all',
            type: 'POST',
            data: {
              '_token': '{{csrf_token()}}'
            },
            success: function(data) {
              $('#content').removeClass('loader');

              $('#content').append('');

              var table = $.map(data, function(item) {
                return '<tr><td><input type="checkbox" name="meetings[]" value="'+item.id+'"/></td><td><a href="/meetings/'+item.id+'">'+item.name+'</a></td><td>'+item.description+'</td><td>'+item.meeting_time+'</td></tr>';                
              }).join('');

              // console.log(table);

              $('#content').append('<table class="table table-striped table-bordered"><thead><th></th><th>Name</th><th>Description</th><th>Time</th></thead><tbody>'+table+'</tbody></table>');

              $('#content').append('');
            },

            beforeSend: function() {
              $('#content').addClass('loader');
            }
          });

        } //reload meetings

          


          $(document).ready(function() {

            reloadmeetings();
  
              $('#delete-multi').click(function(e) {

                e.preventDefault();

                $.ajax({
                  url: '/api/meetings/delete',
                  type: 'POST',
                  data: $('#multi-form').serialize(),
                  success: function () {
                    reloadmeetings();
                    $('#notify').html('<div class="alert alert-danger">Selected meetings deleted successfully.</div>');
                  }
                });
              }); //delete-multi click


             $('#meeting-add-btn').click(function(e) {

                e.preventDefault();

                $.ajax({
                  url: '/api/meetings/add',
                  type: 'POST',
                  data: $('#meeting-add-form').serialize(),
                  success: function(data) {
                    if(data.success == 'true') {

                      $('#close-add-meeting-modal').trigger('click');

                    reloadmeetings();

                    $('#notify').html('<div class="alert alert-success">meeting added successfully.</div>');

                    }

                    else {

                      $('#notify').html('<div class="alert alert-danger">Errors occured '+data.errors.name+' '+data.errors.description+' '+data.errors.meeting_time+'</div>');

                    }
                  }

                });

             });






          }); //dockument.ready


          

         
        </script>
@endsection
