@extends('layouts.app')

@section('content')

    <h1>Detail Form Input Bahan Bakar</h1>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Detail
                </div>
                <div class="panel-default">
                    <br>
                    <div class="row mb-10">
                        <div class="col-md-12">
                            <div class="col-md-5 col-md-1">
                                <label class="col-md-7 col-md-3"> Tahun </label>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="text" name="name" value="{{ $form_bahan_bakar->tahun }}" disabled>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-5 col-md-1">
                                <label class="col-md-7 col-md-3"> Nama </label>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="text" name="name" value="{{ $form_bahan_bakar->name }}" disabled>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>

            <div class="col-md-12 text-right">
                <a href="{{ route('excel_detail_form_bahan_bakar', $form_bahan_bakar->id) }}" class="btn btn-success">
                    <i class="fa fa-download"></i> Download Excel
                </a>
            </div>

            <div class="x_content">
                <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Distrik</th>
                        <th>PRK</th>
                        <th>Januari</th>
                        <th>Februari</th>
                        <th>Maret</th>
                        <th>April</th>
                        <th>Mei</th>
                        <th>Juni</th>
                        <th>Juli</th>
                        <th>Agustus</th>
                        <th>September</th>
                        <th>Oktober</th>
                        <th>November</th>
                        <th>Desember</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($datas as $data)
                        <tr>
                            <td>{{ $data['distrik'] }}</td>
                            <td>{{ $data['prk'] }}</td>
                            @foreach($data['value'] as $value)
                                @for($i=0; $i < 12; $i++)
                                  <td>{{ round($value[$data['prk']][$i], 2) }}</td>    
                                @endfor
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@stop