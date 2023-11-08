@extends('layouts.app')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="card mb-4">
      <div class="card-body">
        @if (!empty($alertMessage))
          <div class="col-lg-12 alert alert-{{ $alertType }}" role="alert">
            {{ $alertMessage }}
          </div>
        @endif
        <div class="d-flex justify-content-between">
          <div>
            <h3>Hay, <span class="text-primary">{{ $auth->name }}</span></h3>
          </div>
          <div>
            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-between">
      <div>
        <h3>Kelola Todolist Kamu</h3>
      </div>
      <div class="text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTodo">Tambah Data</button>
      </div>
    </div>
    <hr />

    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Aktivitas</th>
          <th scope="col">Status</th>
          <th scope="col">Tanggal Dibuat</th>
          <th scope="col">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        @if (isset($todos) && sizeof($todos) > 0)
            @php
                $counter = 1;
            @endphp
            @foreach ($todos as $todo)
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td>{{ $todo->activity }}</td>
                    <td>
                        @if ($todo->status)
                            <span class="badge bg-success">Selesai</span>
                        @else
                            <span class="badge bg-danger">Belum Selesai</span>
                        @endif
                    </td>
                    <td>
                        {{ date('d F Y - H:i', strtotime($todo->created_at)) }}
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="showModalEditTodo({{ $todo->id }}, '{{ $todo->activity }}', {{ $todo->status }})">
                        Ubah
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="showModalDeleteTodo({{ $todo->id }}, '{{ $todo->activity }}')">Hapus</button>
                    </td>
                </tr>
                @endforeach
            @else
            <tr>
            <td colspan="5" class="text-center text-muted">Belum ada data tersedia!</td>
            </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL ADD TODO -->
<div class="modal fade" id="addTodo" tabindex="-1" aria-labelledby="addTodoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTodoLabel">Tambah Data Todo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('post.todo.add') }}" method="POST" id="addTodoForm">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="inputActivity" class="form-label">Aktivitas</label>
            <input type="text" name="activity" class="form-control" id="inputActivity" placeholder="Contoh: Belajar membuat aplikasi website sederhana">
            <div id="activity-error" class="error-message"></div> <!-- Display activity error message -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="addTodoButton">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT TODO -->
<div class="modal fade" id="editTodo" tabindex="-1" aria-labelledby="editTodoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTodoLabel">Ubah Data Todo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('post.todo.edit') }}" method="POST" id="editTodoForm">
        @csrf
        <input name="id" type="hidden" id="inputEditTodoId">

        <div class="modal-body">
          <div class="mb-3">
            <label for="inputEditActivity" class="form-label">Aktivitas</label>
            <input type="text" name="activity" class="form-control" id="inputEditActivity" placeholder="Contoh: Belajar membuat aplikasi website sederhana">
            <div id="edit-activity-error" class="error-message"></div> <!-- Menampilkan pesan error aktivitas -->
          </div>

          <div class="mb-3">
            <label for="selectEditStatus" class="form-label">Status</label>
            <select class="form-select" name="status" id="selectEditStatus">
              <option value="0">Belum Selesai</option>
              <option value="1">Selesai</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="editTodoButton">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL DELETE TODO -->
<div class="modal fade" id="deleteTodo" tabindex="-1" aria-labelledby="deleteTodoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteTodoLabel">Hapus Data Todo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          Kamu akan menghapus todo
          <strong class="text-danger" id="deleteTodoActivity"></strong>.
          Apakah kamu yakin?
        </div>
      </div>
      <div class="modal-footer">
        <form action="{{ route('post.todo.delete') }}" method="POST">
          @csrf
          <input name="id" type="hidden" id="inputDeleteTodoId">

          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Ya, Tetap Hapus</button>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection

<!-- ... your HTML content above ... -->

@section('other-js')
<script>
  function showModalEditTodo(todoId, activity, status) {
    const modalEditTodo = document.getElementById("editTodo");
    const inputId = document.getElementById("inputEditTodoId");
    const inputActivity = document.getElementById("inputEditActivity");
    const selectStatus = document.getElementById("selectEditStatus");
    const editActivityError = document.getElementById("edit-activity-error");

    inputId.value = todoId;
    inputActivity.value = activity;
    selectStatus.value = status;
    editActivityError.innerText = ''; // Reset pesan kesalahan

    var myModal = new bootstrap.Modal(modalEditTodo);
    myModal.show();
  }

  document.getElementById("addTodoButton").addEventListener("click", function (e) {
    const inputActivity = document.getElementById("inputActivity");
    const activityError = document.getElementById("activity-error");

    if (!inputActivity.value.trim()) {
      e.preventDefault();
      activityError.innerText = 'Aktivitas harus diisi'; // Menampilkan pesan kesalahan
    } else {
      activityError.innerText = ''; // Reset pesan kesalahan
    }
  });

  document.getElementById("editTodoButton").addEventListener("click", function (e) {
    const inputEditActivity = document.getElementById("inputEditActivity");
    const editActivityError = document.getElementById("edit-activity-error");

    if (!inputEditActivity.value.trim()) {
      e.preventDefault();
      editActivityError.innerText = 'Aktivitas harus diisi'; // Menampilkan pesan kesalahan
    } else {
      editActivityError.innerText = ''; // Reset pesan kesalahan
    }
  });

  function showModalDeleteTodo(todoId, activity) {
    const modalDeleteTodo = document.getElementById("deleteTodo");
    const elementActivity = document.getElementById("deleteTodoActivity");
    const inputId = document.getElementById("inputDeleteTodoId");

    inputId.value = todoId;
    elementActivity.innerText = activity;

    var myModal = new bootstrap.Modal(modalDeleteTodo);
    myModal.show();
  }
</script>

@endsection

