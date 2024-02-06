@extends('layouts.app')
@section('css_page')
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
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

        .table {
          font-size: 13px;
        }

        .form-horizontal .form-group
        {
            margin-right: 0;
            margin-left: 0;
            margin-top: -13px;
        }
    .big {
      font-size: 1.2em;
    }
    .custom-dropdown {
      display: none;
      position: relative;
      display: inline-block;
      vertical-align: middle;
      width:100%;
      height:100%;
    }

    .custom-dropdown select {
      background-color: #1A5B6F;
      color: #fff;
      font-size: inherit;
      padding: .5em;
      border: 1;
      margin: 0;
      border-radius: 3px;
      text-indent: 0.01px;
      text-overflow: '';
      appearance: none;
    }
    .custom-dropdown select::-ms-expand {
        display: none;
    }

    .custom-dropdown::before,
    .custom-dropdown::after {
      content: "";
      position: absolute;
      pointer-events: none;
    }
    .custom-dropdown::after {
      color: rgba(0,0,0,.6);
    }
    .custom-dropdown select[disabled] {
      color: rgba(0,0,0,.25);
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
                        <button type="submit" class="btn btn-primary" style="margin-left: 600px" id="copy">
                            <span class="glyphicon glyphicon-copy"> </span> Copy
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
                  <p>Show <span>
                      <select name="isi_pagination" id="">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="all">All</option>
                      </select>
                    </span> rows
                  </p>
                  <div class="table-full-width spinner-container" id="hasil_tabel"> 
                  <div class="spinner-back">
                    <table  class="table table-striped table-bordered table-hover">
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
                      <tbody class="spinner-back-placeholder">
                          @for ($i = 0; $i < 10; $i++)
                          <tr class="clickable-row"> 
                              <td><div class=" bg-primary-lighter">&nbsp;</div></td>
                              <td><div class=" bg-primary-lighter">&nbsp;</div></td>
                              <td>
                                <div class=" bg-primary-lighter"></div>
                              </td>
                              <td><div class=" bg-primary-lighter">&nbsp;</div></td>
                              <td><div class=" bg-primary-lighter">&nbsp;</div></td>
                              <td><div class=" bg-primary-lighter">
                              <input type="text" name="" class="form-control"></div></td>
                          </tr>
                          @endfor
                      </tbody>
                      <tbody id="hasil_body"><tbody> 
                    </table>
                    </div>
                    <div class="flex-center" style="margin-left:300px;">
                        <ul id="pagination" class="pagination"></ul>
                    </div>
                </div>
              </div>
                  <button id="simpan" class="btn btn-primary pull-right">Simpan</button>
              </div>
            </div>
        </div>
      </div>
    </div>
</div>

<!-- </div> -->
@endsection

@section('js_page')
<script src="{{ asset('js/sweetalert2.all.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/sweetalert2.min.js') }}" type="text/javascript"></script>
<script src="{{asset('js/mustache.js')}}"></script>
<script src="{{asset('js/pagination-twb.min.js')}}"></script>
<script type="text/javascript">
    var customTags = [ '@{{', '}}' ];
</script>
<script id="template" type="x-tmpl-mustache">
@{{#data}}
    <tr style="background-color:#ffffff ; color:black" role="row" id="@{{id}}"> 
        <td class="text-center" role="column">@{{nomor}}</td>
        <td class="text-center" style="padding-top:17px;" role="column">@{{judul_kolom}}</td>
        <td class="text-center" style="padding-top:17px;">@{{nama_source}}</td>
        @{{#source_id}}
          <td class="text-center" style="padding-top:17px;">@{{nama_jenis}}</td>
          <td class="text-center" style="padding-top:17px;">@{{nama_sheet}}</td>
        @{{/source_id}}
        @{{^source_id}}
          <td style="background-color:#1A5B6F"></td>
          <td style="background-color:#1A5B6F"></td>
        @{{/source_id}}
        <td>
          <input type="text" name="kolom[]" id="input_kolom_@{{id}}" value="@{{kolom}}" class="form-control">
          <input type="hidden" name="id_baris[]" value="@{{id}}" class="form-control">
        </td>
    </tr>
    @{{/data}}
</script>

<script>
    var total_pages = 1;
    var visible_pages = 5;
    var items_show = 10;
    var per_page = 10;
    var firstLoadPagination = false;
    var id='';
    var tahun='';
    var is_saved=0;
    var total_data;
    
  $(document).ready(function(){
    $("#cari").click( function(e)
      { 
        e.preventDefault();
        items_show = 10 ;
        per_page  = 10;
        var page_id=$('select[name="page"]').val();
        var tahun_pilih = $('select[name="tahun"]').val();
        if(page_id!=0)
        {  
          id=page_id;
          tahun=tahun_pilih;
          loadData(1);
        }
      });
      
    $("#simpan").click(function(event){
      event.preventDefault();
      saveInput();
      is_saved=1;
    });
    

    $('select[name="isi_pagination"]').on('change', function() {
      if(this.value == "all"){
        items_show = total_data ;
        per_page  = total_data;
      }else{
        items_show = this.value ;
        per_page  = this.value;
      }
      // console.log(per_page);
      loadData(1);


      
    });

    $("#copy").click(function(event){
      event.preventDefault();
      var thn = $('select[name="tahun"]').val();
      if(thn!=0){
        var data={
                   _token: '{!! csrf_token() !!}',
                   tahun : thn
                };
            $.ajax({
            type: "POST",
            url: "{{ url('/dashboardDinamis/copyTahun/ajax/') }}",
            data :data,
            success:function(data) {
              // console.log(data);
              const toast = swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000
                });
                if(data.copy)
                {
                  toast({
                    type: 'success',
                    title: data.pesan
                  });
                }
                else
                {
                  toast({
                    type: 'error',
                    title: data.pesan
                  });
                }
            
            },
            error: function(error) {
              console.log('gagal copy data '+error.status);
            },
        });

      }
      else{
          const toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
          });

          toast({
            type: 'error',
            title: 'Tahun Belum di Pilih'
          });
      }

    });

    function loadData(currentPage=1)
    {
        // $('.spinner').fadeIn();
        $('#hasil_tabel').show();
        $('#hasil_body').hide();
        $('.spinner-back-placeholder').show();

        $.ajax({
            url: "{{ url('/dashboard/page/ajax?page=') }}"+ currentPage + '&per_page='+ per_page + '&page_id=' + id +'&tahun='+tahun ,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.total>0)
                { 
                  //  console.log(data);
                    $('#hasil_tabel').show();
                    total_data = data.total;
                    total_pages = Math.ceil(data.total/items_show);
                    var template = $('#template').html();
                    loadMustache(template);
                    var rendered = Mustache.render(template, data);
                    $('#hasil_body').show();
                    $('#hasil_body').html(rendered);
                    $('.spinner-back-placeholder').hide();
                    
                    loadPagination(currentPage);
                }
                else
                {
                    $('#hasil_tabel').hide();
                    $('.spinner-back-placeholder').hide();
                }
            },
            error: function() {
                console.log('gagal load data');
            },
        });
    }
    function saveInput()
    { console.log("save Input");
      var nama_kolom_id=[];
      var kolom=[];
      $('input[name="kolom[]"]').each(function() {
        kolom.push($(this).val());
      });
      $('input[name="id_baris[]"]').each(function() {
        nama_kolom_id.push($(this).val());
      });


      console.log(nama_kolom_id,kolom);
      var data={
                   _token: '{!! csrf_token() !!}',
                   nama_kolom_id:nama_kolom_id,
                   kolom    : kolom,
                };
      $.ajax({
                type: 'POST',
                // url: '/dashboardDinamis/kolomstore/ajax',
                url: "{{ url('/dashboardDinamis/kolomstore/ajax') }}",
                data: data,
                success: function(data)
                {
                  $('table tr').each(function() {
                    $(this).find('td').each(function() {
                        $(this).find('input').attr("readonly", true);
                    });
                  });
                      const toast = swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500
                      });

                      toast({
                        type: 'success',
                        title: 'Setting Dashboard Dinamis Berhasil Disimpan'
                      });
                },
                error: function(error)
                {
                  console.log(error.status);
                }
            });
    }
    function loadPagination(currentPage = 1)
    {

        $('#pagination').twbsPagination('destroy');
        $('#pagination').twbsPagination({
            totalPages: total_pages,
            startPage: currentPage,
            visiblePages: visible_pages,
            initiateStartPageClick: false,
            onPageClick: function (event, page) {
              event.preventDefault();
              saveInput();    
              loadData(page);

            }
        });
        firstLoadPagination = true;
    }
    
    function loadMustache(template) {
        Mustache.parse(template, customTags);
        Mustache.tags = customTags;
    }
      
  })
</script>
@endsection