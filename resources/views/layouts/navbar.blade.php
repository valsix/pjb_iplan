<style type="text/css">
    .navbar.nav_title .logo-lg {
      -webkit-transition: width 0.3s ease-in-out;
      -o-transition: width 0.3s ease-in-out;
      transition: width 0.3s ease-in-out;
      display: block;
      float: left;
      height: 50px;
      font-size: 20px;
      line-height: 50px;
      margin-left: 40px;
      width: 230px;
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      padding: 0 15px;
      font-weight: 300;
      overflow: hidden;
    }
</style>
<div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
        <a href="{{ route('home') }}" class="site_title">
            <span class="logo-lg">
                <img src="{{asset('images/logopjb.png')}}" width="90px">
            </span>
             <img src="{{asset('images/logopjb.png')}}" width="50px">
        </a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix" style="color: white; text-align: center;">
        <!-- <div class="profile_pic"><br>
        </div>
        <div class="profile_info">
        </div> -->
        <h3>IPLAN</h3>
        <h5>(Integrated Planning)</h5>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        @if (true)
            <div class="menu_section">
                <ul class="nav side-menu">
                    <h3>Perencanaan</h3>
                    <!-- <li><a href="{{ route('home') }}"><i class="fa fa-home"></i>Dashboard</a> -->
                    <li><a><i class="fa fa-bar-chart"></i> Dashboard Perencanaan<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('output/pencarian') }}">Dashboard Perencanaan</a></li>
                            @if($role->is_kantor_pusat)
                            <li><a href="{{ route('output/summary') }}">Summary Form</a></li>
                            @endif
                            <!-- <li><a href="{{ route('output/risk-profile') }}">1.1 Risk Profile</a></li>
                            <li><a href="{{ route('output/mitigasi-risiko') }}">1.2 Mitigasi Resiko</a></li>
                            <li><a href="{{ route('output/rencana-kinerja') }}">2.0 Rencana Kinerja</a></li>
                            <li><a href="{{ route('output/program-strategis') }}">3.0 Program Strategis</a></li>
                            <li><a href="{{ route('output/laba-rugi') }}">4.0 Laba Rugi</a></li>
                            <li><a href="{{ route('output/biaya-pemeliharaan') }}">5.0 Biaya Pemeliharaan</a></li>
                            <li><a href="{{ route('output/status-dmr') }}">6.0 Status DMR</a></li>
                            <li><a href="{{ route('output/rincian-biaya-har') }}">7.0 Rincian Biaya HAR</a></li>
                            <li><a href="{{ route('output/rincian-biaya-har-reimburse') }}">8.0 Rincian Biaya HAR Reimburse</a></li>
                            <li><a href="{{ route('output/rincian-penetapan-ai') }}">9.1 Rincian Penetapan AI</a></li>
                            <li><a href="{{ route('output/rincian-pengembangan-usaha') }}">9.2 Rincian AI Pengembangan Usaha</a></li>
                            <li><a href="{{ route('output/rincian-penetapan-pln') }}">9.3 Rincian AI Penetapan PLN</a></li>
                            <li><a href="{{ route('output/rincian-biaya-pegawai') }}">10 Rincian Biaya Pegawai</a></li>
                            <li><a href="{{ route('output/rincian-biaya-administrasi') }}">11 Rincian Biaya Administrasi</a></li>
                            <li><a href="{{ route('output/rincian-energi-primer') }}">12 Rincian Energi Primer</a></li>
                            <li><a href="{{ route('output/form-luar-operasi') }}">13 Form Luar Operasi</a></li>
                            <li><a href="{{ route('output/loader-ellipse') }}">14 Loader Ellipse</a></li>
                            <li><a href="{{ route('output/list-prk') }}">15 List PRK</a></li> -->
                        </ul>
                    </li>
                    <!-- SEMENTARA dari permintaan Pak Hisyam, 27 Des 17 supaya Unit tidak upload lagi -->
                    {{-- @if($role->is_kantor_pusat) --}}
                    <li><a><i class="fa fa-upload"></i> Upload Form Perencanaan<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @foreach($nav_jenis as $row)
                                <li><a href="{{ route('template.index', $row->id) }}">{{ $row->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    {{-- @endif --}}
                    <li><a><i class="fa fa-check-square-o"></i> Approval Form<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="{{ url('assignment') }}">Assigment</a></li>
                            {{-- @foreach($nav_jenis as $row)
                                <li><a href="{{ route('approval.daftar', $row->id) }}">{{ $row->name }}</a></li>
                            @endforeach --}}
                        </ul>
                    </li>
                    <li><a><i class="fa fa-file-text"></i> KKP<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('kkp.daftar') }}">Daftar KKP</a></li>
                            <li><a href="{{ route('kkp.summary') }}">Summary KKP</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-file-text"></i> DMR<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('dmr.daftar') }}">Daftar DMR</a></li>
                            <li><a href="{{ route('dmr.summary') }}">Summary DMR</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-check-square"></i> Approval DMR & KKP<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('approval_dmr.daftar') }}">Approval DMR</a></li>
                            <li><a href="{{ route('approval_dmr.publish') }}">DMR Publish</a></li>
                            <li><a href="{{ route('approval_kkp.daftar') }}">Approval KKP</a></li>
                            <li><a href="{{ route('approval_kkp.publish') }}">KKP Publish</a></li>
                        </ul>
                    </li>

                    <li><a><i class="fa fa-file-text"></i> TOR<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('tor.daftar') }}">Daftar TOR</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-check-square"></i> Approval TOR<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('approval_tor.daftar') }}">Approval TOR</a></li>
                            <li><a href="{{ route('tor_published.daftar') }}">TOR Publish</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="menu_section">
                <ul class="nav side-menu">
                    <h3>Pengendalian</h3>
                    <!-- <li><a href="{{ route('home') }}"><i class="fa fa-home"></i>Dashboard</a> -->
                    <li><a><i class="fa fa-line-chart"></i> Dashboard Pengendalian<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">

                            <li><a href="{{ route('output/pencarian-pengendalian') }}">Dashboard Pengendalian</a></li>
                            <!-- <li><a href="{{-- route('output/monitoring-prk-ao') --}}">1. Monitoring PRK AO</a></li>
                            <li><a href="{{-- route('output/monitoring-prk-ai') --}}">2. Monitoring PRK AI</a></li> -->
                        </ul>
                    </li>
                    <!-- SEMENTARA dari permintaan Pak Hisyam, 27 Des 17 supaya Unit tidak upload lagi -->
                    @if($role->is_kantor_pusat)
                    <li><a><i class="fa fa-cloud-upload"></i> Upload Form Pengendalian<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @foreach($nav_jenis_pengendalian as $row)
                                <li><a href="{{ route('templatepengendalian.index', $row->id) }}">{{ $row->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li><a><i class="fa fa-book"></i> Form Input Pengendalian<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('pgdl.input.realisasiproduksipenjualan') }}">Input Produksi & Penjualan</a>
                            <li><a href="{{ route('pgdl.input.statusaipjb') }}">Input Status AI PJB</a>
                            <li><a href="{{ route('pgdl.input.reportdashboard') }}">Input Report & Dashboard</a>
                            <li><a href="{{ route('pgdl.input.kodeparentLR') }}">Input Kode Parent & Pos Laba Rugi</a>
							@if(session('role_id') == ROLE_ID_STAFF_ANGGARAN OR session('role_id') == ROLE_ID_ADMIN)
							<li><a href="{{ route('form_bahan_bakar.index') }}"> Form Input Bahan Bakar</a>
							@endif
                        </ul>
                    </li> 
                    @endif
                </ul>
            </div>
            <div class="menu_section">
                <ul class="nav side-menu">
                    <h3>Master</h3>
                    <!-- Administrator || Staff Anggaran || Manager Anggaran -->
                    @if($role->id == 1 || $role->id == 5 || $role->id == 6)
                    <li><a><i class="fa fa-database"></i> Master<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.role.list') }}">Kelola Grup</a>
                            <li><a href="{{ route('admin.permission.list') }}">Kelola Hak Akses</a>
                            <li><a href="{{ route('admin.user.list') }}">Kelola User</a>
                            <li><a href="{{ route('strategi_bisnis.daftar') }}">Strategi Bisnis</a>
                            <li><a href="{{ route('distrik.daftar') }}">Distrik</a>
                            <li><a href="{{ route('lokasi/daftar') }}">Lokasi</a>
                            <li><a href="{{ route('entitas/daftar') }}">Entitas</a>
                            <li><a href="{{ route('unit/daftar') }}">Unit</a>
                            <li><a href="{{ route('prk_parent/daftar') }}">PRK Parent</a>
                            <li><a href="{{ route('prkinti/daftar') }}">PRK Inti</a>
                            <li><a href="{{ route('masterapproval/daftar') }}">Approval</a>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>

            <!-- Administrator || Staff Anggaran || Manager Anggaran -->
            @if($role->id == 1 || $role->id == 5 || $role->id == 6)
            <div class="menu_section">
                <ul class="nav side-menu">
                    <h3>Master KKP</h3>
                    
                    <li><a><i class="fa fa-database"></i> Master KKP<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">

                            <li><a href="{{ route('kondisi_aicluster.daftar') }}">Kondisi AI Cluster</a>
                            <li><a href="{{ route('admin.grupdiv.list') }}">Group Divisi Pembina Unit</a>
                            <li><a href="{{ route('jabatan.daftar') }}">Jabatan</a>
                            <li><a href="{{ route('bidang_divisi.daftar') }}">Bagian</a>
                            <li><a href="{{ route('admin.userinternal.list') }}">Kelola User Internal </a>
                            <li><a href="{{ route('status_appr.daftar') }}">Status Approval KKP</a>
                            <li><a href="{{ route('jenis_pembangkit.daftar') }}">Jenis Pembangkit</a>
                        </ul>
                    </li>
                    
                </ul>
            </div>
            @endif
        @endif
    </div>

</div>
<script type="text/javascript">
    $(document).ready(function(e){
        setTimeout(function(){
          $('.active').removeClass('active');//remove class active
          $('.current-page').removeClass('current-page');//remove class current-page
          $('.nav .child_menu').css("display", "none");
        },1000);
    });
</script>
