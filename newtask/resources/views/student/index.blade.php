@extends('layout.app')

@section('title')Student Home @endsection

<style>
    body {
        background-color: #f8f9fa;
    }
    .sidebar {
        background-color: #343a40;
        color: white;
        height: 100vh;
    }
    #messageBox {
        height: 300px;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }
    #adminMessageBox{
        height: 300px !important;
        overflow-y: auto !important;

    }
</style>




<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 sidebar p-4">
            <h4>Students List</h4>
            @if(!empty($studentsInSubjects) && count($studentsInSubjects) > 0)
                <ul class="list-unstyled">
                    @foreach($studentsInSubjects as $subjectName => $students)
                        <h5>{{ $subjectName }}</h5> 
                        @foreach($students as $student)
                        @if ($student->username!=$currentUsername)
                            <li>
                                {{ $student->username }}
                                <button onclick="showChat('{{ $student->id }}', '{{ $student->username }}')" class="btn btn-link text-white">Contact</button>
                            </li>
                            @endif
                        @endforeach
                    @endforeach
                </ul>
            @else
                <p>No other students found in your subjects.</p>
            @endif

            <hr> 
            <h4>Contact Admin</h4>
            <button class="btn btn-primary" onclick="showAdminChat()">Message Admin</button>
        </div>
        
        <div class="col-md-9 content mt-5">
            <div class="container">
                <h1 class="mb-4">Welcome, {{ $otherinfo->username }}!</h1>
                <p class="lead">Email: {{ $student->email }}  </p>
                  
                <h2 class="mt-4">Your Subjects</h2>
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Subject</th>
                            <th>Pass Mark</th>
                            <th>Mark Obtained</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($subjects && count($subjects) > 0)
                            @foreach($subjects as $subject)
                                <tr>
                                    <td>{{ $subject->name}}</td>
                                    <td>{{ $subject->pass_mark }}%</td>
                                    <td>{{ $subject->mark }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center">No subjects available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div id="chatContainer" style="display: none;">
                <h2>Chat with Student : <span id="chatStudentId"></span></h2>
                <div id="messageBox">

                </div>
                <input type="text" id="messageInput" placeholder="Type your message..." class="form-control mt-2">
                <button class="btn btn-primary" onclick="hideChat()" >Back Home</button>
                <button class="btn btn-primary" onclick="send()" >Send</button>

            </div>

            <div id="adminChatContainer" class="p-4 bg-light border rounded" style="display: none;">
                <h2 class="mb-3">Chat with Admin</h2>
                <div id="adminMessageBox" class="mb-3" style="max-height: 300px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; border-radius: 5px; background-color: #f8f9fa;"></div> <!-- لعرض الرسائل -->
                
                <div class="form-group">
                    <textarea id="adminMessage" class="form-control" rows="3" placeholder="Write your message here..."></textarea>
                </div>
            </div>
        </div>
    </div>
</div>


  




<script>
        let pub_receiver_id;
        let pub_sender_id = {{ $student->id }};
        function showChat(studentId, studentUsername) {
            pub_receiver_id = studentId;
      
         const currentUserId = {{ $student->id }}; // معرف المستخدم الحالي
         document.querySelector('.content .container').style.display = 'none';
         document.getElementById('chatContainer').style.display = 'block';
         document.getElementById('chatStudentId').innerText = studentUsername;
         document.getElementById('adminChatContainer').style.display = 'none';
       
     
        
              // جلب الرسائل القديمة باستخدام AJAX
              fetch(`/messages/${currentUserId}/${studentId}`)
                  .then(response => response.json())
                  .then(messages => {
                      const messageBox = document.getElementById('messageBox');
                      messageBox.innerHTML = '';
                      if (messages.length === 0) {
            // إذا لم توجد رسائل، أضف رسالة توضح ذلك
                    const noMessagesElement = document.createElement('p');
                    noMessagesElement.innerText = "dont have any message.";
                    messageBox.appendChild(noMessagesElement);
                    }else{
                      messages.forEach(message => {
                          const messageElement = document.createElement('p');
                          messageElement.innerText = `${message.sender_id === currentUserId ? 'You' : 'Student ' + message.sender_id}: ${message.message}`;
                          messageBox.appendChild(messageElement);
          
                          const separator = document.createElement('hr');
                          messageBox.appendChild(separator);
                        });
                    }
                }) 
        }
  
    
        function hideChat() {
            document.querySelector('.content .container').style.display = 'block'; // إظهار المحتوى
            document.getElementById('chatContainer').style.display = 'none'; // إخفاء حاوية الدردشة
            document.getElementById('adminChatContainer').style.display = 'none';
        }
    

        function send() {
           const messageInput = document.getElementById('messageInput');
           const message = messageInput.value;
       
           const receiver_id = pub_receiver_id; 
           const sender_id = {{ $student->id  }};   
       
           console.log("receiver_id : " + receiver_id);
           console.log("sender_id : " + sender_id);
           console.log("message : " + message);
       
           fetch('/messages/store', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ message, receiver_id, sender_id })
           })
         .then(response => {
            if (!response.ok) {
                  throw new Error('Network response was not ok ' + response.statusText);
              }
              return response.json();
          })
          .then(data => {
              const messageBox = document.getElementById('messageBox');
              const messageElement = document.createElement('p');
              messageElement.innerText = `You: ${message}`;
              messageBox.appendChild(messageElement);
              const separator = document.createElement('hr');
              messageBox.appendChild(separator);
              messageInput.value = '';
          })
          .catch(error => {
              console.error('Error:', error);
          });
  
  
        
    }

</script>
    