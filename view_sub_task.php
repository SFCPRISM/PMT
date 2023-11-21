<?php 
  session_start();
include 'db_connect.php';
$task_id = isset($_GET['tid']) ? $_GET['tid'] : null;
$type = isset($_GET['pid']) ? $_GET['pid'] : null;
$pid = isset($_GET['p_id']) ? $_GET['p_id'] : null;
if($type == 2){
  if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT stl.*, pl.manager_id, user.firstname, user.lastname, concat(user.firstname,' ',user.lastname) as username, tester.firstname, tester.lastname, concat(tester.firstname,' ',tester.lastname) as testername 
                        FROM sub_task_list stl 
                        INNER JOIN project_list pl ON stl.project_id = pl.id
						            INNER JOIN users user ON stl.user_id = user.id 
                        INNER JOIN users tester ON stl.tester_id = tester.id 
                        WHERE stl.id = ".$_GET['id'])->fetch_array();

    foreach ($qry as $k => $v) {
        $$k = $v;
    }
  }
}else{
  if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT stl.*, pl.manager_id, user.firstname, user.lastname, concat(user.firstname,' ',user.lastname) as username 
                        FROM sub_task_list stl 
                        INNER JOIN project_list pl ON stl.project_id = pl.id
						            INNER JOIN users user ON stl.user_id = user.id 
                        WHERE stl.id = ".$_GET['id'])->fetch_array();

    foreach ($qry as $k => $v) {
        $$k = $v;
    }
  }
}

$tasks = $conn->query("SELECT * FROM task_list ")->fetch_all(MYSQLI_ASSOC);
$allusers = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 3")->fetch_all(MYSQLI_ASSOC);
$users_detail = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users")->fetch_all(MYSQLI_ASSOC);
$projects = $conn->query("SELECT * FROM project_list where id = $project_id")->fetch_all(MYSQLI_ASSOC);
$user_ids_array = array();
foreach ($projects as $item) {
    $user_ids = explode(',', $item['user_ids']);
    $user_ids[] = $item['manager_id'];
    $user_ids[] = $item['guest_id'];
    $user_ids[] = $item['tech_lead_id']; 
    $user_ids[] = $item['team_lead'];// Add manager_id to the user_ids array
    $user_ids_array[] = $user_ids;
}

$userList = array();

// Iterate through $users_detail
foreach ($users_detail as $user) {
    // Check if the user's 'id' exists in $user_ids_array
    if (in_array($user['id'], $user_ids_array[0])) {
        // Add the user to the filtered $userList array
        $userList[$user['firstname']] = $user['name'];
    }
}
//$manager = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type IN (2, 4)")->fetch_all(MYSQLI_ASSOC);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-7">
        <dl>
          <dd>
          
            <b><?php echo ucwords($sub_task) ?></b>
          </dd>
        </dl>
        <dl>
					<dt><b class="border-bottom border-primary">Description</b></dt>
					<dd>
            <?php echo html_entity_decode($description) ?>
          </dd>
				</dl>
        <br>
        <br>

        <dl>
					<dt><b class="border-bottom border-primary">Estimated Time(Development)</b></dt>
          <dd>
             <?php echo isset($estimated_time) ? html_entity_decode($estimated_time) . ' hrs' : '0 hrs'; ?>
             
        </dd>
				</dl>
        <?php if($_SESSION['login_type'] != 3){?>
          <dl>
            <dt><b class="border-bottom border-primary">Estimated Time(Testing)</b></dt>
            <dd><?php echo html_entity_decode($testing_time) ?> hrs</dd>
          </dl>
          <?php }?>
              <dl>
                <dt><b>Created At : </b></dt>
               <dd> <p><?php echo $Created_At ?></dd>
        </dl>
        <dl>
                <dt><b>Updated At : </b></dt>
               <dd> <p><?php echo $Updated_At ?></dd>
        </dl>
            

        
</div>
   <div class="col-5">
          <div class="task_detail_section">
            <div class="task_detail_section_inner">		    
				<p>	<b>Developer:</b></p>
					<p><?php echo html_entity_decode($username) ?></p>
        </div>
        
       <div class="task_detail_section_inner">
        <?php if($type == 2){ ?> 
          <p><b>Tester:</b></p>
           <p> <?php echo html_entity_decode($testername) ?></p>
        <?php  } ?>
        </div>
        <div class="task_detail_section_inner">
        <p><b>Status:</b></p> 
             <p> <?php 
             if ($status == 1) {
                echo "<span class='badge badge-secondary'>Pending</span>";
              } elseif ($status == 2) {
                echo "<span class='badge badge-primary'>In-Progress</span>";
              } elseif ($status == 3) {
                echo "<span class='badge badge-success'>Done</span>";
              } elseif ($status == 4) {
                echo "<span class='badge badge-warning'>Testing</span>";
              } elseif ($status == 5) {
                echo "<span class='badge badge-warning'>On-Hold</span>";
              } elseif ($status == 6) {
                echo "<span class='badge badge-info'>Ready To Deploy</span>";
              }
              ?>
              </p>
            </div>
            <div class="task_detail_section_inner">
					<p><b>Start Date :</b></p>
					<p><?php echo date("F d, Y",strtotime($start_date)) ?></p>
				</dl>
        </dl>
            </div>
            <div class="task_detail_section_inner">
				<p>	<b>End Date :</b></p>
					<p><?php echo date("F d, Y",strtotime($end_date)) ?></p>
            </div>
        <div class="task_detail_section_inner">
        
            <p><b>Actual End Date :</b></p>
            <p><?php
                if ($actual_end_date != '0000-00-00 00:00:00') {
                  echo date("F d, Y", strtotime($actual_end_date));
                } else {
                  echo "&nbsp;"; // Display a non-breaking space to ensure the HTML element is rendered.
                }
                ?>
                </p>
         </div>
         </div>

				<div class="col-12">
			
				
				
        
				</div>
			</div>
				
   
			<?php
				foreach($tasks as $task)
                {
                    if($task_id == $task['id'])
				    {
                        echo '<dd><a class="dropdown-item view_task badge badge-success" href="javascript:void(0)" data-id="'.$task['id'].'" data-task="'.$task['task'].'">'.$task['task']. '</a></dd>';
                    }
                }
                
                
				?>
			
		</dl>
        </div>
    </div>	
	<div class="attachment_comment">
        <div class="attachment_comment_inner">
          <a href="javascript:void(0)" class="files" id="files" onclick="showFiles()" >Files </a>
          <a href="javascript:void(0)" class="Comments"  id="Comments"   onclick="showComments()" >Comments </a>
          <a href="javascript:void(0)" class="activity displaynone" id="activity" onclick="showActivity()">Activity </a>
        </div>
        <div class="attachment_comment_inner_wrap displaynone">
          <div class="fileinner displaynone">
            <div class="uploadattchment">
              <label>Attach file</label>
              <form enctype="multipart/form-data">
                <input type ="file" name ="url" id="url"  />
                <input type="hidden" name="task_id" id="task_id" value="<?php echo ($id); ?>" />
                <input type="hidden" name="user_id" id="user_id" value="<?php echo ($_SESSION['login_id']); ?>" />
              </form>
            </div>
              <div class="attchment_details_subtask">
              </div>
          </div>
          <div class="commentsinner  displaynone" id="commentsinner">
            <div class="uploadattchment">
              <textarea id="comment" name="comment" rows="2" cols="50"> </textarea>
              <div id="suggestionBox" style="display: none;"></div>
              <div class="comments_button" id="comments_button">
                <button id="savecomment">save</button>
                <button id="canclecomment">cancel</button>
             </div>
              <input type="hidden" id="selectedUsername" name="selectedUsername" value="">
                <input type="hidden" name="task_id" id="task_id" value="<?php echo ($id); ?>" />
              <input type="hidden" name="user_id" id="user_id" value="<?php echo ($_SESSION['login_id']); ?>" />
            </div>
            <div class="comment_details_subtask">
              
            </div>
          </div>
          <div class="activityinner displaynone">
            <div class="uploadattchment">
              <label>Upload act</label>
              <input type ="file" name ="attchment" id="attchment" />
            </div>
            <div class="attchment_details">
              
            </div>
          </div>
        </div>
  </div>
</div>

<script>
$('.view_task').click(function(){
    var pid = <?php echo $pid ?>;
		uni_modal("Task Details","view_task.php?pid=" + pid + "&id="+$(this).attr('data-id'),"mid-large")
	})
window.onload = function() {
  getattachment();
};



document.getElementById('url').addEventListener('change', function() {
  var fileInput = this;
  var file = fileInput.files[0];
  var fileName = file.name;
  var fileType = file.type;
  var task_id = document.getElementById('task_id').value;
  var user_id = document.getElementById('user_id').value;
  var formData = new FormData();
  formData.append('file', file);
  formData.append('task_id', task_id);
  formData.append('user_id', user_id);

  $.ajax({
    url: 'ajax.php?action=upload_attchament',
    data: formData, // Corrected the usage of the FormData object here
    cache: false,
    contentType: false,
    processData: false,
    method: 'POST',
    type: 'POST',
    success: function(resp) {
      if (resp == 1) {
        getattachment();
        alert_toast('Data successfully saved', "success");
        setTimeout(function() {
        	    }, 1500);
      }
    }
  });
});

function getattachment() {
  var task_id = document.getElementById('task_id').value;
  var user_id = document.getElementById('user_id').value;
  if (task_id != '') {
    $.ajax({
      url: 'ajax.php?action=get_attchament',
      data: { task_id: task_id },
      method: 'POST',
      type: 'POST',
      dataType: 'json', // Set the expected response data type to JSON
      success: function (resp) {
        var attachments = resp;
        var attdata = '';
        attachments.forEach(function (attachment) {
          var fileExtension = attachment.url.split('.').pop().toLowerCase();
          if (fileExtension === 'png' || fileExtension === 'jpg' || fileExtension === 'jpeg' || fileExtension === 'gif') {
            attdata += '<p><a href="/assets/attachment/' + attachment.url + '" target="_blank"><img src="/assets/attachment/' + attachment.url + '" style="height:12vh; width:149px; border:1px solid #c4c4c4;" alt="Uploaded File"></a></p><p>Uploaded By : '+attachment.user_name+'</p>';
          } else if (fileExtension === 'pdf') {
            attdata += '<p><a href="/assets/attachment/' + attachment.url + '" target="_blank">View Pdf</a></p><p>Uploaded By : '+attachment.user_name+'</p>';

          } else {
            attdata += '<p><a href="/assets/attachment/' + attachment.url + '" target="_blank">View Doc</a></p><p>Uploaded By : '+attachment.user_name+'</p>';

          }
         if(user_id == attachment.user_id){
          attdata += '<div class="action_container_comment"><p onclick="deletefile(this)" data-attachment-list-id="' + attachment.id + '">Delete</p></div>';

         }
        });

        // Update the HTML content of the '.attchment_details' div with the generated attachment data
        var attachmentDetailDiv = document.querySelector('.attchment_details_subtask');
        attachmentDetailDiv.innerHTML = attdata;
        document.getElementById('url').value = '';
      }
    });
  }
}

function deletefile(element) {
  var id = element.dataset.attachmentListId;
  if(id != ''){
    $.ajax({
      url: 'ajax.php?action=delete_attchament',
      data: {id:id},
      method: 'POST',
      type: 'POST',
      success: function(resp) {
        if(resp == 1){
          getattachment();
					alert_toast('file successfully deleted',"success");
					setTimeout(function(){
						//location.reload()
            
					},1500)
         
				}
      }
    });
    }
}

function deletecomment(element) {
  var id = element.dataset.attachmentListId;
  console.log('attachment_list_id:', id);
  if(id != ''){
    $.ajax({
      url: 'ajax.php?action=delete_comment',
      data: {id:id},
      method: 'POST',
      type: 'POST',
      success: function(resp) {
        if(resp == 1){
          getattachment();
					alert_toast(' successfully deleted',"success");
					setTimeout(function(){
						//location.reload()
            
					},1500)
          getcomment();
         
				}
      }
    });
    }
}

document.getElementById('savecomment').addEventListener('click', function() {
  var commentValue = document.getElementById('comment').value;
  var task_id = document.getElementById('task_id').value;
  var user_id = document.getElementById('user_id').value;
  var selectedUsername = $("#selectedUsername").val();
  var formData = new FormData();
  formData.append('comment', commentValue);
  formData.append('task_id', task_id);
  formData.append('user_id', user_id);
  formData.append('tagged_users', selectedUsername); 
  if(commentValue == ''){
    alert_toast('comment field is blank', "warning");
    setTimeout(function() {
          }, 1500);
  }else{
  $.ajax({
    url: 'ajax.php?action=save_comment',
    data: formData, // Corrected the usage of the FormData object here
    cache: false,
    contentType: false,
    processData: false,
    method: 'POST',
    type: 'POST',
    success: function(resp) {
      if (resp != '') {
        getattachment();
        alert_toast('Data successfully saved', "success");
        setTimeout(function() {
        	    }, 1500);
              document.getElementById('comment').value = '';
              getcomment();
              $("#taggedUsersList").empty(); // Clear the tagged users list after submitting the comment
              $("#selectedUsername").val("");

      }
    }
  });
}
});

$(document).ready(function() {
  // Hide the save comment button initially
  $('#savecomment').hide();
  $('#canclecomment').hide();
  // Listen for input changes in the textarea
  $('#comment').on('input', function() {
    // Check if the comment is blank
    if ($(this).val().trim() === '') {
      $('#savecomment').hide(); // Hide the save button
      $('#canclecomment').hide(); // Hide the comment button
    } else {
      $('#savecomment').show(); // Show the save button
      $('#canclecomment').show(); // Show the comment button
    }
  });
});

document.getElementById('canclecomment').addEventListener('click', function() {
  // Clear the content of the textarea by setting its value to an empty string
  document.getElementById('comment').value = '';
  $('#suggestionBox').hide();
  $('#comments_button').hide();

});

function getcomment() {
  var task_id = document.getElementById('task_id').value;
  var user_id = document.getElementById('user_id').value;
  if (task_id != '') {
    $.ajax({
      url: 'ajax.php?action=get_comment',
      data: { task_id: task_id },
      method: 'POST',
      type: 'POST',
      dataType: 'json', // Set the expected response data type to JSON
      success: function (resp) {
        // Assuming the server returns the attachment data in the format { "data": [attachment1, attachment2, ...] }
        var comments = resp;
        var attdata = '';
      
        comments.forEach(function (comment) {
          // Create a textarea for each comment
          if (user_id == comment.user_id) {

            attdata += '<div contenteditable="true" id="commentId_'+comment.id+'" class="editable-input" oninput="handleInputChange(event)" data-attachment-list-id="' + comment.id + '">'+comment.comment +'</div><div class="update_comment_button displaynone"><button id="updatecomment_'+comment.id+'" onclick="updatecomment(this)" data-attachment-list-id="' + comment.id + '">save</button><button id="canclecommentupdate_'+comment.id+'" onclick="cancleupdaecomment(this)" data-attachment-list-id="' + comment.id + '">cancel</button></div><p>Commented By: '+comment.user_name+'</p><p><span>Created At :'+comment.created_at+'</span><span>Updated At :'+comment.updated_at+'</span></p>';      

            attdata += '<div class="action_container_comment"><p onclick="deletecomment(this)" data-attachment-list-id="' + comment.id + '">Delete</p></div>';
          
          }else {
            attdata += '<textarea disabled id="comment" name="comment" rows="2" cols="50" >' + comment.comment + '</textarea><div class="update_comment_button displaynone"> <button id="savecomment">save</button><button id="canclecomment">cancle</button></div> <p>Commented By : '+comment.user_name+'</p><p><span>Created At :'+comment.created_at+'</span><span>Updated At :'+comment.updated_at+'</span></p>';      

          }
          
        });
            var commentContainerDiv = document.querySelector('.comment_details_subtask');
        commentContainerDiv.innerHTML = attdata;
      }
      
    });
  }
}

function handleInputChange(event) {

  const editedText = event.target.textContent;
  const commentID = event.target.dataset.attachmentListId;

 
  const updateButtonDiv = document.querySelector("#updatecomment_" + commentID).parentNode;
  updateButtonDiv.classList.remove("displaynone");

}

function updatecomment(element){
  var commentID = element.dataset.attachmentListId;
  var comment = document.getElementById('commentId_' + commentID).textContent;
  var currentDate = new Date();
  var day = currentDate.getDate();
  var month = currentDate.getMonth() + 1; // Months are zero-indexed, so we add 1
  var year = currentDate.getFullYear();
  var hours = currentDate.getHours();
  var minutes = currentDate.getMinutes();
  var seconds = currentDate.getSeconds();

  // Format the date and time with leading zeroes
  var formattedDate = year + '-' + ('0' + month).slice(-2) + '-' + ('0' + day).slice(-2);
  var formattedTime = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2);

  // Combine the date and time strings
  var formattedDateTime = formattedDate + ' ' + formattedTime;
  var formData = new FormData();
  formData.append('id', commentID);
  formData.append('comment', comment);
  formData.append('updated_at',formattedDateTime);
	$.ajax({
    url:'ajax.php?action=save_comment',
    data: formData, // Corrected the usage of the FormData object here
    cache: false,
    contentType: false,
    processData: false,
    method: 'POST',
    type: 'POST',
   success:function(resp){
    if(resp == 1){
      alert_toast('Data successfully saved',"success");
      setTimeout(function(){
      },1500)
      getcomment();
    }
  }
  })


}
 function cancleupdaecomment(element){
getcomment();
}

$("#comment").on("input", function() {
  $('#comments_button').show();
  const userList = <?php echo json_encode($userList); ?>;
            const commentText = $(this).val();
            const atPosition = commentText.lastIndexOf('@');
            if (atPosition !== -1) {
                const searchTerm = commentText.substring(atPosition + 1);
                const suggestionBox = $("#suggestionBox");
                const suggestionList = Object.keys(userList).filter(username => username.includes(searchTerm));

                if (suggestionList.length > 0) {
                    const suggestionHTML = suggestionList.map(username => `<div class="suggestionItem">${userList[username]}</div>`).join('');
                    suggestionBox.html(suggestionHTML);
                    suggestionBox.css({
                        top: $(this).offset().top + $(this).outerHeight() + 5,
                        left: $(this).offset().left,
                        width: $(this).outerWidth(),
                    });
                    suggestionBox.show();
                } else {
                    suggestionBox.hide();
                }
            } else {
                $("#suggestionBox").hide();
            }
        });

        // Function to handle user selection from the suggestion box
        $("#suggestionBox").on("click", ".suggestionItem", function() {
            const selectedUsername = $(this).text();
            const currentComment = $("#comment").val();
            const atPosition = currentComment.lastIndexOf('@');
            const newComment = currentComment.substring(0, atPosition) + '@' + selectedUsername + ' ' + currentComment.substring(atPosition + selectedUsername.length + 2);
            $("#comment").val(newComment);
            $("#taggedUsersList").append(`<span class="taggedUser">@${selectedUsername}</span>`);
            $("#suggestionBox").hide();
            $("#selectedUsername").val(selectedUsername);
        });

        // Fetch and display comments on page load
        getcomment(); 
</script>