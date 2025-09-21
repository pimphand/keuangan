@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Role Management</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Role</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <a href="{{ route('role') }}" class="btn btn-primary float-right"><i class="fa fa-arrow-left"></i> &nbsp
                        KEMBALI</a>
                    <h4>Tambah Role Baru</h4>

                </div>
                <div class="card-body pt-0">

                    <div class="row">

                        <div class="col-lg-8">

                            <form method="POST" action="{{ route('role.store') }}">
                                @csrf

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Nama Role</label>
                                        <input id="name" type="text" placeholder="Nama role (contoh: admin, manager)"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name') }}" autocomplete="off">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Display Name</label>
                                        <input id="display_name" type="text" placeholder="Nama yang ditampilkan"
                                            class="form-control @error('display_name') is-invalid @enderror"
                                            name="display_name" value="{{ old('display_name') }}" autocomplete="off">
                                        @error('display_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Deskripsi</label>
                                        <textarea id="description" placeholder="Deskripsi role"
                                            class="form-control @error('description') is-invalid @enderror"
                                            name="description" rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Permissions</label>

                                        <!-- Select All Buttons -->
                                        <div class="mb-3">
                                            <button type="button" class="btn btn-success btn-sm" id="selectAllPermissions">
                                                <i class="fa fa-check-square"></i> Pilih Semua
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm"
                                                id="deselectAllPermissions">
                                                <i class="fa fa-square"></i> Batal Pilih Semua
                                            </button>
                                            <span class="badge badge-info ml-2" id="permissionCounter">
                                                0 dari 0 permission dipilih
                                            </span>
                                        </div>

                                        <div class="row">
                                            @foreach($permissions as $group => $groupPermissions)
                                                <div class="col-md-6 mb-3">
                                                    <div class="card">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                                                            <div>
                                                                <button type="button"
                                                                    class="btn btn-outline-primary btn-xs select-group"
                                                                    data-group="{{ $group }}">
                                                                    <i class="fa fa-check"></i> Pilih Grup
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary btn-xs deselect-group"
                                                                    data-group="{{ $group }}">
                                                                    <i class="fa fa-times"></i> Batal
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            @foreach($groupPermissions as $permission)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permissions[]" value="{{ $permission->id }}"
                                                                        id="permission_{{ $permission->id }}" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="permission_{{ $permission->id }}">
                                                                        {{ $permission->display_name }}
                                                                    </label>
                                                                    @if($permission->description)
                                                                        <small
                                                                            class="text-muted d-block">{{ $permission->description }}</small>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @error('permissions')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Simpan Role</button>
                                    <a href="{{ route('role') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

@endsection

<script>
    // Simple and reliable script for permission selection
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Permission selection script loaded');

        // Function to update button states
        function updateButtonStates() {
            var totalChecked = document.querySelectorAll('input[name="permissions[]"]:checked').length;
            var totalPermissions = document.querySelectorAll('input[name="permissions[]"]').length;

            // Update counter
            var counter = document.getElementById('permissionCounter');
            if (counter) {
                counter.textContent = totalChecked + ' dari ' + totalPermissions + ' permission dipilih';
            }

            // Update main buttons
            var selectAllBtn = document.getElementById('selectAllPermissions');
            var deselectAllBtn = document.getElementById('deselectAllPermissions');

            if (totalChecked === 0) {
                if (selectAllBtn) {
                    selectAllBtn.className = 'btn btn-success btn-sm';
                }
                if (deselectAllBtn) {
                    deselectAllBtn.className = 'btn btn-outline-warning btn-sm';
                }
            } else if (totalChecked === totalPermissions) {
                if (selectAllBtn) {
                    selectAllBtn.className = 'btn btn-outline-success btn-sm';
                }
                if (deselectAllBtn) {
                    deselectAllBtn.className = 'btn btn-warning btn-sm';
                }
            } else {
                if (selectAllBtn) {
                    selectAllBtn.className = 'btn btn-outline-success btn-sm';
                }
                if (deselectAllBtn) {
                    deselectAllBtn.className = 'btn btn-outline-warning btn-sm';
                }
            }

            // Update group buttons
            document.querySelectorAll('.card').forEach(function (card) {
                var checkboxes = card.querySelectorAll('input[name="permissions[]"]');
                var checkedCount = card.querySelectorAll('input[name="permissions[]"]:checked').length;
                var totalCount = checkboxes.length;

                var selectBtn = card.querySelector('.select-group');
                var deselectBtn = card.querySelector('.deselect-group');

                if (checkedCount === 0) {
                    if (selectBtn) {
                        selectBtn.className = 'btn btn-primary btn-xs select-group';
                    }
                    if (deselectBtn) {
                        deselectBtn.className = 'btn btn-outline-secondary btn-xs deselect-group';
                    }
                } else if (checkedCount === totalCount) {
                    if (selectBtn) {
                        selectBtn.className = 'btn btn-outline-primary btn-xs select-group';
                    }
                    if (deselectBtn) {
                        deselectBtn.className = 'btn btn-secondary btn-xs deselect-group';
                    }
                } else {
                    if (selectBtn) {
                        selectBtn.className = 'btn btn-outline-primary btn-xs select-group';
                    }
                    if (deselectBtn) {
                        deselectBtn.className = 'btn btn-outline-secondary btn-xs deselect-group';
                    }
                }
            });
        }

        // Select all permissions
        var selectAllBtn = document.getElementById('selectAllPermissions');
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function () {
                console.log('Select all clicked');
                document.querySelectorAll('input[name="permissions[]"]').forEach(function (checkbox) {
                    checkbox.checked = true;
                });
                updateButtonStates();
            });
        }

        // Deselect all permissions
        var deselectAllBtn = document.getElementById('deselectAllPermissions');
        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function () {
                console.log('Deselect all clicked');
                document.querySelectorAll('input[name="permissions[]"]').forEach(function (checkbox) {
                    checkbox.checked = false;
                });
                updateButtonStates();
            });
        }

        // Select group permissions
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('select-group')) {
                console.log('Select group clicked');
                var card = e.target.closest('.card');
                if (card) {
                    card.querySelectorAll('input[name="permissions[]"]').forEach(function (checkbox) {
                        checkbox.checked = true;
                    });
                    updateButtonStates();
                }
            }
        });

        // Deselect group permissions
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('deselect-group')) {
                console.log('Deselect group clicked');
                var card = e.target.closest('.card');
                if (card) {
                    card.querySelectorAll('input[name="permissions[]"]').forEach(function (checkbox) {
                        checkbox.checked = false;
                    });
                    updateButtonStates();
                }
            }
        });

        // Update when checkboxes change
        document.addEventListener('change', function (e) {
            if (e.target.name === 'permissions[]') {
                console.log('Checkbox changed');
                updateButtonStates();
            }
        });

        // Initial update
        updateButtonStates();
    });
</script>