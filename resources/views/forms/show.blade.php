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
    <div class="row" style="padding-top:70px">
        <div class="col">            
            <div class="card">
                <div class="card-header">
                    <h2>Form {{$data->name}}</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($fields as $field)
                            <div class="col-md-4 col-12 mb-3">
                                @if($field['type'] == 'option')
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" for="selectType">{{ $field['name'] }}</span>
                                            </div>
                                            <select class="custom-select dynamic-select" data-field-type="{{ $field['type'] }}" data-field-id="{{ $field['id'] }}" id="select_type_{{ $field['id'] }}">
                                                <option selected>Choose...</option>
                                            @foreach ($field['options'] as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                            </select>
                                            <div class="alert alert-danger invalid-feedback" style="display:none; position: absolute; top:2rem" role="alert">
                                                Please, choose one option!
                                            </div>
                                        </div>
                                    </div>
                                @elseif($field['type'] == 'date')
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $field['name'] }}</span>
                                        </div>
                                        <input type="date" class="form-control dynamic-field" data-field-type="{{ $field['type'] }}" data-field-id="{{ $field['id'] }}" value="">
                                        <div class="alert alert-danger invalid-feedback" style="display:none; position: absolute; top:2rem" role="alert">
                                            Field {{ $field['name'] }} cannot be empty!
                                        </div> 
                                    </div>
                                </div>
                                @elseif($field['type'] == 'number')
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $field['name'] }}</span>
                                        </div>
                                        <input type="text" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Only numbers allowed" class="form-control dynamic-field" data-field-type="{{ $field['type'] }}" data-field-id="{{ $field['id'] }}" value="">
                                        <div class="alert alert-danger invalid-feedback" style="display:none; position: absolute; top:2rem" role="alert">
                                            Field {{ $field['name'] }} cannot be empty!
                                        </div> 
                                    </div>
                                </div>                              
                                @else
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ $field['name'] }}</span>
                                            </div>
                                            <input type="text" class="form-control dynamic-field" data-field-type="{{ $field['type'] }}" data-field-id="{{ $field['id'] }}" value="">
                                            <div class="alert alert-danger invalid-feedback" style="display:none; position: absolute; top:2rem" role="alert">
                                                Field {{ $field['name'] }} cannot be empty!
                                            </div> 
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <div>
                        <button id="guardarCambios" class="btn btn-sm btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')

@stop

@section('js')
<script>
    $(document).ready(function () {

        var userRoles = @json($userRoles);
        if (userRoles.includes('reader')) {
            var enlaceToDeleteForms = $('.admin-role');
            enlaceToDeleteForms.hide();
        }
        
        $('#guardarCambios').click(function (e) {
            e.preventDefault();
            var isValid = true;

            var valuesToUpdate = {};

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
                var fieldId = $(this).data('field-id');
                var fieldType = $(this).data('field-type');
                var selectedValue = $(this).val();
                valuesToUpdate[fieldId] = {value: selectedValue, type: fieldType};
            });

            $('.dynamic-field').each(function () {
                var currentField = $(this);

                if (currentField.val() === '') {
                    isValid = false;
                    var feedback = currentField.next('.invalid-feedback');
                    console.log(feedback);
                    feedback.show();
                    setTimeout(function () {
                        feedback.fadeOut(3000);
                    }, 1000);
                    return false;
                } else {
                    currentField.next('.invalid-feedback').hide();
                }
                var fieldId = $(this).data('field-id');
                var fieldType = $(this).data('field-type');
                var textValue = $(this).val();
                valuesToUpdate[fieldId] = {value: textValue, type: fieldType};
            });

            if(!isValid) { //Corta la ejecución del código para que no llegue al submit, si hay un campo vacío
                return;
            }

            $.ajax({
                type: 'POST',
                url: '/storeValues',
                data: {values: valuesToUpdate,
                    _token: '{{csrf_token()}}'},
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.error,
                        });
                    }
                    setTimeout(function() {
                        location.reload();
                    }, 3000)
                },
                error: function (error, xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: error.responseJSON.message,
                    });
                }
            });
        });
    });
</script>

@stop
