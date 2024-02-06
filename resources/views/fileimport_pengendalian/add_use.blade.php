@extends('layouts.app')

@section('js_page')
    <!-- sementara pakai ini, karena button tombol close belum ada -->
    <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
    <!-- <script src="{{ asset('vendors/jquery.ui/jquery-ui.min.js') }}"></script> -->
    <!-- <link href="{{ asset('vendors/jquery.ui/jquery-ui.css') }}" rel="stylesheet"> -->
    <script type="text/javascript">
        $(function() {
            $("#checkAll").click(function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
            $( "#dialog" ).dialog({
                // position: ["left","top"],
                // width:"100%",
                // height:$(window).height(),
                // zIndex: 1000,
                        modal: true,
                  // buttons: {
                  //   Ok: function() {
                  //     $( this ).dialog( "close" );
                  //   }
                  // },

                autoOpen: false
          });
        });
        $('#form').submit(function() {
            // Animate loader off screen
            // $(".se-pre-con").fadeIn("slow");

            // Open dialog
            $("#dialog").dialog('open');
        });

        $("#tutup_browser").click(function() {
            window.top.close();
        });
    </script>
@endsection

@section('content')
    <div id="dialog" title="Pesan Upload" style="display:none">
      <p>Excel telah terupload. Silahkan tunggu pada tab sebelumnya dan tutup browser</p>
      <button id="tutup_browser">Tutup Browser</button>
    </div>

    <h1>Pilih Sheet yang di import</h1>
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
                    <form method="POST" id="form" action="{{ route('fileimportpengendalian.import.add.excel', ['version_id' => $version, 'id' => $id]) }}" class="form-horizontal form-label-left">
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sheet Yang di import<span class="required">*</span>
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
                                    @foreach($sheet as $key => $row)
                                        <tr>
                                            <td><input type="checkbox" name="sheet[]" value="{{ $row }}"></td>
                                            <td>{{ $row }}</td>
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
