<!DOCTYPE html>
<html lang="en">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<style>
    .form-input {
        position: relative;
    }
    .form-control-label {
        background-color: #fff;
        font-size: 12px;
        position: absolute;
        top: -9px;
        left: 7px;
        margin: 0;
        padding: 4px 5px;
        border-radius: 0.25rem;
        line-height: 0.9;
        z-index: 3;
    }
    .form-control {
        font-size: 14px;
        color: #000;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.7rem 0.8rem;
        display: block;
        position: relative;
        z-index: 1;
        transition: ease 0.15s;
    }
    .form-control:focus {
        border-color: #006838;
        box-shadow: 0 0 0 0.25rem rgb(129 47 28 / 25%);
        z-index: 2;
    }

    

    /* ================================================================
    Drag and Drop
    ================================================================ */
    .dropzone {
        background-color: #fff;
        border-radius: 0.35rem;
        width: 100%;
        height: 250px;
        margin-left: auto;
        border: 1px dashed #dfe4ee;
        overflow: hidden !important;
        cursor: pointer;
        position: relative;
    }
    .dropzone:hover {
        background-color: #fbf8ff;
    }
    .dz-message {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .relative {
        position: relative;
    }
    .inputfile {
        cursor: pointer;
        width: 100%;
        height: 100%;
        margin-left: auto;
        opacity: 0;
        border-radius: 8px;
        overflow: hidden;
        position: absolute;
        z-index: 1;
    }
    .inputfile+label {
        width: 100%;
        color: #aaa;
        margin: 0 auto;
        background-color: #fafaff;
        display: inline-block;
    }
    .inputfile:focus+label,
    .inputfile+label:hover {
        background-color: #f1f1f1;
        color: #888 !important;
    }
    .inputfile+label {
        cursor: pointer;
    }
    .gallery {
        margin-top: 0;
        border-radius: 0.35rem;
        border: 1px dashed #dfe4ee;
    }
    .gallery-img {
        width: 100%;
        height: auto;
    }
    .gallery-img1 {
        width: 100%;
        height: auto;
    }
    @media (max-width: 768px) {
        .gallery-img {
            max-width: 100%;
        }
    }

    .add_subtask {
        color: black;
        font-weight: bold;
        background-color: transparent;
        text-decoration: none;
    }
    a:hover {
        color: green;
        font-weight: bold;
        background-color: transparent;
        text-decoration: none;
    }
</style>

    <a class="" href="#" id="logoutBtn" style='text-decoration: none;'>
        <span class="text-dark">Logout</span>
    </a>

    <h3 class="mb-3 fw-bold">Task Management</h3>
    <div class="row">
        <div class="col-12">
            <div class="card m-0 p-3 pb-2">
                
                <div class="card-header m-0 p-1">
                    <div class="d-flex flex-row">
                        <div class="flex-fill">                            
                            <form action="{{ route('task-mgmt') }}" method="GET">
                                <div class="input-group ms-auto float-end filter_custom_width_" style="width: auto;">
                                    
                                    <div class="form-input">
                                        <label for="filter_status" class="form-control-label">Status</label>
                                        <select class="form-select select-status" name="status" id='filter_status' required>
                                            <option value="all" selected>[All Status]</option>
                                            <option value="to-do">To-do</option>
                                            <option value="in-progress">In-Progress</option>
                                            <option value="done">Done</option>
                                        </select>
                                    </div>
                                    <div class="form-input">
                                        <label for="filter_title" class="form-control-label">Title</label>
                                        <input type="text" name="title" id="filter_title" class=" form-control" style="height: 38px">
                                    </div>
                                    <button type='submit' name='btn_form' class="btn btn-primary">
                                        Search
                                    </button>
                                    <button type='button' class="btn btn-success" id="addBtn">
                                        <i class="fa fa-pencil" aria-hidden="true"></i> Add Task
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                </div>

                <div class="card-body p-1">
                    <table id="TasksList" class="display table table-hover nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Status</th>
                                <th>Image</th>
                                <th>Save As</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $key => $task)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        <a href="#" class="add_subtask" data-task="{{ $task }}">
                                            {{$task->title}}
                                        </a>
                                    </td>
                                    <td>{{$task->content}}</td>
                                    <td class="">
                                        <a href="#" class="edit_status {{$task->status == 'done' ? 'text-success': ($task->status == 'in-progress' ? 'text-primary':'text-warning')}}" data-task="{{ $task }}" style='text-decoration: none;'>
                                            {{ucFirst($task->status)}}
                                        </a>
                                    </td>
                                    <td>
                                        @php $img = asset('task_img/').'/'.$task->image; @endphp
                                        
                                        @if ($task->image != null)
                                        <img src="{{$img}}" alt="image" class="me-2" style="max-height: 48px;"/>
                                        @endif
                                    </td>
                                    <td class="">
                                        <a href="#" class="edit_save_type {{$task->save_as == 'publish' ? 'text-success':'text-warning'}}" data-task="{{ $task }}" style='text-decoration: none;'>
                                            {{ucFirst($task->save_as)}}
                                        </a>
                                    </td>
                                    <td>{{ date('m/d/Y h:i a', strtotime($task->created_at)) }}</td>
                                    <td>
                                        @if($task->trash_at != null)
                                            <button class="btn btn-sm btn-primary retrieve_task" data-task="{{ $task }}" type="button">Recover</button>
                                        @else
                                            <button class="btn btn-sm btn-danger delete_task" data-task="{{ $task }}" type="button">Delete</button>
                                        @endif
                                    </td>
                                </tr>
                                @foreach ($task->tasks as $key => $subtask)
                                    <tr>
                                        <td></td>
                                        <td>{{$subtask->title}}</td>
                                        <td>{{$subtask->content}}</td>
                                        <td class="">
                                            <a href="#" class="edit_status {{$subtask->status == 'done' ? 'text-success': ($subtask->status == 'in-progress' ? 'text-primary':'text-warning')}}" data-task="{{ $subtask }}" style='text-decoration: none;'>
                                                {{ucFirst($subtask->status)}}
                                            </a>
                                        </td>
                                        <td>
                                            @php $img = asset('task_img/').'/'.$subtask->image; @endphp
                                            
                                            @if ($subtask->image != null)
                                            <img src="{{$img}}" alt="image" class="me-2" style="max-height: 48px;"/>
                                            @endif
                                        </td>
                                        <td class="">
                                            <a href="#" class="edit_save_type {{$subtask->save_as == 'publish' ? 'text-success':'text-warning'}}" data-task="{{ $subtask }}" style='text-decoration: none;'>
                                                {{ucFirst($subtask->save_as)}}
                                            </a>
                                        </td>
                                        <td>{{ date('m/d/Y h:i a', strtotime($subtask->created_at)) }}</td>
                                        <td>
                                            @if($subtask->trash_at != null)
                                                <button class="btn btn-sm btn-primary retrieve_task" data-task="{{ $subtask }}" type="button">Recover</button>
                                            @else
                                                <button class="btn btn-sm btn-danger delete_task" data-task="{{ $subtask }}" type="button">Delete</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
{{-- @endsection --}}

{{-- @push('modals') --}}

    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-modal="false" data-mdb-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-md">
                <div class="modal-header">
                    <h4 class="mb-0">Add Task</h4>
                    <span class="modal-close modal-close-btn">&times;</span>
                </div>
                <div class="modal-body">
                    <span id="error_msg_sec"></span>
                    <form action="{{ route('task.mgmt.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-12 mb-2">
                                <label class="form-label">Title</label>
                                <input class="form-control" type="text" name="title" maxlength="100" required autofocus>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label">Content</label>
                                <input class="form-control" type="text" name="content" required>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label">Status</label>
                                <select class="form-select select-status" name="status" required>
                                    <option value="to-do">To-do</option>
                                    <option value="in-progress">In-Progress</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                            <label class="d-flex align-items-center justify-content-between gap-4">
                                <span>
                                    Image <span class="text-uppercase" style="font-size: 8px;"></span>
                                </span>
                                <span>
                                    <a id="clear" onclick="clearImages()" style="display: none">Clear</a>
                                </span>
                            </label>
                            <div class="rounded d-flex flex-column align-item-center justify-content-center text-center relative">
                                <input type="file" name="file" id="gallery-photo-add" class="d-flex inputfile" accept="image/*"/>
                                <label for="gallery-photo-add">
                                    <div class="dropzone" style="display: block">
                                        <div class="dz-message">
                                            <i class="h3 text-muted ri-upload-cloud-2-line"></i>
                                            <div class="mb-1">Click or drop the image here</div>
                                            <div class="small">Maximum Size 4MB</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="gallery" style="display: none"></div>
                        </div>
                        </div>

                        <div class="row mx-0 mb-2">
                            <div class="d-flex mx-0 my-0 px-0">
                                <button name='btnForm' value="publish" class="btn btn-success ms-2 px-4 ms-auto" type="submit">Publish</button>
                                <button name='btnForm' value="draft" class="btn btn-primary ms-2 px-4" type="submit">Save as Draft</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createSubTaskModal" tabindex="-1" role="dialog" aria-modal="false" data-mdb-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-md">
                <div class="modal-header">
                    <h4 class="mb-0">Create Sub Task</h4>
                    <span class="modal-close modal-close-btn">&times;</span>
                </div>
                <div class="modal-body">
                    {{-- <span id="error_msg_sec"></span> --}}
                    <form action="{{ route('task.mgmt.create.subtask') }}" id="myForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-12 mb-2">
                                <label class="form-label">Task Title</label>
                                <input class="form-control" type="text" name="task_title" id="task_title" maxlength="100" readonly required>
                                <input class="form-control" type="hidden" name="task_id" id="task_id" maxlength="100" readonly required>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label">Sub Task Title</label>
                                <input class="form-control" type="text" name="sub_task_title" maxlength="100" required autofocus>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label">Content</label>
                                <input class="form-control" type="text" name="sub_task_content" required>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label">Status</label>
                                <select class="form-select select-status" name="sub_task_status" required>
                                    <option value="to-do">To-do</option>
                                    <option value="in-progress">In-Progress</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                            
                        </div>

                        <div class="row mx-0 mb-2">
                            <div class="d-flex mx-0 my-0 px-0">
                                <button name='sub_task_btnForm' value="publish" class="btn btn-success ms-2 px-4 ms-auto" type="submit">Publish</button>
                                <button name='sub_task_btnForm' value="draft" class="btn btn-primary ms-2 px-4" type="submit">Save as Draft</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editStatusModal" tabindex="-1" role="dialog" aria-modal="false" data-mdb-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-md">
                <div class="modal-header">
                    <h4 class="mb-0">Update Status</h4>
                    <span class="modal-close modal-close-btn">&times;</span>
                </div>
                <div class="modal-body">
                    {{-- <span id="error_msg_sec"></span> --}}
                    <form action="{{ route('task.mgmt.status.update') }}" method="POST">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-12 mb-2">
                                <label class="form-label">Task Title</label>
                                <input class="form-control" type="text" name="task_title" id="task_title_status_update" maxlength="100" readonly required>
                                <input class="form-control" type="hidden" name="task_id" id="task_id_status_update" maxlength="100" readonly required>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label">Status</label>
                                <select class="form-select select-status" id="task_status_update" name="task_status_update" required>
                                    <option value="to-do">To-do</option>
                                    <option value="in-progress">In-Progress</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                            
                        </div>

                        <div class="row mx-0 mb-2">
                            <div class="d-flex mx-0 my-0 px-0">
                                <button class="btn btn-success ms-2 px-4 ms-auto" type="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSaveTypeModal" tabindex="-1" role="dialog" aria-modal="false" data-mdb-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-md">
                <div class="modal-header">
                    <h4 class="mb-0">Update Save Type</h4>
                    <span class="modal-close modal-close-btn">&times;</span>
                </div>
                <div class="modal-body">
                    {{-- <span id="error_msg_sec"></span> --}}
                    <form action="{{ route('task.mgmt.saveType.update') }}" method="POST">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-12 mb-2">
                                <label class="form-label">Task Title</label>
                                <input class="form-control" type="text" name="task_title" id="task_title_save_type_update" maxlength="100" readonly required>
                                <input class="form-control" type="hidden" name="task_id" id="task_id_save_type_update" maxlength="100" readonly required>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label">Save Type</label>
                                <select class="form-select select-status" id="task_save_type_update" name="task_save_type_update" required>
                                    <option value="publish">Publish</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                            
                        </div>

                        <div class="row mx-0 mb-2">
                            <div class="d-flex mx-0 my-0 px-0">
                                <button class="btn btn-success ms-2 px-4 ms-auto" type="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
{{-- @endpush --}}

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

{{-- <script src="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css"></script> --}}
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

@if ($message_update = session('message'))
    <script>
        swal("", "{{ session('message') }}", "{{ session('error_type') }}");
    </script>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            $('#error_msg_sec').append('<li class="text-danger">'+'{{ $error }}'+'</li>');

            console.log('{{ $error }}')
        </script>
    @endforeach
    <script type='text/javascript'>
        $(document).ready(function(event) {
            $('#createModal').modal('show');
        });
    </script>
@endif

<script type='text/javascript'>
    $(document).ready(function(event) {

        var url = new URL(window.location);
        var filter_status = url.searchParams.get("status");
        var filter_title = url.searchParams.get("title");
        
        $('#filter_status').val((filter_status ? filter_status : 'all'));
        $('#filter_title').val(filter_title);
        

        $('#TasksList').DataTable({
            select : true,
            "aaSorting": [],
            // "order" : [[0, "desc"]],
            // "scrollY" : "380px",
            pageLength: 50,
            "scrollCollapse" : true,
            "paging" : true,
            "bProcessing" : true,
            bFilter: false, bInfo: false,
        });
 
        $('#addBtn').click(function (event) {
            event.preventDefault();
            $('#createModal').modal('show');
        });

        $('.add_subtask').click(function (event) {
            event.preventDefault();

            var task_info = JSON.parse($(this).attr('data-task'));

            $('#task_title').val(task_info.title);
            $('#task_id').val(task_info.id);
            
            $('#createSubTaskModal').modal('show');
        });

        $('.edit_status').click(function (event) {
            event.preventDefault();

            var task_info = JSON.parse($(this).attr('data-task'));

            $('#task_title_status_update').val(task_info.title);
            $('#task_id_status_update').val(task_info.id);

            $('#task_status_update').val(task_info.status).trigger('change');
            
            $('#editStatusModal').modal('show');
        });

        $('.edit_save_type').click(function (event) {
            event.preventDefault();

            var task_info = JSON.parse($(this).attr('data-task'));

            $('#task_title_save_type_update').val(task_info.title);
            $('#task_id_save_type_update').val(task_info.id);

            $('#task_save_type_update').val(task_info.save_as).trigger('change');
            
            $('#editSaveTypeModal').modal('show');
        });

        $('.delete_task').click(function (event) {
            event.preventDefault();

            var task_info = JSON.parse($(this).attr('data-task'));

           console.log(task_info.id);

           alert_msg('warning', '', "Are you sure you want to delete Task : <b>"+(task_info.title)+"</b>?", task_id = task_info.id, 'Delete');
        });

        $('.retrieve_task').click(function (event) {
            event.preventDefault();

            var task_info = JSON.parse($(this).attr('data-task'));

           console.log(task_info.id);

           alert_msg('warning', '', "Are you sure you want to retrieve Task : <b>"+(task_info.title)+"</b>?", task_id = task_info.id, 'Retrieve');
        });

        $('#logoutBtn').click(function (event) {
            axios.post("{{ route('logout-post') }}")
                .then(function (response) {
                    window.location.href = "{{ route('login') }}";
                }).catch(function (error) {
                    showErrorAlert(error.response.data.message);
                });
        });

    });

    function alert_msg(type, title, msg, task_info, action) {
        Swal.fire({
            icon: type,
            title: title,
            html: msg,
            showConfirmButton: true,
            showCancelButton: true,
            showDenyButton: false,
            confirmButtonColor: (action == 'Delete' ? 'red':'blue'),
            confirmButtonText: action,
            cancelButtonColor: '#495057',
            cancelButtonText: 'Cancel',
            denyButtonText: 'No',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                if(action == 'Delete')
                {
                    window.location.href = "{{ route('delete.task') }}?task_info=" + task_info;
                }
                else if(action == 'Retrieve')
                {
                    window.location.href = "{{ route('retrieve.task') }}?task_info=" + task_info;
                }
            }
        });
    }


    function clearImages() {
        $(".gallery").css("display", "none");
        $(".inputfile").css("display", "block");
        $(".dropzone").css("display", "block");
        $("#clear").css("display", "none");
        $('#gallery-photo-add').val("");
        for (const elem of document.querySelectorAll(".gallery-img")) {
            elem.remove();
        }
    }
    
    $(function() {
        // Multiple images preview in browser
        var imagesPreview = function(input, placeToInsertImagePreview) {

            if (input.files.length > 5) {
                $('#gallery-photo-add').val("");
                $("#exceedModal").modal('show');

                // var filesAmount = input.files.length;
            } else {
                console.warn(input.files);
                for (const file of input.files) {
                    $(".gallery").css("display", "block");
                    $(".inputfile").css("display", "none");
                    $(".dropzone").css("display", "none");
                    $("#clear").css("display", "block");
                    $($.parseHTML('<img class="gallery-img">')).attr('src', URL.createObjectURL(file)).appendTo(placeToInsertImagePreview);
                }
            }
        };

        $('#gallery-photo-add').on('change', function() {
            imagesPreview(this, 'div.gallery');

        });
    });
</script>

</html>