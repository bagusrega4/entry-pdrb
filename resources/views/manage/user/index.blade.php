@extends('layouts/app')

@section('content')

<style>
    * { box-sizing: border-box; }

    body { background: #f7f6f3; }

    .page {
        padding: 24px 24px 48px;
        padding-top: 80px;
        font-family: 'Figtree', sans-serif;
    }

    .card {
        background: #fff;
        border: 0.5px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 0.5px solid #e5e7eb;
        background: #fafafa;
        gap: 8px;
    }
    .card-head-left { display: flex; align-items: center; gap: 8px; }
    .card-head-icon { color: #9ca3af; }
    .card-head-title { font-size: 14px; font-weight: 500; color: #111827; }

    .card-head-badge {
        font-size: 11px;
        padding: 2px 8px;
        background: #f3f4f6;
        border: 0.5px solid #e5e7eb;
        border-radius: 100px;
        color: #6b7280;
    }

    /* Tombol tambah */
    .btn-tambah {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 36px;
        padding: 0 16px;
        font-size: 13px;
        font-weight: 500;
        color: #fff;
        background: #f97316; 
        border: 0.5px solid #ea580c;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Figtree', sans-serif;
        text-decoration: none;
        transition: background 0.12s;
    }
    .btn-tambah:hover { background: #ea580c; color: #fff; }

    /* Table */
    .table-wrap { overflow-x: auto; }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 700px;
    }

    .user-table thead th {
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        padding: 10px 14px;
        text-align: center;
        border-bottom: 0.5px solid #e5e7eb;
        background: #f9fafb;
        white-space: nowrap;
    }
    .user-table thead th:first-child { width: 40px; }
    .user-table thead th.th-left { text-align: left; }

    .user-table tbody tr:hover td { background: #f9fafb; }
    .user-table tbody tr:not(:last-child) td { border-bottom: 0.5px solid #f0f0f0; }

    .user-table tbody td {
        padding: 10px 14px;
        text-align: center;
        vertical-align: middle;
        color: #374151;
        font-size: 13px;
    }
    .user-table tbody td.td-no { color: #9ca3af; width: 40px; }
    .user-table tbody td.td-left { text-align: left; color: #111827; font-weight: 500; }

    /* Role badge */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 500;
        padding: 3px 10px;
        border-radius: 100px;
        cursor: pointer;
        border: none;
        font-family: 'Figtree', sans-serif;
    }
    .role-admin    { background: #fef9c3; color: #854d0e; border: 0.5px solid #fde68a; }
    .role-keuangan { background: #f3f4f6; color: #374151; border: 0.5px solid #e5e7eb; }
    .role-other    { background: #eff6ff; color: #1e40af; border: 0.5px solid #bfdbfe; }

    /* Dropdown */
    .dropdown-menu-custom {
        position: absolute;
        z-index: 1000;
        background: #fff;
        border: 0.5px solid #e5e7eb;
        border-radius: 8px;
        padding: 4px 0;
        min-width: 140px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        display: none;
    }
    .dropdown-menu-custom.show { display: block; }
    .dropdown-menu-custom button {
        display: block;
        width: 100%;
        padding: 8px 14px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        color: #374151;
        background: none;
        border: none;
        text-align: left;
        cursor: pointer;
    }
    .dropdown-menu-custom button:hover { background: #f9fafb; }

    /* Tim badge */
    .tim-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 500;
        padding: 3px 10px;
        border-radius: 100px;
        cursor: pointer;
        background: #f0fdf4;
        color: #166534;
        border: 0.5px solid #86efac;
        font-family: 'Figtree', sans-serif;
    }
    .tim-badge-empty {
        background: #f3f4f6;
        color: #9ca3af;
        border: 0.5px solid #e5e7eb;
    }

    /* Action buttons */
    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 6px;
        border: 0.5px solid;
        cursor: pointer;
        background: none;
        transition: background 0.12s;
        text-decoration: none;
    }
    .btn-icon-edit  { color: #f97316; border-color: #fed7aa; background: #fff7ed; }
    .btn-icon-edit:hover  { background: #ffedd5; }
    .btn-icon-hapus { color: #991b1b; border-color: #fca5a5; background: #fef2f2; }
    .btn-icon-hapus:hover { background: #fee2e2; }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.35);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.show { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 12px;
        border: 0.5px solid #e5e7eb;
        padding: 24px;
        width: 100%;
        max-width: 420px;
        font-family: 'Figtree', sans-serif;
    }
    .modal-box-title {
        font-size: 15px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 8px;
    }
    .modal-box-body {
        font-size: 13px;
        color: #374151;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    .modal-box-footer {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }
    .btn-batal {
        height: 36px;
        padding: 0 16px;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        background: #f3f4f6;
        border: 0.5px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Figtree', sans-serif;
    }
    .btn-batal:hover { background: #e5e7eb; }
    .btn-simpan {
        height: 36px;
        padding: 0 16px;
        font-size: 13px;
        font-weight: 500;
        color: #fff;
        background: #f97316; 
        border: 0.5px solid #ea580c;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Figtree', sans-serif;
    }
    .btn-simpan:hover { background: #ea580c; }
    .btn-hapus-confirm {
        height: 36px;
        padding: 0 16px;
        font-size: 13px;
        font-weight: 500;
        color: #fff;
        background: #dc2626;
        border: 0.5px solid #b91c1c;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Figtree', sans-serif;
    }
    .btn-hapus-confirm:hover { background: #b91c1c; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="page">

    <h3 class="fw-bold mb-2">Kelola Pengguna</h3>
    <div class="mb-3">
        <h5 style="font-size:14px; color:#6b7280; font-weight:400; margin:0;">Pengelolaan daftar pengguna SI-PRABU.</h5>
    </div>

    <!-- Tabel Pengguna -->
    <div class="card" style="margin-bottom:0;">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span class="card-head-title">Daftar Pengguna</span>
                <span class="card-head-badge" id="userCount">{{ count($users) }} pengguna</span>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="position:relative;">
                    <svg style="position:absolute; left:9px; top:50%; transform:translateY(-50%); color:#9ca3af;" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari username..."
                        style="height:36px; padding:0 10px 0 30px; font-size:13px; font-family:'Figtree',sans-serif;
                            color:#374151; border:0.5px solid #d1d5db; border-radius:8px; outline:none;
                            width:200px; transition:border-color 0.15s;"
                        onfocus="this.style.borderColor='#f97316'"
                        onblur="this.style.borderColor='#d1d5db'">
                </div>
            </div>
        </div>

        <div class="table-wrap">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="th-left">Username</th>
                        <th class="th-left">NIP Lama</th>
                        <th class="th-left">Email</th>
                        <th>Role</th>
                        <th>Tim</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="td-no">{{ $loop->iteration }}</td>
                        <td class="td-left">{{ $user->username }}</td>
                        <td class="td-left" style="font-weight:400; color:#6b7280; font-family:monospace; font-size:12.5px;">{{ $user->nip_lama }}</td>
                        <td class="td-left" style="font-weight:400;">{{ $user->email }}</td>

                        <!-- Role dropdown -->
                        <td>
                            <div style="position:relative; display:inline-block;">
                                <button
                                    class="role-badge {{ $user->role->role == 'admin' ? 'role-admin' : ($user->role->role == 'keuangan' ? 'role-keuangan' : 'role-other') }} dropdown-role-toggle"
                                    data-user="{{ $user->id }}">
                                    {{ ucfirst($user->role->role) }}
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <div class="dropdown-menu-custom" id="role-menu-{{ $user->id }}">
                                    @foreach($roles as $role)
                                        @if($role->role != $user->role->role)
                                        <button
                                            onclick="openRoleModal({{ $user->id }}, {{ $role->id }}, '{{ $role->role }}', '{{ $user->username }}')"
                                            type="button">
                                            {{ ucfirst($role->role) }}
                                        </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </td>

                        <!-- Tim dropdown -->
                        <td>
                            @if($user->id_role == 2 || $user->id_role == 3)
                            <div style="position:relative; display:inline-block;">
                                <button
                                    class="tim-badge {{ $user->tim ? '' : 'tim-badge-empty' }} dropdown-tim-toggle"
                                    data-user="{{ $user->id }}">
                                    {{ $user->tim ? $user->tim->nama_tim : 'Pilih Tim' }}
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <div class="dropdown-menu-custom" id="tim-menu-{{ $user->id }}">
                                    @foreach($timList as $tim)
                                        @if(!$user->tim || $user->tim->id !== $tim->id)
                                        <button
                                            onclick="openTimModal({{ $user->id }}, {{ $tim->id }}, '{{ $tim->nama_tim }}', '{{ $user->username }}')"
                                            type="button">
                                            {{ $tim->nama_tim }}
                                        </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @else
                                <span style="color:#d1d5db;">—</span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td>
                            <div style="display:flex; align-items:center; justify-content:center; gap:6px;">
                                <a href="{{ route('manage.user.edit', $user->id) }}" class="btn-icon btn-icon-edit">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>
                                <button type="button" class="btn-icon btn-icon-hapus" onclick="openHapusModal({{ $user->id }}, '{{ $user->username }}')">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                        <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Ubah Role -->
<div class="modal-overlay" id="modalRole">
    <div class="modal-box">
        <div class="modal-box-title">Ubah Role Pengguna</div>
        <div class="modal-box-body" id="modalRoleBody"></div>
        <div class="modal-box-footer">
            <button class="btn-batal" onclick="closeModal('modalRole')">Batal</button>
            <form id="formRole" method="POST" style="margin:0;">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_role" id="inputRoleId">
                <button type="submit" class="btn-simpan">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ubah Tim -->
<div class="modal-overlay" id="modalTim">
    <div class="modal-box">
        <div class="modal-box-title">Ubah Tim Pengguna</div>
        <div class="modal-box-body" id="modalTimBody"></div>
        <div class="modal-box-footer">
            <button class="btn-batal" onclick="closeModal('modalTim')">Batal</button>
            <form id="formTim" method="POST" style="margin:0;">
                @csrf
                @method('PUT')
                <input type="hidden" name="tim_id" id="inputTimId">
                <button type="submit" class="btn-simpan">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal-overlay" id="modalHapus">
    <div class="modal-box">
        <div class="modal-box-title">Hapus Pengguna</div>
        <div class="modal-box-body" id="modalHapusBody"></div>
        <div class="modal-box-footer">
            <button class="btn-batal" onclick="closeModal('modalHapus')">Batal</button>
            <form id="formHapus" method="POST" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-hapus-confirm">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('success')),
        timer: 2200,
        showConfirmButton: false
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: @json(session('error')),
        confirmButtonColor: '#f97316',
        confirmButtonText: 'OK'
    });
    @endif

    // Dropdown role toggle
    document.querySelectorAll('.dropdown-role-toggle').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const id = this.dataset.user;
            const menu = document.getElementById('role-menu-' + id);
            document.querySelectorAll('.dropdown-menu-custom').forEach(m => {
                if (m !== menu) m.classList.remove('show');
            });
            menu.classList.toggle('show');
        });
    });

    // Dropdown tim toggle
    document.querySelectorAll('.dropdown-tim-toggle').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const id = this.dataset.user;
            const menu = document.getElementById('tim-menu-' + id);
            document.querySelectorAll('.dropdown-menu-custom').forEach(m => {
                if (m !== menu) m.classList.remove('show');
            });
            menu.classList.toggle('show');
        });
    });

    // Close dropdown on outside click
    document.addEventListener('click', function () {
        document.querySelectorAll('.dropdown-menu-custom').forEach(m => m.classList.remove('show'));
    });
});

const updateRoleRoute = "{{ route('manage.user.updateRole', ':id') }}";
const updateTimRoute  = "{{ route('manage.user.updateTim', ':id') }}";
const destroyRoute    = "{{ route('manage.user.destroy', ':id') }}";

function openRoleModal(userId, roleId, roleName, username) {
    document.querySelectorAll('.dropdown-menu-custom').forEach(m => m.classList.remove('show'));
    document.getElementById('modalRoleBody').innerHTML =
        'Apakah Anda yakin ingin mengubah role <b>' + username + '</b> menjadi <b>' + roleName.charAt(0).toUpperCase() + roleName.slice(1) + '</b>?';
    document.getElementById('inputRoleId').value = roleId;
    document.getElementById('formRole').action = updateRoleRoute.replace(':id', userId);
    document.getElementById('modalRole').classList.add('show');
}

function openTimModal(userId, timId, timName, username) {
    document.querySelectorAll('.dropdown-menu-custom').forEach(m => m.classList.remove('show'));
    document.getElementById('modalTimBody').innerHTML =
        'Apakah Anda yakin ingin mengubah tim <b>' + username + '</b> menjadi <b>' + timName + '</b>?';
    document.getElementById('inputTimId').value = timId;
    document.getElementById('formTim').action = updateTimRoute.replace(':id', userId);
    document.getElementById('modalTim').classList.add('show');
}

function openHapusModal(userId, username) {
    document.getElementById('modalHapusBody').innerHTML =
        'Apakah Anda yakin ingin menghapus pengguna <b>' + username + '</b>? Tindakan ini tidak dapat dibatalkan.';
    document.getElementById('formHapus').action = destroyRoute.replace(':id', userId);
    document.getElementById('modalHapus').classList.add('show');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('show');
}

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('show');
    });
});

// Search filter
document.getElementById('searchInput').addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll('.user-table tbody tr');
    let visible = 0;

    rows.forEach(row => {
        const username = row.querySelector('.td-left')?.textContent.toLowerCase() || '';
        const match = username.includes(keyword);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    document.getElementById('userCount').textContent = visible + ' pengguna';
});
</script>
@endsection