@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Kategori</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Kategori</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">

                <div class="card-header pt-4">
                    <div class="float-right">
                        <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#importModal">
                            <i class="fa fa-upload"></i> &nbsp IMPORT EXCEL
                        </button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa fa-plus"></i> &nbsp TAMBAH KATEGORI
                        </button>
                    </div>
                    <h4>Data Kategori</h4>

                </div>
                <div class="card-body pt-0">

                    <!-- Modal -->
                    <form action="{{ route('kategori.aksi') }}" method="post">
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        @csrf
                                        <div class="form-group">
                                            <label>Nama Kategori</label>
                                            <input type="text" name="nama" required="required" class="form-control"
                                                placeholder="Nama Kategori ..">
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                                class="ti-close m-r-5 f-s-12"></i> Tutup</button>
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa fa-paper-plane m-r-5"></i> Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Import Modal -->
                    <form action="{{ route('kategori.import') }}" method="post" enctype="multipart/form-data">
                        <div class="modal fade" id="importModal" tabindex="-1" role="dialog"
                            aria-labelledby="importModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importModalLabel">Import Kategori dari Excel</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @csrf
                                        <div class="form-group">
                                            <label>Pilih File Excel</label>
                                            <input type="file" name="file" required="required" class="form-control"
                                                accept=".xlsx,.xls,.csv">
                                            <small class="form-text text-muted">
                                                Format file: .xlsx, .xls, atau .csv<br>
                                                Kolom header harus: <strong>nama_kategori</strong>
                                            </small>
                                            <div class="mt-2">
                                                <a href="{{ route('kategori.template') }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="fa fa-download"></i> Download Template Excel
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                            <i class="ti-close m-r-5 f-s-12"></i> Tutup
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-upload m-r-5"></i> Import
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">


                        <table class="table table-bordered" id="table-datatable">
                            <thead>
                                <tr>
                                    <th width="1%">NO</th>
                                    <th>NAMA KATEGORI</th>
                                    <th class="text-center" width="10%">OPSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                  @endphp
                                @foreach($kategori as $k)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $k->kategori }}</td>
                                        <td>

                                            @if($k->id != 1)
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal"
                                                        data-target="#edit_kategori_{{ $k->id }}">
                                                        <i class="fa fa-cog"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#hapus_kategori_{{ $k->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endif

                                            <form action="{{ route('kategori.update', ['id' => $k->id]) }}" method="post">
                                                <div class="modal fade" id="edit_kategori_{{$k->id}}" tabindex="-1"
                                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Edit Kategori
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                @csrf
                                                                {{ method_field('PUT') }}

                                                                <div class="form-group" style="width:100%">
                                                                    <label>Nama Kategori</label>
                                                                    <input type="hidden" name="id" value="{{ $k->id }}">
                                                                    <input type="text" name="nama" required="required"
                                                                        class="form-control" placeholder="Nama Kategori .."
                                                                        value="{{ $k->kategori }}" style="width:100%">
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal"><i class="ti-close m-r-5 f-s-12"></i>
                                                                    Tutup</button>
                                                                <button type="submit" class="btn btn-primary"><i
                                                                        class="fa fa-paper-plane m-r-5"></i> Simpan</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <!-- modal hapus -->
                                            <form method="POST" action="{{ route('kategori.delete', ['id' => $k->id]) }}">
                                                <div class="modal fade" id="hapus_kategori_{{$k->id}}" tabindex="-1"
                                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Peringatan!</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <p>Yakin ingin menghapus data ini ?</p>

                                                                @csrf
                                                                {{ method_field('DELETE') }}


                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal"><i class="ti-close m-r-5 f-s-12"></i>
                                                                    Batal</button>
                                                                <button type="submit" class="btn btn-primary"><i
                                                                        class="fa fa-paper-plane m-r-5"></i> Ya, Hapus</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>


                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

@endsection
