{{-- <!DOCTYPE html> --}}
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laravel 10 CRUD With Image and Video Upload using jQuery Ajax with SweetAlert and DataTables</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    </head>
    <body>
        {{-- Header of the Page --}}
        <div class="container">
            <div class="row my-5">
               <div class="col-lg-12">
                <h2>Image and Video Uploading</h2>
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-end align-items-end">
                    {{-- <div class="card-header d-flex justify-content-around"> --}}
                        {{-- <h3>Upload Image and Video</h3> --}}
                        {{-- <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addUploadModal"><i class="bi-plus-circle me-2"></i>Add Image</button>
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addVideoModal"><i class="bi-plus-circle me-2"></i>Add Video</button> --}}
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addUploadModal"><i class="bi-plus-circle me-2"></i>Upload image and video</button>
                        {{-- <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addVideoModal"><i class="bi-plus-circle me-2"></i>Add Video</button> --}}
                    </div>
                    <div class="card-body" id="show_all_sample">
                        <h1 class="text-center text-secondary my-5">Loading...</h1>
                    </div>
                </div>
            </div>
        </div>

        {{-- Create options and action button --}}
        <div class="modal fade" id="addUploadModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Upload</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" method="POST" id="add_form" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4 bg-light">
                            <div class="my-2">
                                <label for="image">Select Image</label>
                                {{-- <label>Select Image</label> --}}
                                <input type="file" name="image" class="form-control" required>
                            </div>
                            <div class="my-2">
                                <label for="video">Select Video</label>
                                <input type="file" name="video" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="add_btn" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit --}}
        <div class="modal fade" id="editUploadModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" method="POST" id="edit_form" enctype="multipart/form-data">
                        @csrf
                        {{-- @method('PUT') --}}
                        <input type="hidden" name="upload_id" id="upload_id">
                        <input type="hidden" name="upload" id="upload">
                        <div class="modal-body p-4 bg-light">
                            <div class="my-2">
                                <label for="image">Select Image</label>
                                <input type="file" name="image" id="image" class="form-control">
                            </div>
                            <div class="my-2">
                                <label for="video">Select Video</label>
                                <input type="file" name="video" id="video" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="edit_btn" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <script src=" https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js "></script>
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(function() {
                //add images 
                $("#add_form").submit(function(a) {
                    a.preventDefault();
                    const cnst = new FormData(this);
                    $("#add_btn").text('Adding');
                    $.ajax({
                        // headers: {
                        //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        // },
                        url: '{{ route('store') }}',
                        method: 'post',
                        data: cnst,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(response) {
                            //checking log
                            //console.log(response);
                            if (response.status == 200) {
                                Swal.fire(
                                    'Added!',
                                    'Added Successfully!',
                                    'success'
                                )
                                fetchAllDatas();
                            }
                            $("#add_btn").text('Uploaded');
                            $("#add_form")[0].reset();
                            $("#addUploadModal").modal('hide');
                        }
                    })
                });
                
                //edit image and video
                $(document).on('click', '.editIcon', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                    $.ajax({
                        // headers: {
                        //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        // },
                        url: '{{ route('edit') }}',
                        method: 'get',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $("#image").html('<img src="/public/storage/SampleImages/${response.image}" width="100" class="img-fluid img-thumbnail">');
                            $("#upload_id").val(response.id);
                            $("#upload").val(response.image);
                        }
                    });
                });

                //update image and video
                $("#edit_form").submit(function(a) {
                    a.preventDefault();
                    const cnst = new FormData(this);
                    $("#edit_btn").text('Updating');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('update') }}',
                        method: 'put',
                        data: cnst,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 200) {
                                Swal.fire(
                                    'Updated!',
                                    'Image and Video Successfully Updated!',
                                    'success'
                                )
                                fetchAllDatas();
                            }
                            $("#edit_btn").text('Update Image and Video');
                            $("#edit_form")[0].reset();
                            $("#editUploadModal").modal('hide');
                        }
                    })
                });

                // delete images ajax request
                $(document).on('click', '.deleteIcon', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('id');
                    let csrf = '{{ csrf_token() }}';
                    Swal.fire({
                        title: 'Do you want to delete this image?',
                        text: "It will be permanently deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('delete') }}',
                                method: 'delete',
                                data: {
                                    id: id,
                                    _token: csrf
                                },
                                success: function(response) {
                                    console.log(response);
                                    Swal.fire(
                                        'Deleted!',
                                        'Image has been deleted.',
                                        'success'
                                    )
                                    fetchAllDatas();
                                }
                            })
                        }
                    })
                });

                //fetch all datas 
                fetchAllDatas();
                function fetchAllDatas() {
                    //alert("Show all data");
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('fetchAll') }}',
                        method: 'get',
                        success: function(response){
                            //message
                            $("#show_all_sample").html(response);
                        }
                    })
                }
            });
        </script>
    </body>
</html>
    