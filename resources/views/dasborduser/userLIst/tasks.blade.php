@extends('dasborduser.layouts.master')

@section('title') @lang('translation.Kanban_Board') @endsection

@section('css')
    <!-- dragula css -->
    <link href="{{ URL::asset('/assets/libs/dragula/dragula.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            List
        @endslot
        @slot('title')
            List Board
        @endslot
    @endcomponent
    <div class="container-fluid">
    <!-- <div class="scrolling-wrapper row flex-row flex-nowrap mt-4 pb-4 pt-2" style="overflow-x: auto;"> -->
    <div class="row flex-nowrap"  style="max-height:80%; overflow-y: scroll;"  >
        @include('sweet::alert')
        @foreach ($list as $item)
            <div class="col-3">
                <div class="card"  style="height:95%; overflow-y: scroll;">
                    <div class="card-body">

                        @php $list_item=App\Models\User\UserItem::where('list_id',$item->id)->where('is_complete',0)->get();
                         $list_item_complete=App\Models\User\UserItem::where('list_id',$item->id)->where('is_complete',1)->get();
                        @endphp
                        <div class="dropdown float-end">
                            <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" type="button" id="renamebtn" data-toggle="modal"
                                    data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg{{ $item->id }}"
                                    data-id="{{ $item->id }}" onclick="rename()">Rename</a>
                                <a class="dropdown-item" href="{{ route('list.delete', $item->id) }}">Delete</a>
                                <a class="dropdown-item edittask-details" id="taskedit" data-bs-toggle="modal"
                                    data-bs-target=".bs-example-modal-l{{ $item->id }}">Share List
                                </a>
                            </div>
                        </div> <!-- end dropdown -->
                        <div id="sharelist" class="modal fade bs-example-modal-l{{ $item->id }}" tabindex="-1"
                            role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header ">
                                        <div class="mt-4 justify-content-center">
                                            <h5 class="font-size-20 mb-3">Share List</h5>
                                            <ul class="list-inline">
                                                <input type="hidden" name="listid" value="{{ $item->id }}">

                                                <!-- <li class="list-inline-item">
                                                    <a class="social-list-item bg-primary text-white border-primary"
                                                        id="share"  onclick="sharepdf({{ $item['id'] }})" >
                                                        <i class="mdi mdi-file-pdf"></i>
                                                    </a>
                                                </li> -->
                                                <li class="list-inline-item">
                                                    <a class="social-list-item bg-success text-white border-success"
                                                        id="sharewhats" onclick="sharewhats({{ $item['id'] }})" >
                                                        <i class="mdi mdi-whatsapp"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a class="social-list-item bg-danger text-white border-danger"
                                                        id="share" onclick="sharemail({{ $item['id'] }})">
                                                        <i class="mdi mdi-google"> </i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a value="Copy Url" onclick="share({{ $item['id'] }})" id="share"
                                                        class="social-list-item bg-secondary text-white border-secondary">
                                                        <i class="mdi mdi-link"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>


                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->


                        <form method="Post" action="{{ route('list.editname', $item->id) }}" role="form">
                            <input type="hidden" name="list_id" value="{{ $item->id }}">
                            @csrf
                            <div id="rename" data-id="{{ $item->id }}">
                            </div>
                        </form>
                          <div class="activity">
                          <h4 class="card-title mb-4" id="listname">{{ $item->name }}</h4>
                          </div>







                        <div id="task-1">
                            <div id="upcoming-task" class="pb-1 task-list">
                                @if ($list_item)
                                    @foreach ($list_item as $data)
                                        <div class="card task-box" id="uptask-1">
                                            <div class="card-body" id="card-name-{{ $data->id }}">
                                                <div class="dropdown float-end">
                                                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item edittask-details" href="#" id="taskedit"
                                                            data-bs-toggle="modal"
                                                            data-bs-target=".bs-example-modal-lg{{ $data->id }}"
                                                            data-id="{{ $data->id }}">Edit</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('list.destoryItem', $data->id) }}">Delete</a>
                                                    </div>
                                                </div> <!-- end dropdown -->
                                                <div class="float-end ml-2 ">
                                                </div>
                                                <div >

                                                    <input class="form-check-input bg-danger" style="color:#e30000" onclick="complete({{ $data['id'] }})" id="task-name-{{ $data->id }}" type="checkbox" value="{{ $data->name }}"   />
                                                    <label  id="label-name-{{ $data->id }}"> {{ $data->name }} </label>
                                                </div>

                                            </div>
                                        </div>


                                        <div id="editform{{ $data->id }}"
                                            class="modal fade bs-example-modal-lg{{ $data->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title mt-0 update-task-title"
                                                            style="display: none;">Update item</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="Post"
                                                            action="{{ route('list.editItem', $data->id) }}"
                                                            role="form">
                                                            @csrf
                                                            <input type="hidden" name="list_id"
                                                                value="{{ $item->id }}">
                                                            <div class="form-group mb-3">
                                                                <label for="taskname" class="col-form-label">Item Name<span
                                                                        class="text-danger">*</span></label>
                                                                <div class="col-lg-12">
                                                                    <input id="taskname" name="name" type="text"
                                                                        class="form-control validate"
                                                                        value="{{ $data->name }}">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-lg-10">
                                                                    <button type="submit" class="btn btn-danger"
                                                                        style="background-color:#e30000">Update
                                                                        Item</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->
                                    @endforeach
                                @endif

                            </div>


                            <div class="text-center d-grid">
                                <a href="javascript: void(0);" class="btn btn-danger waves-effect waves-light"
                                    style="background-color:#e30000" data-bs-toggle="modal"
                                    data-bs-target=".bs-example-modal-lg-lg{{ $item->id }}"
                                    data-id="{{ $item->id }}"><i class="mdi mdi-plus me-1"></i> Add New Item</a>
                                    <br>
                                    <br>
                            </div>
                            <div class="d-grid" id="loadform" >
                            <h4 class="card-title mb-4" id="listname">Complete task</h4>
                            @foreach ($list_item_complete as $data )
                            <div class="completediv-{{ $data->id }}">
                            <div class="card task-box" id="uptask-1">
                                            <div class="card-body" id="card-name-{{ $data->id }}">

                                                <div class="float-end ml-2 ">
                                                </div>
                                                <div >


                                                    <label  id="label-name-{{ $data->id }}">  <s>{{ $data->name }}</S> </label>
                                                </div>

                                            </div>
                                        </div>

                            </div>



                            @endforeach

                            </div>




                            <div id="modalForm" class="modal fade bs-example-modal-lg-lg{{ $item->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title mt-0 add-task-title">Add New item</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form method="Post" action="{{ route('list.addItem', $item->id) }}"
                                                role="form">
                                                @csrf
                                                {{-- <input type="hidden" name="list_id" value="{{ $item->id }}"> --}}
                                                <div class="form-group mb-3">
                                                    <label for="taskname" class="col-form-label">Item Name<span
                                                            class="text-danger">*</span></label>
                                                    <div class="col-lg-12">
                                                        <input id="taskname" name="name" type="text"
                                                            class="form-control validate" placeholder="Enter  Name..."
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-10">
                                                        <button type="submit" class="btn btn-danger"
                                                            style="background-color:#e30000" id="addtask">Add Item</button>
                                                    </div>
                                                </div>


                                            </form>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </div>
                    </div>
                </div>
            </div>




            <script>
                function rename() {
                    // First create a DIV element.
                    var txtNewInputBox = document.createElement('div');
                    document.getElementById("listname").style.display = "none";
                    document.getElementById("renamebtn").style.display = "none";
                    var dataID = element.getAttribute('data-id');
                    // Then add the content (a new input box) of the element.
                    txtNewInputBox.innerHTML = "<input type='text' name='name'  class='form-control' placeholder='Rename list'>";
                    // Finally put it where it is supposed to appear.
                    // document.querySelector(`[data-id]`);
                    document.getElementById("rename").appendChild(txtNewInputBox);
                }
            </script>
            <script>
                function share(id) {
                    // var id= $("input[name=listid]").val();
                    $.ajax({
                        url: '/list/get_link_list/' + id,
                        data: {
                            format: 'json',
                        },
                        success: function share(data) {
                            link = 'https://www.monolist.io/';

                            newlink = link.concat(data.data);
                            var inputc = document.body.appendChild(document.createElement("input"));
                            inputc.value = newlink;
                            inputc.focus();
                            inputc.select();
                            document.execCommand('copy');
                            inputc.parentNode.removeChild(inputc);
                            $("#sharewhats").attr("href",newlink);
                            alert("URL Copied.");
                        },
                        error: function() {
                            console.log(id);
                            alert('An error has occurred');
                        },
                        type: 'GET'
                    });
                };
            </script>
            <script>
            function sharewhats(id) {

                    $.ajax({
                        url: '/list/get_link_list/' + id,
                        data: {
                            format: 'json',
                        },
                        success: function share(data) {
                            link = 'https://wa.me/?text=https://www.monolist.io/';

                            newlink = link.concat(data.data);

                            window.open(newlink);

                            alert("URL Copied.");
                        },
                        error: function() {
                            console.log(id);
                            alert('An error has occurred');
                        },
                        type: 'GET'
                    });
                };
            </script>
              <script>
            function sharemail(id) {

                    $.ajax({
                        url: '/list/get_link_list/' + id,
                        data: {
                            format: 'json',
                        },
                        success: function share(data) {

                            link = "mailto:?subject=Monolist&body=Check out this site https://www.monolist.io/"+data.data;
                            window.open(link);


                        },
                        error: function() {
                            console.log(id);
                            alert('An error has occurred');
                        },
                        type: 'GET'
                    });
                };
            </script>

<script>
                function list() {
                    var name=document.getElementById("listnamein").value;
                    console.log(name);

                    var color=$("#color").val();
                    var type=$("#type").val();

                  $.ajax({
                      url: '{{route('list.store.list')}}',
                      method: 'post',
                      data: {
                          format: 'json',
                          "_token":"{{csrf_token()}}",
                          "name":name,
                          "color":color,
                          "type":type

                      },
                      success: function list(data) {
                        location.reload();
                          JQuery('activity').append(data.div);
                      },
                      error: function() {
                          alert(name);
                      },

                  });

              };

             </script>




             <script>
                function complete(id) {
                    // event.preventDefault();
                  $.ajax({
                      url: '/list/is_complete/' + id,
                      data: {
                          format: 'json',
                      },
                      success: function complete(data) {
                       if(data.data.is_complete==1){

                     var x=document.getElementById("label-name-"+data.data.id).style.textDecoration="line-through";
                     window.location.href = "{{ route('list.list') }}";

                        }
                         return false;
                      },
                      error: function() {
                          console.log(id);
                          alert("err");
                      },
                      type: 'GET'
                  });

              };

             </script>



</script>



        @endforeach
        <div class="col-3">
            <div class="card" >

                    <input type="hidden" name="color" id="color" value="color">
                    <input type="hidden" name="type" id="type" value="type">
                    <button onclick="list()">add</button>






                    <div id="newElementId">
                    </div>



                <div class="card-body">
                    <div class="text-center d-grid">
                        <a href="javascript: void(0);" id="newbtnId" onclick="createNewElement();"
                            class="btn btn-danger waves-effect waves-light" style="background-color:#e30000"
                            data-bs-toggle="modal1" data-bs-target=".bs-example-modal-lg-lg" data-id="modalForm"><i
                                class="mdi mdi-plus me-1"></i> Add New List</a>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- end col -->


    </div>
    </div>


    <!-- end row -->
@endsection
@section('script')
    <!-- dragula plugins -->
    <script src="{{ URL::asset('/assets/libs/dragula/dragula.min.js') }}"></script>
    <script>
        function createNewElement() {
            // First create a DIV element.
            var txtNewInputBox = document.createElement('div');
            document.getElementById("newbtnId").style.display = "none";
            // Then add the content (a new input box) of the element.
            txtNewInputBox.innerHTML ="<input type='text' name='name' id='listnamein'  class='form-control' placeholder='Enter List Name'>";
            // Finally put it where it is supposed to appear.
            document.getElementById("newElementId").appendChild(txtNewInputBox);
        }
    </script>


    <!-- jquery-validation -->
    <script src="{{ URL::asset('/assets/libs/jquery-validation/jquery-validation.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/task-kanban.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/task-form.init.js') }}"></script>
@endsection
