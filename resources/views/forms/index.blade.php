@extends('adminlte::page')

@section('title', 'Dashboard')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
<style>
    .tagify.form-control {
        display: inline-block !important;
        height: auto !important;
    }

</style>

@section('content_header')

@stop

@section('content')

    
    <div class="row my-5" style="padding-top:50px!important;">
    @role('admin')
        <div class="col-12">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_form_modal">Add Modal</button>
        </div>
        @endrole
    </div>
    <div class="row">
        <div class="col col-12">
            <table class="table" id="forms_table">
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

    <div class="modal fade" id="add_form_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <h5 class="card-header">Form</h5>
                            <div class="card-body">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Form Name</span>
                                            </div>
                                            <input type="text" id="formName" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <h5 class="card-header">Field</h5>
                            <div class="card-body">
                                <div class="row mb-4" id="dynamics_fields">


                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-success add_row" data-action="store">Add Field</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save_form" data-button-form="save">Save Form</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="optionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Options</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="options">Insert field options</label> - Type and press Enter
                        <input id="options" name="options" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveOptions">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editFormModal" tabindex="-1" role="dialog" aria-labelledby="editFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <h5 class="card-header">Form</h5>
                            <div class="card-body">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Form Name</span>
                                            </div>
                                            <input type="text" id="formName_edit" class="form-control">
                                            <input type="hidden" id="formId">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <h5 class="card-header">Field</h5>
                            <div class="card-body">
                                <div class="row mb-4" id="dynamics_fields_edit">


                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-success add_row" data-action="edit">Add Field</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save_form" data-button-form="edit">Save Form</button>
            </div>
            </div>
        </div>
    </div>




    
@stop

@section('css')
    
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify@4.3.0/dist/jQuery.tagify.min.js"></script>
<script>
    var fieldOptions = [];
    var userRoles = @json($userRoles);

    $(document).ready(function () {

        $('#options').tagify();

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success mx-1",
                cancelButton: "btn btn-danger mx-1"
            },
            buttonsStyling: false
        });
        
        function getTableData() {
            $('#forms_table').DataTable({
                ajax: 'getFormsData',
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
                            var buttonsHtml = '<a class="btn btn-sm btn-success mx-1" href="/form/'+ data +'"><i class="fas fa-fw fa-eye"></i></a>';
                            
                            if (userRoles.includes('admin')) {
                                buttonsHtml += '<a class="btn btn-sm btn-info btn-edit  mx-1" data-id="' + data + '"><i class="fas fa-fw fa-edit"></i></a>' +
                                    '<a class="btn btn-sm btn-danger btn-delete mx-1" data-id="' + data + '"><i class="fas fa-fw fa-trash"></i></a>';
                            }

                            return buttonsHtml;
                    },
                    orderable: false,
                    searchable: false
                }
                ],
                responsive: true,
            });
        }

        getTableData();

        function deleteForm(id) {
            $.ajax({
                type: "DELETE",
                url: "/form/" + id,
                data: {id:id,
                    _token: '{{csrf_token()}}'},
                success: function (response) {
                    if(response.success == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        });
                    } else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                        });
                    }
                    setTimeout(function() {
                        location.reload();
                    }, 3000)
                }
            });
        }

        $(document).on('click', '.btn-delete', function() {
            var id = $(this).data('id');

            swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You are about to delete this form.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    deleteForm(id);
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {

                }
            });
        });

        $(document).on('click', '.btn-edit', function () {
            var formId = $(this).data('id');

            $.ajax({
                type: "GET",
                url: "{{url('form')}}/" + formId + "/edit",
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        populateEditModal(data.formData, formId);

                        $('#editFormModal').modal('show');
                    } else {
                        alert("Error al obtener los datos del formulario.");
                    }
                },
                error: function () {
                    alert("Error al obtener los datos del formulario.");
                }
            });
        });

        function deleteField(id) {
            $.ajax({
                type: "DELETE",
                url: "/field/" + id,
                data: {id:id,
                    _token: '{{csrf_token()}}'},
                success: function (response) {
                    if(response.success == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        });
                    } else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                        });
                    }
                }
            });
        }

        $(document).on('click', '.btn-remove-row', function () { 
            $(this).closest('.row').remove();
            var fieldId = $(this).data('btn-id');

            swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You are about to delete this field.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    deleteField(fieldId);
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {

                }
            });
        
        });


        function populateEditModal(formData, formId) {
            $('#formName_edit').val(formData.form.name);
            $('#formId').val(formId);

            var fields = formData.fields;
            var dynamicFieldsContainerEdit = $('#dynamics_fields_edit');
            
            dynamicFieldsContainerEdit.empty();

            $.each(fields, function (index, field) {
                var fieldIdEdit = field.id;
                var fieldNameEdit = field.name;
                var fieldTypeEdit = field.type;
                var fieldOptionsEdit = field.options ? JSON.parse(field.options) : [];

                var newRowEditInput = `
                    <div class="col-md-5 col-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Field Name</span>
                                </div>
                                <input type="text" class="form-control" id="field_name_edit_${fieldIdEdit}" value="${fieldNameEdit}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-12">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" for="selectType${fieldIdEdit}">Field Type/Data</span>
                                </div>
                                <select class="custom-select dynamic-select" data-field-id="${fieldIdEdit}" id="select_type_edit_${fieldIdEdit}">
                                    <option value="number" ${fieldTypeEdit === 'number' ? 'selected' : ''}>Number</option>
                                    <option value="date" ${fieldTypeEdit === 'date' ? 'selected' : ''}>Date</option>
                                    <option value="text" ${fieldTypeEdit === 'text' ? 'selected' : ''}>Text</option>
                                    <option value="option" ${fieldTypeEdit === 'option' ? 'selected' : ''}>Options</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-danger btn-sm btn-remove-row" data-btn-id="${fieldIdEdit}">Remove</button>
                        </div>
                    </div>`;

                if (fieldTypeEdit === 'option') {
                    newRowEditInput += `
                    <div class="col-12">
                        <div class="form-group">
                            <label for="optionsEdit">Insert field options</label> - Type and press Enter
                            <input id="optionsEdit_${fieldIdEdit}" name="optionsEdit" class="form-control"
                            style="display: inline-block !important; height: auto !important;" value="${fieldOptionsEdit.join(',')}">
                        </div>
                    </div>`;
                }

                var newRow = $('<div class="row">' + newRowEditInput + '</div>');
                dynamicFieldsContainerEdit.append(newRow);

                if (fieldTypeEdit === 'option') {
                    var optionsEditInput = newRow.find(`#optionsEdit_${fieldIdEdit}`);
                    optionsEditInput.tagify();
                }
            });
        }


        $('#optionsModal').on('shown.bs.modal', function () {
            $(this).css('z-index', 1050);
        });


        // Lógica para agregar rows dinámicas

        var dynamicFieldsContainer = $('#dynamics_fields');

        $('.add_row').click(function (e) { 
            var nameIdField = '';
            var nameidSelect = '';
            var actionValue = $(this).data('action');
            if(actionValue == 'edit') {
                dynamicFieldsContainer =  $('#dynamics_fields_edit');
                nameIdField = 'field_name_edit_';
                nameidSelect = 'select_type_edit_'
            } else {
                dynamicFieldsContainer = $('#dynamics_fields');
                nameIdField = 'field_name_';
                nameidSelect = 'select_type_'
            }
            addDynamicField(nameIdField, nameidSelect);            
        });

        function addDynamicField(nameIdField, nameidSelect) {
            var fieldId = new Date().getTime();
            var newRow = 
                `<div class="col-md-5 col-sm-12 col-12">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" >Field Name</span>
                            </div>
                            <input type="text" class="form-control" id="${nameIdField}${fieldId}">
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-sm-12 col-12">
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" for="selectType" >Field Type/Data</span>
                            </div>
                            <select class="custom-select dynamic-select" data-field-id="${fieldId}" id="${nameidSelect}${fieldId}">
                                <option selected>Choose...</option>
                                <option value="number">Number</option>
                                <option value="date">Date</option>
                                <option value="text">Text</option>
                                <option value="option">Options</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 col-sm-12 col-12 mb-3 text-center">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-row" >Remove</button>
                </div>`;

                dynamicFieldsContainer.append('<div class="row">' + newRow + '</div>');
                $('.btn-remove-row').click(function () { 
                    $(this).closest('.row').remove();                 
                });
        };

        // Fin de lógica para agregar rows dinámicas


        // Fix superposición de modal - #optionsModal aparece detrás
        $(document).on('show.bs.modal', '.modal', function() {
            const zIndex = 1040 + 10 * $('.modal:visible').length;
            $(this).css('z-index', zIndex);
            setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
        });
        // Fin de fix superposición de modal - #optionsModal aparece detrás


        $(document).on('change', '.dynamic-select', function () { 
            var selectedValue = $(this).val();
            var fieldId = $(this).data('field-id');

            if(selectedValue == "option") {
                $('#optionsModal').attr('data-currentFieldId', fieldId).modal('show');
            }
        });

        $('#saveOptions').click(function () { 
            var currentFieldId = $('#optionsModal').attr('data-currentFieldId');
            var options = $('#options').val();

            fieldOptions[currentFieldId] = options;

            var tagifyInstance = $('#options').data('tagify');

            if (tagifyInstance) {
                tagifyInstance.removeAllTags();
            }

            $('#options').val('');
            $('#optionsModal').modal('hide');
        });

        // Ingreso de nuevos formularios
        
        $('.save_form').click(function (e) { 
            e.preventDefault();

            var actionValue = $(this).data('button-form');
            var id = $('#formId').val();
            var formName = '';
            var method = '';
            var url = '';
            var formData = [];

            if(actionValue == 'edit') {
                method = 'PUT';
                url = "/form/" + id;
                formName = $('#formName_edit').val();
                dynamicFieldsContainer =  $('#dynamics_fields_edit');


                dynamicFieldsContainer.find('.row').each(function () {
                    var fieldId = $(this).find('[id^="field_name_edit_"]').attr('id').split('_').pop();
                    var fieldName = $('#field_name_edit_' + fieldId).val();
                    var fieldType = $('#select_type_edit_' + fieldId).val();
                    var options = $('#optionsEdit_' + fieldId).val();
                    var options2 = fieldOptions[fieldId];

                    var parsedOptions = options ? JSON.parse(options) : [];
                    var parsedOptions2 = options2 ? JSON.parse(options2) : [];

                    // Combina los arrays
                    var combinedOptions = parsedOptions.concat(parsedOptions2);

                    // Filtra duplicados
                    combinedOptions = combinedOptions.filter(function(option, index, self) {
                        return index === self.findIndex(o => o.value === option.value);
                    });

                    // Convierte el array combinedOptions a una cadena JSON
                    var optionsString = JSON.stringify(combinedOptions);
                    

        
                    formData.push({
                        id: fieldId,
                        name: fieldName,
                        type: fieldType,
                        options: optionsString,
                    });
                });

            } else {
                method = 'POST';
                url = "{{url('form')}}";
                formName = $('#formName').val();
                dynamicFieldsContainer = $('#dynamics_fields');

                dynamicFieldsContainer.find('.row').each(function () {
                    var fieldId = $(this).find('[id^="field_name_"]').attr('id').split('_').pop();
                    var fieldName = $('#field_name_' + fieldId).val();
                    var fieldType = $('#select_type_' + fieldId).val();
                    var options = fieldOptions[fieldId];

        
                    formData.push({
                        name: fieldName,
                        type: fieldType,
                        options: options
                    });
                });
            }
            
            
            formData.unshift({formName: formName});
            
            $.ajax({
                type: method,
                url: url,
                data: {formData: formData,
                    _token: '{{csrf_token()}}'},
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        });
                        $('#add_form_modal').modal('hide');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                        });
                    }
                    setTimeout(function() {
                        location.reload();
                    }, 3000)
                    
                },
                error: function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong with the request.',
                    });
                }
            });
            
        });

        // Fin ingreso de nuevos formularios


    });
</script>
@stop