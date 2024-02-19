@extends('adminlte::page')

@section('title', 'Dashboard')

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
                            <div class="col-md-4 col-12">
                                @if($field['type'] == 'option')
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" for="selectType">{{ $field['name'] }}</span>
                                            </div>
                                            <select class="custom-select dynamic-select" data-field-type="{{ $field['type'] }}" data-field-id="{{ $field['id'] }}" id="select_type_{{ $field['id'] }}">
                                            @foreach ($field['options'] as $option)
                                                <option value="{{ $option }}" @if(!empty($field['values']) && $field['values'][0]['value'] === $option) selected @endif>{{ $option }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @elseif($field['type'] == 'date')
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $field['name'] }}</span>
                                        </div>
                                        <input type="date" class="form-control" data-field-type="{{ $field['type'] }}" data-field-id="{{ $field['id'] }}" value="{{ $field['values'][0]['value'] ?? '' }}">
                                    </div>
                                </div>
                                @elseif($field['type'] == 'number')
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $field['name'] }}</span>
                                        </div>
                                        <input type="text" pattern="[0-9]*" placeholder="Only numbers allowed" class="form-control" data-field-type="{{ $field['type'] }}" data-field-id="{{ $field['id'] }}" value="{{ $field['values'][0]['value'] ?? '' }}">
                                    </div>
                                </div>                              
                                @else
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ $field['name'] }}</span>
                                            </div>
                                            <input type="text" class="form-control" data-field-type="{{ $field['type'] }}" data-field-id="{{ $field['id'] }}" value="{{ $field['values'][0]['value'] ?? '' }}">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function () {
        $('#guardarCambios').click(function () {

            var valuesToUpdate = {};

            $('.dynamic-select').each(function () {
                var fieldId = $(this).data('field-id');
                var fieldType = $(this).data('field-type');
                var selectedValue = $(this).val();
                valuesToUpdate[fieldId] = {value: selectedValue, type: fieldType};
            });

            $('.form-control').each(function () {
                var fieldId = $(this).data('field-id');
                var fieldType = $(this).data('field-type');
                var textValue = $(this).val();
                valuesToUpdate[fieldId] = {value: textValue, type: fieldType};
            });

            $.ajax({
                type: 'PUT',
                url: '/updateValues',
                data: {values: valuesToUpdate,
                    _token: '{{csrf_token()}}'},
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
