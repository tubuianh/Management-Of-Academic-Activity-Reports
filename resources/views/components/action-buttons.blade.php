

 {{-- <div class="d-flex align-items-center justify-content-center">
    <!-- Nút Sửa -->
    @if (!empty($editRoute))
    <a style="color:#fff" href="{{ route($editRoute, $id) }}" class="btn btn-warning btn-sm me-2">
        <i class="fas fa-edit"></i> Sửa
    </a>
    @endif

    <!-- Nút Xóa -->
    <form id="deleteForm{{ $id }}" action="{{ route($deleteRoute, $id) }}" method="POST" >
        @csrf @method('DELETE')
        <button style="color:#fff" data-form="deleteForm{{ $id }}" style="align-self: center" class="btn btn-danger btn-sm btn-delete">
            <i class="fas fa-trash"></i> Xóa
        </button>
    </form>
</div> --}}

<div class="d-flex align-items-center justify-content-center gap-1">
    <!-- Nút Sửa -->
    @if (!empty($editRoute))
    <a href="{{ route($editRoute, $id) }}" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="min-width: 50px;">
        <i class="fas fa-edit me-1"></i> Sửa
    </a>
    @endif

    <!-- Nút Xóa -->
    @if (!empty($deleteRoute))
        <form id="deleteForm{{ $id }}" action="{{ route($deleteRoute, $id) }}" method="POST" class="m-0 p-0" style="display:inline-block;">
            @csrf @method('DELETE')
            <button type="submit" data-form="deleteForm{{ $id }}"
                class="btn btn-danger btn-sm d-flex align-items-center justify-content-center btn-delete"
                style="min-width: 50px;">
                <i class="fas fa-trash me-1"></i> Xóa
            </button>
        </form>
    @endif
    
</div>
{{-- xac nhan/ tu choi phieu/bien ban --}}
    @if (!empty($xacNhanRoute) && $row->trangThai == 'Chờ Xác Nhận')
        <form id="xacNhanForm{{ $id }}" action="{{ route($xacNhanRoute, $id) }}" method="POST">
        @csrf
            <button style="padding: 5px" type="submit" data-form="xacNhanForm{{ $id }}" class="btn btn-success btn-xacnhan">
                <i class="fas fa-check-circle me-1"></i>Xác nhận
            </button>
        </form>
    @endif
    @if (!empty($tuChoiRoute) && $row->trangThai == 'Chờ Xác Nhận')
        <form id="tuChoiForm{{ $id }}" action="{{ route($tuChoiRoute, $id) }}" method="POST">
        @csrf
            <button style="padding: 5px; margin-top: 5px;" type="submit" data-form="tuChoiForm{{ $id }}" class="btn btn-danger btn-tuchoi">
                <i class="fas fa-times-circle me-1"></i>Từ chối
            </button>
        </form>
    @endif

