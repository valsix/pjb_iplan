@extends('layouts.app')

@section('css_page')
    <style type="text/css">
        .table-container
        {
            width: 100%;
            overflow-x: auto;
            overflow: auto;
            margin: 0 0 1em;
        }
    </style>
@endsection

@section('js_page')
    <script type="text/javascript">
        $('#form').submit(function() {
            // Animate loader off screen
            $(".se-pre-con").fadeIn("slow");
        });
    </script>
@endsection

@section('content')
    <h3>Setting {{ $version->pgdl_template->jenis->name }}</h3>
    @if(session('salah'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session('salah') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <a href="{{ route('templatepengendalian.show', ['jenis_id' => $version->pgdl_template->jenis_id, 'id'=>$version->template_id]) }}" class="btn btn-primary"> Kembali </a>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Import Setting
                        </div>
                        <div class="panel-default">
                            <br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <form method="POST" id="form" action="{{ route('sheetpengendalian.import', $version) }}" enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <div class="form-group">
                                            <label class="control-label col-md-1 col-sm-1 col-xs-12">File Excel<span class="required">*</span>
                                            </label>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <input type="file" name="file" required="required" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"s>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary"> Upload </button>
                                        </div>
                                    </form>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab1" class="nav nav-tabs bar_tabs left" role="tablist">
                            @foreach($sheet_md as $row)
                            <li role="presentation" class=""><a href="#{{ slugify($row->name) }}" id="home-tabb" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">{{ $row->name }}</a>
                            </li>
                            @endforeach
                        </ul>
                        <div id="myTabContent2" class="tab-content">
                            @foreach($sheet_md as $row)
                            <div role="tabpanel" class="tab-pane fade" id="{{ slugify($row->name) }}" aria-labelledby="home-tab">
                                <div class="x_content">
                                    <a href="{{ route('sheetpengendalian.setting', ['version_id' => $version, 'id' => $row->id]) }}" class="btn btn-primary"> Setting {{ $row->name }}</a>
                                </div>
                                <div class="scroll" style="width: 100%; height: 500px; overflow:scroll;">
                                    <table class="table">
                                        @foreach($sheet[$row->name] as $row2)
                                            <tr>
                                                @foreach($row2 as $value)
                                                    <td>{{ $value }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>

                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
@endsection
