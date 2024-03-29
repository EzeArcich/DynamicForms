@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')

    <div class="row my-5" style="padding-top:50px!important;">
        <h2>Forms deleted</h2>
    </div>
    <div class="row>
        <div class="col col-12">
            <table class="table" id="forms_deleted_table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Fields quantity</th>
                        <th>Fields names</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

@stop

@section('css')

@stop

@section('js')
<script>
    $(document).ready(function () {
        
        function getTableDataDeletes() { 

            $('#forms_deleted_table').DataTable({
                ajax: 'getFormDeletes',
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'fields_count'},
                    {data: 'fields_names'},
                    {data: 'created_at_formatted'},
                    {data: 'updated_at_formatted'},
                    {
                    data: 'id',
                        render: function(data, type, full, meta) {

                            var  buttonsHtml = '<a class="btn btn-sm btn-warning mx-1" data-id="' + data + '" id="restore_button"><i class="fas fa-fw fa-folder-open"></i></a>';
                            

                            return buttonsHtml;
                    },
                    orderable: false,
                    searchable: false
                }
                ],
                responsive: true,
            });
            
        }


        getTableDataDeletes();


        function restoreDeletedForm(id) {
            $.ajax({
                type: "PUT",
                url: "/restoreFormDeletes/" + id,
                data: {id:id,
                    _token: '{{csrf_token()}}'},
                success: function (response) {
                    if(response.success == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        });
                        $('#forms_deleted_table').dataTable().fnDestroy();
                        getTableDataDeletes();
                    } else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                        });
                    }
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong with the request.',
                    });
                }
            });
        }


        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success mx-1",
                cancelButton: "btn btn-danger mx-1"
            },
            buttonsStyling: false
        });

        

        $(document).on('click', '#restore_button', function() {
            var id = $(this).data('id');

            swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You are about to restore this form.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    restoreDeletedForm(id);
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {

                }
            });           
        });     
    });
</script>

@stop
