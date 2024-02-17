@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')
    <div class="row" style="padding-top:70px">
        <div class="col">            
            <div class="card">
                <div class="card-header">
                    Form
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($fields as $field)
                            <div class="col-4">
                                @if($field['type'] == 'option')
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" for="selectType">{{ $field['name'] }}</span>
                                            </div>
                                            <select class="custom-select dynamic-select" data-field-id="{{ $field['id'] }}" id="select_type_{{ $field['id'] }}">
                                                @foreach (json_decode($field['options']) as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ $field['name'] }}</span>
                                            </div>
                                            <input type="text" class="form-control" id="field_name_" value="">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')

@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
