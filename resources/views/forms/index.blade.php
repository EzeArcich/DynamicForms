@extends('adminlte::page')

@section('title', 'Dashboard')
<style>
    .tagify.form-control {
        display: inline-block !important;
        height: auto !important;
    }

    .alert-danger {
        color: #fff!important;
        background-color: #ef7f8a!important;
        padding: 0.35rem 1.25rem!important;
        line-height: 0.5rem!important;
        margin-top: 0.6rem!important;
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
                                            <div class="alert alert-danger invalid-feedback" style="display:none; position: absolute; top:2rem" role="alert">
                                                Form Name cannot be empty!
                                            </div>
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
                                <!-- Espacio para líneas generadas de forma dinámica -->
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
<script>
    var fieldOptions = [];
    var userRoles = @json($userRoles);

    $(document).ready(function () {

        if (userRoles.includes('reader')) {
            var enlaceToDeleteForms = $('.admin-role');
            enlaceToDeleteForms.hide();
        }

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
                        $('#forms_table').dataTable().fnDestroy();
                        getTableData();
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: "Error al obtener los datos del formulario.",
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: "Error al obtener los datos del formulario.",
                    });
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
                    <div class="col-md-5 col-12 mb-3">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Field Name</span>
                                </div>
                                <input type="text" class="form-control dynamic-field" id="field_name_edit_${fieldIdEdit}" value="${fieldNameEdit}">
                                <div class="alert alert-danger invalid-feedback" style="display:none; position: absolute; top:2rem" role="alert">
                                    Field Name cannot be empty!
                                </div>
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
                                <div class="alert alert-danger invalid-feedback" style="display:none; position: absolute; top:2rem" role="alert">
                                    You have to choose a option!
                                </div>
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
                            <label for="optionsEdit_${fieldIdEdit}">Insert field options</label> - Type and press Enter
                            <input id="optionsEdit_${fieldIdEdit}" name="optionsEdit_${fieldIdEdit}" class="form-control"
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
                `<div class="col-md-5 col-sm-12 col-12 mb-3">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" >Field Name</span>
                            </div>
                            <input type="text" class="form-control dynamic-field" id="${nameIdField}${fieldId}">
                            <div class="alert alert-danger invalid-feedback" style="display:none; position: absolute; top:2rem" role="alert">
                                Field Name cannot be empty!
                            </div>
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
                            <div class="alert alert-danger invalid-feedback" style="display:none; position: absolute; top:2rem" role="alert">
                                You have to choose a option!
                            </div>
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
                
                // $('#optionsModal').attr('data-currentFieldId', fieldId).modal('show');
                
                var newRowEditInput = `
                <div class="col-12">
                    <div class="form-group">
                        <label for="optionsEdit_${fieldId}">Insert field options</label> - Type and press Enter
                        <input id="optionsEdit_${fieldId}" name="optionsEdit_${fieldId}" class="form-control"
                        style="display: inline-block !important; height: auto !important;">
                    </div>
                </div>`;
                
                var newRow = $('<div class="row">' + newRowEditInput + '</div>');
                dynamicFieldsContainer.append(newRow);

                var optionsEditInput = newRow.find(`#optionsEdit_${fieldId}`);
                optionsEditInput.tagify();
                
            }
        });
        
        $('.save_form').click(function (e) { 
            e.preventDefault();
            var isValid = true;

            $('.dynamic-field').each(function () {
                var currentField = $(this);

                if (currentField.val() === '') {
                    isValid = false;
                    var feedback = currentField.next('.invalid-feedback');
                    feedback.show();
                    setTimeout(function () {
                        feedback.fadeOut(3000);
                    }, 1000);
                    return false;
                } else {
                    currentField.next('.invalid-feedback').hide();
                }
            });

            $('.dynamic-select').each(function () {
                var currentField = $(this);

                if (currentField.val() === 'Choose...') {
                    isValid = false;
                    var feedback = currentField.next('.invalid-feedback');
                    feedback.show();
                    setTimeout(function () {
                        feedback.fadeOut(3000);
                    }, 1000);
                    return false;
                } else {
                    currentField.next('.invalid-feedback').hide();
                }
            });

            if(!isValid) { //Corta la ejecución del código para que no llegue al submit, si hay un campo vacío
                return;
            }

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
                    var fieldIdElement = $(this).find('[id^="field_name_"]');
                    if (fieldIdElement.length === 0) {
                        return true;
                    }
                    var fieldId = fieldIdElement.attr('id').split('_').pop();
                    var fieldName = $('#field_name_edit_' + fieldId).val();
                    var fieldType = $('#select_type_edit_' + fieldId).val();
                    var options = $('#optionsEdit_' + fieldId).val();                   

        
                    formData.push({
                        id: fieldId,
                        name: fieldName,
                        type: fieldType,
                        options: options
                    });
                });

            } else {
                method = 'POST';
                url = "{{url('form')}}";
                formName = $('#formName').val();
                dynamicFieldsContainer = $('#dynamics_fields');

                dynamicFieldsContainer.find('.row').each(function () {
                    var fieldIdElement = $(this).find('[id^="field_name_"]');
                    
                    // Verificar si existe el campo field_name_ en la fila actual
                    if (fieldIdElement.length === 0) {
                        // Saltar esta iteración si no se encuentra el campo
                        return true;
                    }

                    var fieldId = fieldIdElement.attr('id').split('_').pop();
                    var fieldName = $('#field_name_' + fieldId).val();
                    var fieldType = $('#select_type_' + fieldId).val();
                    var options = $('#optionsEdit_' + fieldId).val();

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
                        $('#editFormModal').modal('hide');
                        $('#forms_table').dataTable().fnDestroy();
                        getTableData();
                    } else {
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
            
        });

        // Fin ingreso de nuevos formularios


    });
</script>
@stop