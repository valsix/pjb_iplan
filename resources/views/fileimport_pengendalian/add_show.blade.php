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

@section('content')
    <h3>Draft {{ $version->pgdl_template->jenis->name }} Tahun Anggaran {{ $version->pgdl_template->tahun }}</h3>

    @if(session('salah'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session('salah') }}
        </div>
    @endif

    @if(!empty($fileimport->error))
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Error</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {!! $fileimport->error !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <ul>
                @foreach (session('error') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Add Excel Data
                        </div>
                        <div class="panel-default">
                            <br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <form target="_blank" onsubmit="reDir()" method="POST" action="{{ route('fileimportpengendalian.import.use.add.excel', ['version_id' => $version->id, 'id' => $id]) }}" enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <div class="form-group">
                                            <label class="control-label col-md-1 col-sm-1 col-xs-12">File Excel<span class="required">*</span>
                                            </label>
                                            <div class="col-md-3 col-sm-3 col-xs-12">
                                                <input type="file" name="file" required="required" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" id='upload' class="btn btn-primary"> Submit </button>
                                        </div>
                                    </form>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('templatepengendalian.show', ['jenis_id' => $version->pgdl_template->jenis_id, 'id'=>$version->template_id]) }}" class="btn btn-primary"> Kembali </a>
                    <a href="{{ route('fileimport.export.use', ['version_id' => $version->id, 'id' => $id]) }}" class="btn btn-primary"> Download Excel </a>

                    {{-- Khusus pengendalian di hidden --}}
                    {{-- <a href="{{ route('history.index', $id) }}" class="btn btn-primary"> Riwayat </a> --}}

                    @if ($version->pgdl_template->jenis_id != 2 && $version->pgdl_template->jenis_id != 3)
                      <div class="x_panel">
                              <div class="x_title">
                                  <h2>Daftar Sheet</h2>
                                  <div class="clearfix"></div>
                              </div>
                              <div class="x_content">
                                  <!-- start pop-over -->
                                  <div class="bs-example-popovers">
                                      @foreach($sheet_md as $row)
                                      <a href="{{ route('fileimport.showsheet', ['version_id' => $version->id, 'id' => $id, 'sheet_id' => $row->id]) }}" class="btn btn-default loading">
                                          <h2>{{ $row->name }}</h2>
                                      </a>
                                      @endforeach
                                  </div>
                                  <!-- end pop-over -->
                              </div>
                          </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <script>
    function reDir() {
          setTimeout(function () {
             // window.onbeforeunload = null;
             history.back();
          }, 10);
        }
    </script>


@endsection
