@extends('layouts.app')

@section('css_page')

    <!-- searching -->
    <script src="{{ asset('js/jquery-1.11.2.min.js') }}" type="text/javascript"></script>

    <!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">

    <!-- EASYUI 1.4.5 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/easyui/themes/default/easyui.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/easyui/themes/icon.css') }}">
@endsection

@section('js_page')
    <script src="{{ asset('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('vendors/jszip/dist/jszip.min.js') }}"></script>
    <script src="{{ asset('vendors/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendors/pdfmake/build/vfs_fonts.js') }}"></script>

    <!-- EASYUI 1.4.5 -->
    <script type="text/javascript" src="{{ asset('vendors/easyui/jquery.easyui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/easyui/datagrid-groupview.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/easyui/globalfunction.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/easyui/kalender-easyui.js') }}"></script>    

    <script type="text/javascript">
        $('#datatable').dataTable( {
            "searching": true
        } );
    </script>
@endsection

@section('content')
<div  role="main">
          <div class="">
            <div class="page-title">
                <div>
                    <h3> Daftar Bagian</h3>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session('success') }}
                        </div>
                    @endif
                </div>


              <!-- <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                         Pencarian
                    </div>
                    <div class="panel-default"> 
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <form>
                                    <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                    <div class="col-md-4">
                                        <select class="form-control" name="strategi_bisnis">
                                           <option></option>
                                           @foreach ($Sb as $sbs => $value)
                                             <option value="{{ $value->id }}"> {{ $value->name }} </option>
                                           @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2"><label>Distrik</label></div>
                                    <div class="col-md-4">
                                        <select class="form-control" name="distrik">
                                           
                                        </select>
                                    </div>

                                     <script type="text/javascript">
                                        $(document).ready(function() {
                                            $('select[name="strategi_bisnis"]').on('change', function() {
                                                var strategi_bisnisID = $(this).val();
                                                $('select[name="distrik"]').empty();
                                                $('select[name="lokasi"]').empty();

                                                if(strategi_bisnisID) {
                                                    $.ajax({
                                                        url: '/distrik/daftar/ajax/'+strategi_bisnisID,
                                                        type: "GET",
                                                        dataType: "json",
                                                        success:function(data) {
                                                    // console.log(data);
                                                          $('select[name="distrik"]').empty();
                                                          $.each(data, function(sb, value) {
                                                              $('select[name="distrik"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                                                          });

                                                        }
                                                    });
                                                }else{
                                                    $('select[name="distrik"]').empty();
                                                }
                                            }); 
                                        });
                                    </script>
                                   
                                    <br>
                                    <br>
                                    <div class="col-md-2"><label> Lokasi</label></div>
                                    <div class="col-md-4">
                                        <select class="form-control" name="lokasi">
                                           <option></option>
                                           
                                        </select>
                                    </div>

                            
                                   <script type="text/javascript">
                                        $(document).ready(function() {
                                            $('select[name="distrik"]').on('change', function() {
                                                var lokasiID = $(this).val();
                                                $('select[name="lokasi"]').empty();

                                                if(lokasiID) {
                                                    $.ajax({
                                                        url: '/distrik/daftar/ajax2/'+lokasiID,
                                                        type: "GET",
                                                        dataType: "json",
                                                        success:function(data) {
                            
                                                          $('select[name="lokasi"]').empty();
                                                          console.log(data);
                                                          // $('select[name="lokasi"]').append('<option value="">==Silahkan Pilih Lokasi==</option>');
                                                          $.each(data, function(ad , value) {
                                                          console.log(ad);
                                                              $('select[name="lokasi"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                                                          });

                                                         }
                                                    });
                                                }else{
                                                    $('select[name="lokasi"]').empty();

                                                }
                                            });
                                        });
                                    </script>

                                    <div>
                                       <button type="submit" class="btn btn-primary"> 
                                           <span class="glyphicon glyphicon-search"> </span> cari 
                                       </button>   
                                    </div>
                                </form>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
              </div> -->

            
                <div> <a href="create" class="btn btn-primary">Tambah Bagian</a> <a href="sinkron" class="btn btn-primary">Sinkron Data</a></div>
                <br>
                <div class="x_content">
                    <table id="tt" class="easyui-treegrid" data-options=
                            "
                                url: '/jabatan/tree'
                                , rownumbers: false
                                , pagination: false
                                // , height: 'auto'
                                , idField: 'ID'
                                , treeField: 'NAMA'
                                , onSelect:function(node){
                                    // console.log(node);
                                    selectedNodeId = node.ID;
                                    //console.log(selectedNodeId);
                                }
                                , onLoadSuccess: function(row, data){
                                }
                                , onBeforeLoad: function(row,param){
                                    if (!row) {
                                        param.id = 0;
                                    }
                                }
                            " style="height:470px">
                        <thead>
                            <tr>
                                <!-- <th data-options="field:'ID'" width="100">Kode Jabatan</th> -->
                                <th data-options="field:'NAMA'" width="770">Nama Jabatan</th>
                                <th data-options="field:'UNIT'" width="200">Unit</th>
                                <th field="LINK_URL_INFO" width="90" align="center">Aksi</th>
                            </tr>
                        </thead>
                            
                    </table>
                </div>
            </div>
          </div>
</div>

<script type="text/javascript">
    // $(document).ready(function(){

    //     $('#tt').treegrid({
    //         url: "/jabatan/tree"
    //         , rownumbers: false
    //         , pagination: false
    //         , idField: 'ID'
    //         , treeField: 'NAMA'
    //         , onSelect:function(node){
    //             selectedNodeId = node.ID;
    //         }
    //         , onLoadSuccess: function(row, data){
    //         }
    //         , onBeforeLoad: function(row,param){
    //             if (!row) {
    //                 param.id = 0;
    //             }
    //         }
    //     });

    // });

    function searchtree(value)
    {
        $('#tt').treegrid({
            url: '/jabatan/tree?reqSearch='+value
            , rownumbers: false
            , pagination: false
            , idField: 'ID'
            , treeField: 'NAMA'
            , onSelect:function(node){
                selectedNodeId = node.ID;
            }
            , onLoadSuccess: function(row, data){
            }
            , onBeforeLoad: function(row,param){
                if (!row) {
                    param.id = 0;
                }
            }
        });
    }
</script>
@endsection
