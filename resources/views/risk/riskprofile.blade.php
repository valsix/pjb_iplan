@extends('layouts.app')

@section('css_page')

    <!-- searching -->
    <script src="{{ asset('js/jquery-1.11.2.min.js') }}" type="text/javascript"></script>

    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
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

    <script type="text/javascript">
        $('#datatable').dataTable( {
            "searching": false
        } );
    </script>
@endsection

@section('content')
    <div role="main">
          <div class="row">
            <div class="page-title">
              <div>
                <h3> Daftar Risk Profile </h3>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session('success') }}
                    </div>
                @endif
              </div>

          <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                     Pencarian
                </div>
                <div class="panel-default"> 
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <form>
                                <div class="col-md-2"><label> Risk Tag</label></div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="">
                                </div>

                                <div class="col-md-2"><label> Risk Event</label></div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="">
                                </div>
                               
                                <br>
                                <br>
                                <div class="col-md-2"><label> Risk Korporat</label></div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="">
                                </div>

                                <div class="col-md-2"><label> Tingkat Kemungkinan</label></div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="">
                                </div>

                                <br>
                                <br>
                                <div class="col-md-2"><label> Tingkat Dampak </label></div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="">
                                </div>

                                <div class="col-md-2"><label> Nama Unit </label></div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="">
                                </div>
                                 <br>
                                <br>

                                <div class="col-md-2"><label> Struktur Bisnis</label></div>
                                <div class="col-md-4">
                                   <select class="form-control" name="strategi_bisnis">
                                       <option></option>
                                       @foreach ($Sb as $sbs => $value)
                                         <option value="{{ $value->id }}"> {{ $value->name }} </option>
                                       @endforeach
                                    </select>
                                </div>
                                <br>
                                <br>

                                <div class="col-md-2"><label> Distrik </label></div>
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
                                                    url: '/risk_profile/ajax/'+strategi_bisnisID,
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

                                <div class="col-md-2"><label> Lokasi </label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="lokasi">
                                      
                                    </select>
                                </div>

                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('select[name="distrik"]').on('change', function() {
                                            var lokasiID = $(this).val();
                                            $('select[name="lokasi"]').empty();

                                            if(lokasiID) {
                                                $.ajax({
                                                    url: '/risk_profile/ajax2/'+lokasiID,
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
          </div>

              <div> 
                <a href="tambah_risk_profile" class="btn btn-primary"> Tambah Risk Profile </a> 
              </div>

              <br>
                <div class="x_content">
                  <table id="datatable" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>No.</th>
                          <th>Lokasi</th>
                          <th>Risk Tag</th>
                          <th>Risk Event</th>
                          <th>Risiko Korporat</th>
                          <th>Tingat Kemungkinan</th>
                          <th>Tingkat Dampak</th>
                          <th>Level Resiko</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $i=1; ?>
                    @foreach ( $riskprofile as $item)
                        <tr>
                          <td>{{ $i++ }}</td>
                          <td>{{ $item->lokasi->name }}</td>
                          <td>{{ $item->risk_tag }}</td>
                          <td>{{ $item->risk_event }}</td>
                          <td>{{ $item->risk_corporate }}</td>
                          <td>{{ $item->possibility_level }}</td>
                          <td>{{ $item->impact_level }}</td>
                          <td>{{ $item->risk_level }}</td>
                          <td> 
                          <a href="detail_unit.html"><button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-eye-open"></span></button></a>

                          <a href="update_risk_profile/{{ $item['id'] }}"><button type="button" class="btn btn-success"><span class="glyphicon glyphicon-edit"></span></button></a>

                          <a href="delete_risk_profile/{{ $item['id'] }}" class="btn btn-danger" data-toggle="tooltip" title="hapus" onclick="return konfirmasi()"> <span class="glyphicon glyphicon-trash"></span> </a>
                          </td>
                        </tr>
                        <script type="text/javascript" language="JavaScript">
                          function konfirmasi()
                          {
                            tanya = confirm("Anda Yakin Akan Menghapus Data?");
                            if (tanya == true) 
                              return true;
                            else return false;
                          }
                        </script>
                    @endforeach
                        </tbody>
                        </table>
                </div>
            </div>
          </div>
        </div>
@endsection