@extends('layouts.app')

@section('js_page')
    <script type="text/javascript">
        $(function() {
            $("#checkAll").click(function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
        });
    </script>
@endsection

@section('content')
@if(Session::has('failed')) 
<br>
    <div class="alert alert-danger"> 
        {{Session::get('failed')}} 
    </div> 
@endif
    <h1>Pilih Sheet yang di export</h1>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Sheet</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <!-- <form method="POST" id="form" action="{{ route('fileimport.export', ['version_id' => $version, 'id' => $id]) }}" class="form-horizontal form-label-left"> -->
                    <form method="POST" id="form" action="{{ route('fileimportpengendalian.export', ['version_id' => $version, 'id' => $id]) }}" class="form-horizontal form-label-left">
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sheet Yang di export<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="mappingtable">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>Nama Sheet</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($sheet as $row)
                                        <tr>
                                            <td><input type="checkbox" name="sheet[]" value="{{ $row->id }}"></td>
                                            <td>{{ $row->name }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
