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

@endsection

@section('content')
    <h3>Ketetapan {{ $version->pgdl_template->jenis->name }}</h3>
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
                    <h2><i class="fa fa-bars"></i> Template <small>Sheet</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li>
                            <a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="POST" action="{{ route('fileimportpengendalian.updateimport', ['version_id' => $version->id, 'id' => $id, 'sheet_id' => $sheet_id]) }}">
                        {!! csrf_field() !!}
                        {!! method_field('PUT') !!}
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Update Data
                            </div>
                            <div class="panel-default">
                                <br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group" style="margin-left: 25px;">
                                            <button type="submit" class="btn btn-primary"> Update </button>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="scroll form-group" style="width: 100%; height: 500px; overflow:scroll;">
                        <table class="table">
                            <?php $k = 1; $num = -1; $baris = 12; $start_baris = 0; ?>
                            @if($version->pgdl_template->jenis_id == 1)
                                @if($sheet_md->name == 'I-PENDUKUNG EP')
                                    <?php $start_baris = 5; ?>
                                @else
                                    <?php $start_baris = 3; ?>
                                @endif

                                @foreach($sheet as $key => $row2)
                                    <tr>
                                        @if(!empty($row2))
                                            @if($key >= $start_baris)
                                                <?php $baris++ ?>
                                                <td style="min-width: 50px;"><input type="checkbox" name="change[{{ $baris }}]" class="form-control check{{ $baris }}" value="{{ $baris }}"></td>
                                            @else
                                                <td></td>
                                            @endif
                                        @else
                                            <td></td>
                                        @endif
                                        @foreach($row2 as $value)
                                            @if($key >= $start_baris)
                                                @if(in_array($k, $updatable['updatable']))
                                                    <td style="min-width: 200px;"><input type="text" name="update[{{ $updatable['updatable_id'][$k] }}]" class="form-control row{{ $baris }}" value="{{ $value }}"></td>
                                                @else
                                                    <td style="text-align: center;">{{ $value }}</td>
                                                @endif 
                                            @else
                                                <td style="text-align: center;">{{ $value }}</td>
                                            @endif
                                            <?php $k++ ?>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @else
                                @if($version->pgdl_template->jenis->name == 'Form Bahan Bakar' || $version->pgdl_template->jenis->name == 'Penyusutan' || $version->pgdl_template->jenis_id == 7 || $version->pgdl_template->jenis_id == 9)
                                    <?php $start_baris = 4; ?>
                                @else
                                    <?php $start_baris = 5; ?>
                                @endif

                                @foreach($sheet as $key => $row2)
                                    <tr>
                                        @if(!empty($row2))
                                            @if($key >= $start_baris)
                                                <?php $baris++ ?>
                                                <td style="min-width: 50px;"><input type="checkbox" name="change[{{ $baris }}]" class="form-control check{{ $baris }}" value="{{ $baris }}"></td>
                                            @else
                                                <td></td>
                                            @endif
                                        @else
                                            <td></td>
                                        @endif
                                        @foreach($row2 as $value)
                                            @if($key >= $start_baris)
                                                @if(in_array($k, $updatable['updatable']))
                                                    <td style="min-width: 200px;"><input type="text" name="update[{{ $updatable['updatable_id'][$k] }}]" class="form-control row{{ $baris }}" value="{{ $value }}"></td>
                                                @else
                                                    <td style="text-align: center;">{{ $value }}</td>
                                                @endif
                                            @else
                                                <td style="text-align: center;">{{ $value }}</td>
                                            @endif
                                            <?php $k++ ?>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    @for ($x = 13 ; $x <= $baris ; $x++)
    <script type="text/javascript">
        $('.row{{ $x }}').each(function() {
            var elem = $(this);
            // Save curren t value of element
            elem.data('oldVal', elem.val());
            // Look for changes in the value
            elem.bind("propertychange change click keyup input paste", function(){
              // If value has changed...
                if (elem.data('oldVal') != elem.val()) {
               // Updated stored value
                    $('.check{{ $x }}').attr('checked', true);

                }
            });
        });
    </script>
    @endfor
    <!-- @for ($x = 1 ; $x <= $num+1 ; $x++)
    <script type="text/javascript">
        $('.row{{ $x }}').each(function() {
            var elem = $(this);
            // Save curren t value of element
            elem.data('oldVal', elem.val());
            // Look for changes in the value
            elem.bind("propertychange change click keyup input paste", function(){
              // If value has changed...
                if (elem.data('oldVal') != elem.val()) {
               // Updated stored value
                    $('.check{{ $x }}').attr('checked', true);

                }
            });
        });
    </script>
    @endfor -->
@endsection
