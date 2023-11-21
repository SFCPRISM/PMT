<?php 
  session_start();
  include 'db_connect.php';
  $project = $conn->query("SELECT project_list.type FROM project_list WHERE project_list.id =".$_GET['pid'])->fetch_array();
  $project_id = isset($_GET['pid']) ? $_GET['pid'] : null;
  $projectType = $project['type'];
  if($projectType == 2){
    if (isset($_GET['id'])) {
      $qry = $conn->query("SELECT tl.*, pl.manager_id, user.firstname, user.lastname, concat(user.firstname,' ',user.lastname) as username, tester.firstname, tester.lastname, concat(tester.firstname,' ',tester.lastname) as testername
                          FROM task_list tl 
                          INNER JOIN project_list pl ON tl.project_id = pl.id 
                          INNER JOIN users user ON tl.user_id = user.id 
                          INNER JOIN users tester ON tl.tester_id = tester.id 
                          WHERE tl.id = ".$_GET['id'])->fetch_array();

      foreach ($qry as $k => $v) {
          $$k = $v;
      }
    }
  }else{
    if (isset($_GET['id'])) {
      $qry = $conn->query("SELECT tl.*, pl.manager_id, user.firstname, user.lastname, concat(user.firstname,' ',user.lastname) as username
                          FROM task_list tl 
                          INNER JOIN project_list pl ON tl.project_id = pl.id 
                          INNER JOIN users user ON tl.user_id = user.id 
                          WHERE tl.id = ".$_GET['id'])->fetch_array();

      foreach ($qry as $k => $v) {
          $$k = $v;
      }
    }
  }
  $subtasks = $conn->query("SELECT * FROM sub_task_list WHERE task_id =" .$_GET['id'])->fetch_all(MYSQLI_ASSOC);
  $allusers = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 3")->fetch_all(MYSQLI_ASSOC);
  //print_r($allusers);die;
  $users_detail = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users")->fetch_all(MYSQLI_ASSOC);
  $projects = $conn->query("SELECT * FROM project_list where id = $project_id")->fetch_all(MYSQLI_ASSOC);
  $user_ids_array = array();
  foreach ($projects as $item) {
      $user_ids = explode(',', $item['user_ids']);
      $manager_ids = explode(',', $item['manager_id']);
      $guest_ids = explode(',', $item['guest_id']);
      $tech_lead_ids = explode(',', $item['tech_lead_id']);
      $team_lead_ids = explode(',', $item['team_lead']);
  
      // Merge all user IDs arrays into a single array
      $user_ids_array = array_merge($user_ids_array, $user_ids, $manager_ids, $guest_ids, $tech_lead_ids, $team_lead_ids);
  }
  
  $userList = array();
  
  // Iterate through $users_detail
  foreach ($users_detail as $user) {
      if (in_array($user['id'], $user_ids_array)) {
          $userList[$user['firstname']] = $user['name'];
      }
  }
  
  // Validate the $userList array as needed.
  


  // Print the filtered $userList
//$manager = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type IN (2, 4)")->fetch_all(MYSQLI_ASSOC);

$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'];

// Assuming $attachment_name contains the file name, e.g., "1689070080_IMG-5614.jpg"
$attachment_url = $base_url . "/assets/attachment/" . $attachment_name;
?>
<div class="container-fluid">
      <div class="row">
        <div class="col-7">
          <dl>
            <dd>
            <b> <?php echo ucwords($task) ?></b>
            </dd>
          </dl>
          <dl>
            <dt><b class="border-bottom border-primary">Description</b></dt>
            <dd> 
              <?php echo html_entity_decode($description) ?>
            </dd>
          </dl>
          <dl>
            <dt><b class="border-bottom border-primary">Attachment :</b></dt>
            <dd>
              <?php
						$file_extension = strtolower(pathinfo($attachment_name, PATHINFO_EXTENSION));
						if (in_array($file_extension, array('png', 'jpg', 'jpeg', 'gif'))) {
						// Display as an image for image file types
						echo '<a href="' . $attachment_url . '" target="_blank">';
						echo '<img src="' . $attachment_url . '" alt="Attachment" width="80" height="80" style="border:1px solid #000;" />';
						echo '</a>';
						} elseif ($file_extension == 'pdf') {
						// Display as a link for PDF files
						echo '<a href="' . $attachment_url . '" target="_blank">View PDF</a>';
						} else {
						// Display as a link for other file types
						echo '<a href="' . $attachment_url . '" target="_blank">' . $attachment_name . '</a>';
						}
						?>
          </dl>
          <div class="task_detail">
            <div class="task_detail_left">
              <div class="task_detail_left_inner">
                <p><b>Development Estimation :</b> </p>
                <p><?php echo ($estimated_time ? $estimated_time  : ' 0');  ?> Hrs</p>

              </div>
              <div class="task_detail_left_inner">
                <p><b>Deployment Estimation :</b> </p>
                <p><?php echo ($Deployment_Estimation ? $Deployment_Estimation : '0'); ?> Hrs</p>
              </div>
              <div class="task_detail_left_inner">
                <p><b>Created At :</b> </p>
                <p><?php echo $Created_At ?></p>
              </div>
              <div class="task_detail_left_inner">
                <p><b>Updated At : </b></p>
                <p><?php echo $Updated_At ?></p>
              </div>
            </div>
            <div class="task_detail_right">
              <div class="task_detail_right_inner">
                <p><b>Testing Estimation :</b> </p>
                <p><?php echo ($testing_time ? $testing_time : '0') ?> Hrs</p>
              </div>
              <div class="task_detail_right_inner">
                <p><b>Design Estimation :</b> </p>
                <p><?php echo ($Design_Estimation ? $Design_Estimation : '0') ?> Hrs</p>
              </div>
              <div class="task_detail_right_inner">
                <p><b>Total Estimation :</b> </p>
                <p><?php $total_time = 0;
                  if (is_numeric($Deployment_Estimation)) {
                      $total_time += $Deployment_Estimation;
                  }
                  if (is_numeric($estimated_time)) {
                      $total_time += $estimated_time;
                  }
                  if (is_numeric($testing_time)) {
                      $total_time += $testing_time;
                  }
                  echo $total_time . ' Hrs';
                  ?>
                </p>
              </div>
              
            </div>
          </div>
        </div>
        <div class="col-5">
          <div class="task_detail_section">
            <div class="task_detail_section_inner">
              <p><b>Developer : </b></p>
              <p><?php echo $username ?></p>
            </div>
            <div class="task_detail_section_inner">
              <?php if($projectType == 2) { ?>
              <p><b>Tester : </b></p>
              <p><?php echo $testername ?></p>
              <?php } ?>
            </div>
            <div class="task_detail_section_inner">
              <p><b>Status : </b></p>
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
              <p><b>Priority : </b></p>
              <p> <?php 
							  if ($priority == 1) {
								echo "<span class='badge badge-danger'>High</span>";
							  } elseif ($priority == 2) {
								echo "<span class='badge badge-primary'>Medium</span>";
							  } elseif ($priority == 3) {
								echo "<span class='badge badge-success'>Low</span>";
							  }
							  ?>
              </p>
            </div>
            <div class="task_detail_section_inner">
              <p><b>Task Type : </b></p>
              <p> <?php 
                if ($task_type == 1) {
                  echo "<span>Story</span>";
                } else {
                  echo "<span>Bug</span>";
                }
                ?>
              </p>
            </div>
            <div class="task_detail_section_inner">
              <p><b>Start Date : </b></p>
              <p> <?php  echo date("F d, Y", strtotime($start_date));  ?>
              </p>
            </div>
            <div class="task_detail_section_inner">
              <p><b>End Date : </b></p>
              <p> <?php  echo date("F d, Y", strtotime($end_date));  ?>
              </p>
            </div>
            <div class="task_detail_section_inner">
              <p><b> Actual End Date : </b></p>
              <p>  <?php
								if ($actual_end_date != '0000-00-00 00:00:00') {
								  echo date("F d, Y", strtotime($actual_end_date));
								} else {
								  echo "&nbsp;"; // Display a non-breaking space to ensure the HTML element is rendered.
								}
								?>
              </p>
            </div>
          </div>

        </div>
      </div>
  <?php
  $subtaskCount = count($subtasks);
  if($subtaskCount > 0){
  ?>
  <div class="row">
    <div class="col-12">
      <div class="card card-outline card-primary">
      <div class="card-header">
        <span><b>Sub Task List:</b></span>
        <?php if($_SESSION['login_type'] != 3 || $_SESSION['login_type'] != 5): ?>
        <div class="card-tools">
        </div>
        <?php endif; ?>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table tabe-hover table-condensed" id="list">
          <colgroup>
          <col width="5%">
          <col width="20%">
          <col width="25%">
          <col width="20%">
          <col width="12%">
          <?php if($_SESSION['login_type'] != 6){ ?>
          <col width="12%">
          <?php } ?>
          </colgroup>
          <thead>
          <th>#</th>
          <th>Title</th>
          <th>Description</th>
          <th style="white-space: nowrap;">Estimated Time</th>
          <th>Status</th>
          <?php if($_SESSION['login_type'] != 6){ ?>
          <th>Action</th>
          <?php } ?>
          </thead>
          <tbody>
          <?php 
                  $i = 1;
                  $sessionId = $_SESSION['login_id'];
                  if($_SESSION['login_type'] == 3 || $_SESSION['login_type'] == 5 || $_SESSION['login_type'] == 8){
                    $tasks = $conn->query("SELECT *, estimated_time as Estimated_time FROM sub_task_list WHERE task_id = {$id} ORDER BY sub_task ASC");
                  }
                  else {
                    $tasks = $conn->query("SELECT *, estimated_time as Estimated_time FROM sub_task_list WHERE task_id = {$id} ORDER BY sub_task ASC");
                  }
                  while($row=$tasks->fetch_assoc()):
                    $trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
                    unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                    $desc = strtr(html_entity_decode($row['description']),$trans);
                    $desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
                  ?>
          <tr>
            <td class="text-center">
            <?php echo $i++ ?>
            </td>
            <td class=""><a class="dropdown-item view_sub_task" href="javascript:void(0)"
              data-id="<?php echo $row['id'] ?>" data-task="<?php echo $row['sub_task'] ?>"><b>
              <?php echo ucwords($row['sub_task']) ?>
              </b></a></td>
            <td class="">
            <p class="truncate">
              <?php echo strip_tags($desc) ?>
            </p>
            </td>
            <td class=""><b>
              <?php echo isset($Estimated_time) ? html_entity_decode($Estimated_time) . ' hrs' : '0 hrs'; ?>
            </b></td>
            <td>
            <?php 
                      if($row['status'] == 1){
                      echo "<span class='badge badge-secondary'>Pending</span>";
                      }elseif($row['status'] == 2){
                      echo "<span class='badge badge-primary'>On-Progress</span>";
                      }elseif($row['status'] == 3){
                      echo "<span class='badge badge-success'>Done</span>";
                      }elseif($row['status'] == 4){
                      echo "<span class='badge badge-warning'>Testing</span>";
                      }elseif($row['status'] == 5){
                      echo "<span class='badge badge-warning'>On-Hold</span>";
                      }elseif($row['status'] == 6){
                      echo "<span class='badge badge-info'>Ready To Deploy</span>";
                      }
                      ?>
            </td>
            <td class="text-center">
            <?php if($_SESSION['login_type'] != 6){ ?>
            <button type="button"
              class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle"
              data-toggle="dropdown" aria-expanded="true">
              Action
            </button>
            <?php } ?>
            <div class="dropdown-menu" style="">
              <a class="dropdown-item view_sub_task" href="javascript:void(0)"
              data-id="<?php echo $row['id'] ?>" data-task="<?php echo $row['sub_task'] ?>">View</a>
              <div class="dropdown-divider"></div>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item edit_sub_task" href="javascript:void(0)"
              data-id="<?php echo $row['id'] ?>" data-task="<?php echo $row['sub_task'] ?>">Edit</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item " href="javascript:void(0)" onclick="delete_sub_task(this)"
              data-id="<?php echo $row['id'] ?>">Delete</a>
            </div>
            </td>
          </tr>
    
    
          <?php 
                  endwhile;
                  ?>
          </tbody>
        </table>
        </div>
      </div>
      </div>
    </div>
  </div>
  <?php } ?>
  <div class="attachment_comment">
    <div class="attachment_comment_inner">
      <a href="javascript:void(0)" class="files" id="files" onclick="showFiles()">Files </a>
      <a href="javascript:void(0)" class="Comments" id="Comments" onclick="showComments()">Comments </a>
      <a href="javascript:void(0)" class="activity displaynone" id="activity" onclick="showActivity()">Activity </a>
    </div>
    <div class="attachment_comment_inner_wrap displaynone">
      <div class="fileinner displaynone">
        <div class="uploadattchment">
          <label>Attach file</label>
          <form enctype="multipart/form-data">
            <input type="file" name="url" id="url" />
            <input type="hidden" name="task_id" id="task_id" value="<?php echo ($id); ?>" />
            <input type="hidden" name="user_id" id="user_id" value="<?php echo ($_SESSION['login_id']); ?>" />
          </form>
        </div>
        <div class="attchment_details">
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
        <div class="comment_details">
        </div>
      </div>
      <div class="activityinner displaynone">
        <div class="uploadattchment">
          <label>Upload act</label>
          <input type="file" name="attchment" id="attchment" />
        </div>
        <div class="attchment_details">
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('#list').dataTable()
    $('.new_productivity').click(function () {
      uni_modal("<i class='fa fa-plus'></i> New Progress for: " + $(this).attr('data-task'), "manage_progress.php?pid=" + $(this).attr('data-pid') + "&tid=" + $(this).attr('data-tid'), 'large')
    })
  })

var pid = <?php echo json_encode($projectType); ?>;
  $('.view_sub_task').click(function () {
    var tid = <?php echo $id ?>;
    var p_id = <?php echo $project_id ?>;
    var dataId = $(this).attr('data-id');
    uni_modal_view("Sub Task Details", "view_sub_task.php?p_id=" + p_id + "&pid=" + pid + "&tid=" + tid + "&id=" + dataId, "mid-large");
  })

  $('.sub_task').click(function () {
    uni_modal("New Sub Task: " + $(this).attr('data-task'), "sub_task.php?pid=<?php echo $id ?>&id=" + $(this).attr('data-id'), "mid-large")
  })
  $('.edit_sub_task').click(function () {
    uni_modal("Edit Sub Task: " + $(this).attr('data-task'), "sub_task.php?pid=<?php echo $id ?>&id=" + $(this).attr('data-id') + "&event=edit", "mid-large");
  })


  window.onload = function () {
    getattachment();

  };




  document.getElementById('url').addEventListener('change', function () {
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
      success: function (resp) {
        if (resp == 1) {
          getattachment();
          alert_toast('Data successfully saved', "success");
          setTimeout(function () {
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
              attdata += '<p><a href="/assets/attachment/' + attachment.url + '" target="_blank"><img src="/assets/attachment/' + attachment.url + '" style="height:12vh; width:149px; border:1px solid #c4c4c4;" alt="Uploaded File"></a></p><p>Uploaded By : ' + attachment.user_name + '</p>';
            } else if (fileExtension === 'pdf') {
              attdata += '<p><a href="/assets/attachment/' + attachment.url + '" target="_blank">View Pdf</a></p><p>Uploaded By : ' + attachment.user_name + '</p>';

            } else {
              attdata += '<p><a href="/assets/attachment/' + attachment.url + '" target="_blank">View Doc</a></p><p>Uploaded By : ' + attachment.user_name + '</p>';

            }
            if (user_id == attachment.user_id) {
              attdata += '<div class="action_container_comment"><p onclick="deletefile(this)" class="delete_button" data-attachment-list-id="' + attachment.id + '">Delete</p></div>';

            }
          });

          // Update the HTML content of the '.attchment_details' div with the generated attachment data
          var attachmentDetailDiv = document.querySelector('.attchment_details');
          attachmentDetailDiv.innerHTML = attdata;
          document.getElementById('url').value = '';
        }
      });
    }
  }

  function deletefile(element) {
    var id = element.dataset.attachmentListId;
    if (id != '') {
      $.ajax({
        url: 'ajax.php?action=delete_attchament',
        data: { id: id },
        method: 'POST',
        type: 'POST',
        success: function (resp) {
          if (resp == 1) {
            getattachment();
            alert_toast('file successfully deleted', "success");
            setTimeout(function () {
              //location.reload()

            }, 1500)

          }
        }
      });
    }
  }

  function deletecomment(element) {
    var id = element.dataset.attachmentListId;
    console.log('attachment_list_id:', id);
    if (id != '') {
      $.ajax({
        url: 'ajax.php?action=delete_comment',
        data: { id: id },
        method: 'POST',
        type: 'POST',
        success: function (resp) {
          if (resp == 1) {
            getattachment();
            alert_toast(' successfully deleted', "success");
            setTimeout(function () {
              //location.reload()

            }, 1500)
            getcomment();

          }
        }
      });
    }
  }

  document.getElementById('savecomment').addEventListener('click', function () {
    var commentValue = document.getElementById('comment').value;
    var task_id = document.getElementById('task_id').value;
    var user_id = document.getElementById('user_id').value;
    var selectedUsername = $("#selectedUsername").val();
    var formData = new FormData();
    formData.append('comment', commentValue);
    formData.append('task_id', task_id);
    formData.append('user_id', user_id);
    formData.append('tagged_users', selectedUsername);
    if (commentValue == '') {
      alert_toast('comment field is blank', "warning");
      setTimeout(function () {
      }, 1500);
    } else {
      $.ajax({
        url: 'ajax.php?action=save_comment',
        data: formData, // Corrected the usage of the FormData object here
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function (resp) {
          if (resp == 1) {
            getattachment();
            alert_toast('Data successfully saved', "success");
            setTimeout(function () {
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

  $(document).ready(function () {
    // Hide the save comment button initially
    $('#savecomment').hide();
    $('#canclecomment').hide();
    // Listen for input changes in the textarea
    $('#comment').on('input', function () {
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

  document.getElementById('canclecomment').addEventListener('click', function () {
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

              attdata += '<div contenteditable="true" id="commentId_' + comment.id + '" class="editable-input" oninput="handleInputChange(event)" data-attachment-list-id="' + comment.id + '">' + comment.comment + '</div><div class="update_comment_button displaynone"><button id="updatecomment_' + comment.id + '" onclick="updatecomment(this)" data-attachment-list-id="' + comment.id + '">save</button><button id="canclecommentupdate_' + comment.id + '" onclick="cancleupdaecomment(this)" data-attachment-list-id="' + comment.id + '">cancel</button></div><p>Commented By: ' + comment.user_name + '</p><p><span>Created At :' + comment.created_at + '</span><span>Updated At :' + comment.updated_at + '</span></p>';

              attdata += '<div class="action_container_comment"><p onclick="deletecomment(this)" data-attachment-list-id="' + comment.id + '">Delete</p></div>';

            } else {
              attdata += '<textarea disabled id="comment" name="comment" rows="2" cols="50" >' + comment.comment + '</textarea><div class="update_comment_button displaynone"> <button id="savecomment">save</button><button id="canclecomment">cancel</button></div> <p>Commented By : ' + comment.user_name + '</p><p><span>Created At :' + comment.created_at + '</span><span>Updated At :' + comment.updated_at + '</span></p>';

            }

          });
          var commentContainerDiv = document.querySelector('.comment_details');
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

  function updatecomment(element) {
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
    formData.append('updated_at', formattedDateTime);
    $.ajax({
      url: 'ajax.php?action=save_comment',
      data: formData, // Corrected the usage of the FormData object here
      cache: false,
      contentType: false,
      processData: false,
      method: 'POST',
      type: 'POST',
      success: function (resp) {
        if (resp == 1) {
          alert_toast('Data successfully saved', "success");
          setTimeout(function () {
          }, 1500)
          getcomment();
        }
      }
    })


  }

  function cancleupdaecomment(element) {
    getcomment();
  }

  function delete_sub_task(elem) {
    var id = $(elem).attr('data-id');
    console.log('subtask id', id);
    start_load()
    $.ajax({
      url: 'ajax.php?action=delete_sub_task',
      method: 'POST',
      data: { id: id },
      success: function (resp) {
        if (resp == 1) {
          alert_toast("Data successfully deleted", 'success')
          setTimeout(function () {
            location.reload()
          }, 1500)

        }
      }
    })
  }

  $("#comment").on("input", function () {
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
  $("#suggestionBox").on("click", ".suggestionItem", function () {
    const selectedUsername = $(this).text();
    const currentComment = $("#comment").val();
    const atPosition = currentComment.lastIndexOf('@');
    const newComment = currentComment.substring(0, atPosition) + '@' + selectedUsername + ' ' + currentComment.substring(atPosition + selectedUsername.length + 2);
    $("#comment").val(newComment);
    $("#taggedUsersList").append(`<span class="taggedUser">@${selectedUsername}</span>`);
    $("#suggestionBox").hide();
    $("#selectedUsername").val(selectedUsername);
  });  
</script>