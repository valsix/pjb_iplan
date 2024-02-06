@extends('layouts.main')
@section('css_page')
    <style type="text/css">
        .table-container
        {
            width: 100%;
            overflow-x: auto;
            overflow: auto;
            margin: 0 0 1em;
        }
        thead th{
            text-align: center;
        }

        /Update line height & font-size/
        .table thead tr th{
          line-height: 1;
        }
        .table tbody tr td{
          /Untuk data yang deskripsi panjang/
          line-height: 1; 

          /Untuk data yang tidak ada deskripsi panjang/
          /*line-height: 0.5; */
        }
        .table {
          font-size: 11px;
        }

        .form-horizontal .form-group
        {
            margin-right: 0;
            margin-left: 0;
            margin-top: -13px;
        }

    </style>

@endsection

@section('content')
<div role="main">
    <div class="row">
      <div class="page-title">
        <div>
          <h3>SETTING DASHBOARD & REPORT DINAMIS</h3>
        </div><br>
          <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="panel panel-default">
                  <div class="panel-heading">
                    Pencarian     
                  </div>
                  <div class="panel-default"><br/>
                    <form method="post" class="form-horizontal form-label-left" action="">
                      <div class="form-group">
                        <label class="col-md-2 col-sm-3 col-xs-12 ml-10">Halaman</label>
                          <div class="col-md-4 col-sm-4 col-xs-12" style="margin-left:-50px;">
                            <select class="form-control col-md-7 col-xs-12" name="page">
                                <option value="0">--Select Page--</option>
                              @foreach($page as $p)
                                <option value="{{$p->id}}">{{$p->id}}. {{$p->name}}</option>
                              @endforeach
                            </select>
                          </div>
                      </div>
                      <div class="form-group" style="margin-top:-50px;">
                        <label class="col-md-2 col-sm-3 col-xs-12" style="margin-left:50px;">Tahun</label>
                          <div class="col-md-4 col-sm-4 col-xs-12" style="margin-left:-70px;">
                            <select class="form-control col-md-7 col-xs-12" name="tahun">
                                <option value="0">--Pilih Tahun--</option>
                              @foreach($tahun as $thn)
                                <option value="{{$thn->tahun}}">{{$thn->tahun}}</option>
                              @endforeach
                            </select>
                          </div>
                      </div>
                      <div>
                        <button type="submit" class="btn btn-primary" style="margin-left: 190px" id="cari">
                            <span class="glyphicon glyphicon-search"> </span> Cari
                        </button>
                      </div>
                    </form>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                  <h2 style="font-size: 18px;">SETTING DASHBOARD & REPORT DINAMIS</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table  class="table table-striped table-bordered table-hover" id="tabeldata">
                      <thead style="background:#2A3F54;color:white;">
                        <tr role="row">
                          <th colspan="1" rowspan="1">No.</th>
                          <th colspan="1" rowspan="1">Judul Kolom </th>
                          <th colspan="1" rowspan="1">Source </th>
                          <th colspan="1" rowspan="1">Jenis Excel </th>
                          <th colspan="1" rowspan="1">Sheet </th>
                          <th colspan="1" rowspan="1">Kolom di Excel</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
              </div>
                  <button type="button" class="btn btn-primary pull-right">Simpan</button>
              </div>
            </div>
        </div>
      </div>
    </div>
</div>

<!-- </div> -->
@endsection

@section('js_page')
<script>
  $(document).ready(function(){    
    
    $("#cari").click( function(event)
      { event.preventDefault(); 
        var id=$('select[name="page"]').val();
        var tahun = $('select[name="tahun"]').val();
        if(id!=0 && tahun!=0)
        {  
          $('#tabeldata').DataTable({
            stateSave : true,
            processing: true,
            serverSide: true,
            pagingType: "full_numbers",
            paging: true,
            lengthMenu: [10, 25, 50, 75, 100],

            ajax: '/dashboard/page/ajax?page_id='+id+'&tahun='+tahun,
            columns: [
              {data: 'id'},
              {data: 'judul_kolom'},
              {data: 'judul_kolom'},
              {data: 'judul_kolom'},
              {data: 'judul_kolom'},
              {data: 'judul_kolom'},
            ]
          });
        } 

    });

  });



</script>


@endsection
