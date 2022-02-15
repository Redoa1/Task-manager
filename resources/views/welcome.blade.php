<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">

    <title>Hello, world!</title>
    <style>

    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">LOGO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                </ul>
                <div class="d-flex">
                    @if (Route::has('login'))
                        <div class=" py-8">
                            @auth
                                <div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Redoan
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf

                                                <x-dropdown-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                                                this.closest('form').submit();">
                                                    {{ __('Log Out') }}
                                                </x-dropdown-link>
                                            </form>
                                        </li>
                                    </ul>

                                </div>
                            @else
                                <a href="{{ route('login') }}" class="underline">Log
                                    in </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ms-4 me-4 underline">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1>Task Management App </h1>
        @auth
            <div class="d-flex justify-content-end mb-2">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal"
                    data-bs-whatever="@mdo">Create New Task</button>
            </div>
        @endauth
        <table class="table table-striped table-bordered " id="mytable">
            <thead>
                <tr class="text-center">
                    <th scope="col">Title</th>
                    <th scope="col">Due Date</th>
                    <th scope="col">Duration</th>
                    <th scope="col">Type</th>
                    @auth
                        <th scope="col">Action</th>
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr class="text-center">
                        <td>{{ $task->title }}</td>
                        @php
                            $due_date = $task->due_date;
                            $date = new Illuminate\Support\Carbon($due_date);
                            $monthDateYear = $date->format('m/d/Y');
                            $hourAndMinute = $date->format('h:i a');
                            $now = Illuminate\Support\Carbon::now();
                        @endphp
                        @if ($date > $now)
                            <td class="text-danger">{{ $monthDateYear }} at {{ $hourAndMinute }}</td>
                        @else
                            <td>{{ $monthDateYear }} at {{ $hourAndMinute }}</td>
                        @endif
                        <td>{{ $task->hour }} : {{ $task->minute }}</td>
                        <td>
                            @if ($task->type == 1)
                                <i class="fa-solid fa-phone"></i> Call
                            @elseif ($task->type == 2)
                                <i class="fa-solid fa-clock"></i> Deadline
                            @elseif ($task->type == 3)
                                <i class="fa-solid fa-envelope"></i> Email
                            @elseif ($task->type == 4)
                                <i class="fa-solid fa-users"></i> Meeting
                            @endif
                        </td>
                        @auth
                            <td class="d-flex text-center">

                                <button type="button" id="editTask" class="btn btn-primary me-2" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal2" data-id="{{ $task->id }}">Edit</button>

                                <form action="{{ route('tasks.destroy', ['task' => $task->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" onclick="confirm('Are you sure?')"
                                        type="submit">Delete</button>
                                </form>
                            </td>
                        @endauth
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- For creating task --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span
                            class="glyphicon glyphicon-earphone"></span>New message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Title:</label>
                            <input type="text" class="form-control" id="title" value="{{ old('title') }}"
                                name="title" required>
                            @if ($errors->has('title'))
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Due Date:</label>
                            <input type="datetime-local" value="{{ old('due_date') }}" class="form-control"
                                id="recipient-name" name="due_date" required>
                            @if ($errors->has('due_date'))
                                <span class="text-danger">{{ $errors->first('due_date') }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Duration:</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="number" class="form-control" value="{{ old('hour') }}"
                                        id="recipient-name" placeholder="Hour" name="hour" required>
                                    @if ($errors->has('hour'))
                                        <span class="text-danger">{{ $errors->first('hour') }}</span>
                                    @endif
                                </div>:
                                <div class="col-md-5">
                                    <input type="number" class="form-control" value="{{ old('minute') }}"
                                        id="recipient-name" placeholder="Minute" name="minute" required>
                                    @if ($errors->has('minute'))
                                        <span class="text-danger">{{ $errors->first('minute') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <select class="form-select" aria-label="Default select example" name="type" required>
                                <option selected>Select type</option>
                                <option value="1">Call</option>
                                <option value="2">Deadline</option>
                                <option value="3">Email</option>
                                <option value="4">Meeting</option>
                            </select>
                            @if ($errors->has('type'))
                                <span class="text-danger">{{ $errors->first('type') }}</span>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- For updating task --}}
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2"><span
                            class="glyphicon glyphicon-earphone"></span>New message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tasks.update', ['task' => $task->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Title:</label>
                            <input type="text" class="form-control" id="title1" value="" name="title" required>
                            @if ($errors->has('title'))
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Due Date:</label>
                            <input type="text" value="" class="form-control" id="due_date1" name="due_date" disabled>
                            <input type="datetime-local" value="" class="form-control" id="due_date1" name="due_date"
                                required>
                            @if ($errors->has('due_date'))
                                <span class="text-danger">{{ $errors->first('due_date') }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Duration:</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="number" class="form-control" value="" id="hour1" placeholder="Hour"
                                        name="hour" required>
                                    @if ($errors->has('hour'))
                                        <span class="text-danger">{{ $errors->first('hour') }}</span>
                                    @endif
                                </div>:
                                <div class="col-md-5">
                                    <input type="number" class="form-control" value="" id="minute1"
                                        placeholder="Minute" name="minute" required>
                                    @if ($errors->has('minute'))
                                        <span class="text-danger">{{ $errors->first('minute') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <select class="form-select" aria-label="Default select example" name="type" id="type1"
                                required>
                                <option selected>Select type</option>
                                <option value="1" @if ($task->type == 1) selected @endif>Call</option>
                                <option value="2" @if ($task->type == 2) selected @endif>Deadline</option>
                                <option value="3" @if ($task->type == 3) selected @endif> Email</option>
                                <option value="4" @if ($task->type == 4) selected @endif> Meeting</option>
                            </select>
                            @if ($errors->has('type'))
                                <span class="text-danger">{{ $errors->first('type') }}</span>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>  
    {{-- For updating task --}}

    {{-- For deleting task --}}


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="http://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#mytable').DataTable();

            @if (count($errors) > 0)
                $('#exampleModal').modal('show');
            @endif
        });

        $('body').on('click', '#editTask', function() {
            var id = $(this).data('id');
            $.get('tasks/' + id, function(data) {
                $('#title1').val(data.title);
                $('#due_date1').val(data.due_date);
                $('#hour1').val(data.hour);
                $('#minute1').val(data.minute);
                $('#type1').val(data.type);
            })
        });
    </script>

</body>

</html>
