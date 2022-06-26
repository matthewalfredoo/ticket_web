@extends('layouts.admin')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Tabel Orderan Masuk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Transaksi</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-100%">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tabel List Orderan</h3>
                        {{-- <button type="button" class="btn btn-info float-right" data-toggle="modal"
                            data-target="#exampleModal"><i class="fas fa-plus"></i> Add
                            item</button> --}}
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>ID User</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Tanggal</th>
                                    <th>Bukti Transfer</th>
                                    <th>Status</th>
                                    <th style="width: 40px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listTransaksi as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->user_id }}</td>
                                        <td>{{ $data->jumlah }}</td>
                                        <td>{{ "Rp. ".number_format($data->harga)}}</td>
                                        <td>{{ $data->tanggal }}</td>
                                        <td>
                                            <a href="{{ asset('storage/bukti_transfer/'.$data->bukti_transfer) }}"
                                                target="_blank">
                                                <img src="{{ asset('storage/bukti_transfer/'.$data->bukti_transfer) }}"
                                                    alt="{{ $data->bukti_transfer }}" width="100px">
                                            </a>
                                        </td>
                                        <td>{{ $data->updated_at }}</td>

                                        <td>
                                            <form method="POST" action="{{ route('transaksi.update', $data->id) }}">
                                                @csrf
                                                <select class="form-select form-select-sm" name="status"
                                                        onchange='if(this.value != 0) { this.form.submit(); }'>
                                                    <option value='0' disabled selected>Status</option>
                                                    <option value='Menunggu'>Menunggu Konfirmasi</option>
                                                    <option value='Berhasil'>Berhasil</option>
                                                    <option value='Ditolak'>Ditolak</option>
                                                </select>
                                            </form>
                                        </td>
                                @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Tiket</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form method="POST" action="{{ route('ticket.store') }}" role="form" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Tiket</label>
                                        <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Nama" name="name">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Harga</label>
                                                <input type="text" class="form-control" placeholder="Harga..." name="harga">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Pilih Kategori</label>
                                            <select class="form-control" name="category_id">
                                                <option value = "1">Tiket Masuk Weekdays</option>
                                                <option value = "2">Tiket Masuk Weekend</option>
                                                <option value = "3">Tiket Parkir</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea class="form-control" rows="3" placeholder="Deskripsi..." name="deskripsi"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Gambar</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="exampleInputFile" name="image">
                                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
