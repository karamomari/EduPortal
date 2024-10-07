<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Document</title>
</head>
<body>
    

    <div class="container " id="alll">
        <h1>Welcome, {{$admin->username }}!</h1>
        <p>Email: {{ $user->email }}</p>
        <h1>Student Management</h1>
    
    
        <div class="container">
            <div class="row">
                <div class="col-auto">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addstudentModal">Add Student</button>
                </div>
    
                <div class="col-auto">
                    <button class="btn btn-secondary" data-toggle="modal" data-target="#addSubjectModal">Add Subject</button>
                </div>
    
                <div class="col-auto">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addMarkModal">Assign Subject</button>
    
                </div>
    
                
                <div class="col-auto">
                    <button class="btn btn-danger" data-toggle="modal" data-target="#registerSubjectModal">Register Subject</button>
                </div>
            </div>
        </div>
    
    
        <h2 class="mt-4">Inactive Students</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Activation</th>
                </tr>
            </thead>
            <tbody>
                @forelse($studentunactive as $student)
                    <tr>
                        <td>{{ $student->full_name }}</td>
                        <td>{{ $student->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <form action="{{ route('student.destroy', $student->user_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                            </form>
                            
    
                           <!-- Spacer -->
                           <span style="margin: 0 5px;">|</span>
     
                           <form action="{{ route('student.update', $student->user_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to Active this student?')">Active</button>
                          </form>
                        </td>
    
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No inactive students found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    
    
    
    
    
        <h2 class="mt-4">Active Students</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Activation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($studentactive as $student)
                    <tr>
                        <td>{{ $student->full_name }}</td>
                        <td>{{ $student->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            
                      
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal"
                            data-username="{{ $student->full_name }}"
                            data-active="{{ $student->isActive ? '1' : '0' }}"
                            data-id="{{ $student->user_id }}" 
                        >Edit</button>
                        
                            <span style="margin: 0 5px;">|</span>
                            
                            <form action="{{ route('student.update', $student->user_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                            </form>
                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No active students found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
    
    </div>
    
    
    
<!-- Modal Edit Information -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal">Edit Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="studentId">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                        <label>Activation Status</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="isActive" id="active" value="E" checked>
                            <label class="form-check-label" for="active">Active</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="isActive" id="inactive" value="D">
                            <label class="form-check-label" for="inactive">Inactive</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>

                <script>
                    $(document).ready(function() {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $('#exampleModal').on('show.bs.modal', function(event) {
                            var button = $(event.relatedTarget); // Button that triggered the modal
                            var username = button.data('username'); // Extract info from data-* attributes
                            var isActive = button.data('active');

                            // Update the modal's content.
                            var modal = $(this);
                            modal.find('.modal-body #username').val(username);
                            modal.find('.modal-body #studentId').val(button.data('id')); // Pass the student ID
                            modal.find('.modal-body input[name="isActive"][value="' + isActive + '"]').prop('checked', true);

                            // Update the form action URL
                            $('#editForm').attr('action', '/student/edit/' + encodeURIComponent(button.data('id')));
                        });

                        // Handle form submission via AJAX
                        $('#editForm').on('submit', function(event) {
                            event.preventDefault(); // Prevent the default form submission

                            var formData = $(this).serialize(); // Serialize the form data

                            $.ajax({
                                type: 'POST',
                                url: $(this).attr('action'), // Use the form's action URL
                                data: formData, // Send the serialized form data
                                success: function(response) {
                                    // Handle success - you can update the UI or show a success message
                                    if (response.success) {
                                        alert(response.message); // Show success message
                                        $('#exampleModal').modal('hide'); // Close the modal
                                        location.reload(); 
                                    }
                                },
                                error: function(xhr) {
                                    // Handle error - you can show an error message
                                    var errors = xhr.responseJSON.errors; // Get validation errors
                                    if (errors) {
                                        $.each(errors, function(key, value) {
                                            alert(value); // Show each error
                                        });
                                    } else {
                                        alert('An error occurred while updating the student information.'); // General error message
                                    }
                                }
                            });
                        });
                    });
                </script>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>











<!-- Modal Add Student -->
<div class="modal fade" id="addstudentModal" tabindex="-1" role="dialog" aria-labelledby="addstudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addstudentModalLabel">Add Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addStudentForm" method="POST" action="{{ route('student.create') }}">
                    @csrf
               
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="username">User Name</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            $('#addStudentForm').submit(function(e) {
                                e.preventDefault();

                                let formData = $(this).serialize(); 
                              console.log(formData)
                                $.ajax({
                                    type: 'POST', 
                                    url: "{{ route('student.create') }}", 
                                    data: formData,
                                    success: function(response) {
                                        alert('Student added successfully!');
                                        $('#addstudentModal').modal('hide');
                                        location.reload();
                                    },
                                    error: function(xhr, status, error) {
                                        alert('An error occurred while adding the student.');
                                        console.log(xhr.responseText);
                                    }
                                });
                            });
                        });
                    </script>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </form>
            </div>
        </div>
    </div>
</div>







<!-- Modal Add Subject -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Add Subject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addSubjectForm" method="POST" action="{{ route('subject.create') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Subject Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="passing_grade">Passing Grade</label>
                        <input type="number" class="form-control" id="passing_grade" name="pass_mark" required>
                    </div>

                <script>
                    $(document).ready(function() {
                 $('#addSubjectForm').submit(function(e) {
                     e.preventDefault();
             
                     let formData = $(this).serialize();

              $.ajax({
                  type: 'POST',
                  url: "{{ route('subject.create') }}",
                  data: formData,
                  success: function(response) {
                      alert('Subject added successfully!');
                      $('#addSubjectModal').modal('hide');
                      location.reload();
                  },
                  error: function(xhr, status, error) {
                      alert('An error occurred while adding the subject.');
                      console.log(xhr.responseText);
                  }
                 });
              });
        });

                </script>

                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </form>
            </div>
        </div>
    </div>
</div>







<!-- Modal Registr Subject -->
<div class="modal fade" id="registerSubjectModal" tabindex="-1" role="dialog" aria-labelledby="registerSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerSubjectModalLabel">Register Subject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="registerSubjectForm" method="POST" action="{{ route('subject.register') }}">
                    @csrf
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <select id="subject" name="subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="student">Student</label>
                        <select id="student" name="student_id" class="form-control" required>
                            <option value="">Select Student</option>
                        </select>
                    </div>

              <script>
                        $(document).ready(function() {
                            $('#subject').change(function() {
                                var subjectId = $(this).val();
                        
                                if (subjectId) {
                                    $.ajax({
                                        type: 'GET',
                                        url: "{{ route('students.not.registered') }}",  // مسار لجلب الطلاب
                                        data: { subject_id: subjectId },
                                        success: function(response) {
                                            $('#student').empty().append('<option value="">Select Student</option>');
                                            $.each(response.students, function(index, student) {
                                                $('#student').append('<option value="' + student.user_id + '">' + student.full_name + '</option>');
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            alert('An error occurred while fetching students.');
                                            console.log(xhr.responseText);
                                        }
                                    });
                                } else {
                                    $('#student').empty().append('<option value="">Select Student</option>');
                                }
                            });
                        
                            $('#registerSubjectForm').submit(function(e) {
                                e.preventDefault();  // منع الإرسال العادي للنموذج
                        
                                let formData = $(this).serialize();  // تجميع بيانات النموذج
                        
                                $.ajax({
                                    type: 'POST',
                                    url: $(this).attr('action'),  // استخدم URL النموذج
                                    data: formData,
                                    success: function(response) {
                                        alert('Subject registered successfully!'); 
                                        $('#registerSubjectModal').modal('hide'); 
                                        location.reload(); 
                                    },
                                    error: function(xhr, status, error) {
                                        alert('An error occurred while registering the subject.');
                                        console.log(xhr.responseText);
                                    }
                                });
                            });
                        });
            </script>
                        
                    <button type="submit" class="btn btn-primary">Register Subject</button>
                </form>
            </div>
        </div>
    </div>
</div>








<!-- Modal add mark -->
{{-- <div class="modal fade" id="addMarkModal" tabindex="-1" role="dialog" aria-labelledby="addMarkModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMarkModalLabel">Add Mark</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addMarkForm" method="POST" action="{{ route('marks.add') }}">
                    @csrf
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <select id="subjectss" name="subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="student">Student</label>
                        <select id="studentt" name="student_id" class="form-control" required>
                            <option value="">Select Student</option>
                            <!-- سيتم تحميل الطلاب بناءً على اختيار المادة -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mark">Mark</label>
                        <input type="number" class="form-control" id="mark" name="mark" required min="0" max="100">
                    </div>

                    <script>
                        $(document).ready(function() {
                            $('#subjectss').change(function() {
                                var subjectId = $(this).val();
                                if (subjectId) {
                                    $.ajax({
                                        type: 'GET',
                                        url: "{{ route('students.registered') }}", 
                                        data: { subject_id: subjectId },
                                        success: function(response) {
                                            console.log(response); 
                                            $('#studentt').empty().append('<option value="">Select Student</option>');
                                            $.each(response.students, function(index, student) {
                                                $('#student').append('<option value="' + student.user_id + '">' + student.full_name + '</option>');
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            alert('An error occurred while fetching students.');
                                            console.log(xhr.responseText);
                                        }
                                    });
                                } else {
                                    $('#student').empty().append('<option value="">Select Student</option>');
                                }
                            });

                            $('#addMarkForm').submit(function(e) {
                                e.preventDefault();

                                let formData = $(this).serialize();

                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('marks.add') }}",
                                    data: formData,
                                    success: function(response) {
                                        alert(response.message);
                                        $('#addMarkModal').modal('hide');
                                        location.reload(); // إعادة تحميل الصفحة أو يمكنك تحديث جزء منها
                                    },
                                    error: function(xhr, status, error) {
                                        alert('An error occurred while adding the mark.');
                                        console.log(xhr.responseText);
                                    }
                                });
                            });
                        });
                    </script>

                    <button type="submit" class="btn btn-primary">Add Mark</button>
                </form>
            </div>
        </div>
    </div>
</div> --}}
<!-- Modal add mark -->
<div class="modal fade" id="addMarkModal" tabindex="-1" role="dialog" aria-labelledby="addMarkModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMarkModalLabel">Add Mark</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addMarkForm" method="POST" action="{{ route('marks.add') }}">
                    @csrf
                    <div class="form-group">
                        <label for="subjectss">Subject</label>
                        <select id="subjectss" name="subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="studentt">Student</label>
                        <select id="studentt" name="student_id" class="form-control" required>
                            <option value="">Select Student</option>
                            <!-- سيتم تحميل الطلاب بناءً على اختيار المادة -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mark">Mark</label>
                        <input type="number" class="form-control" id="mark" name="mark" required min="0" max="100">
                    </div>

                    <script>
                        $(document).ready(function() {
                            $('#subjectss').change(function() {
                                var subjectId = $(this).val();
                                if (subjectId) {
                                    $.ajax({
                                        type: 'GET',
                                        url: "{{ route('students.registered') }}", 
                                        data: { subject_id: subjectId },
                                        success: function(response) {
                                            console.log(response); 
                                            $('#studentt').empty().append('<option value="">Select Student</option>');
                                            $.each(response.students, function(index, student) {
                                                $('#studentt').append('<option value="' + student.user_id + '">' + student.full_name + '</option>');
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            alert('An error occurred while fetching students.');
                                            console.log(xhr.responseText);
                                        }
                                    });
                                } else {
                                    $('#studentt').empty().append('<option value="">Select Student</option>');
                                }
                            });

                            $('#addMarkForm').submit(function(e) {
                                e.preventDefault();

                                let formData = $(this).serialize();

                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('marks.add') }}",
                                    data: formData,
                                    success: function(response) {
                                        alert(response.message);
                                        $('#addMarkModal').modal('hide');
                                        // location.reload(); 
                                    },
                                    error: function(xhr, status, error) {
                                        alert('An error occurred while adding the mark.');
                                        console.log(xhr.responseText);
                                    }
                                });
                            });
                        });
                    </script>

                    <button type="submit" class="btn btn-primary">Add Mark</button>
                </form>
            </div>
        </div>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/5r4N9A1t0vT4XkJ9T6EKDkq84TiF5lg55pNeiG6X" crossorigin="anonymous"></script>

</body>
</html>

{{-- <div class="sidebar bg-dark text-white position-fixed p-3" style="width: 400px; height: 100vh;">
    <h4 class="text-center">Active Students</h4>
    <ul class="list-group">
        @forelse($studentactive as $student)
            <li class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center">
                <span class="text-white" style="text-decoration: none;">
                    {{ $student->username }}
                </span>
                <button onclick="showAdminChat({{ $student->id }})" class="btn btn-outline-light btn-sm">Contact</button>
            </li>
        @empty
            <li class="list-group-item bg-dark text-white">No active students found.</li>
        @endforelse
    </ul>
</div> --}}





