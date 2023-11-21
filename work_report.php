<?php include'db_connect.php' ;
$allusers = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users WHERE type IN (3,5,7,8,4)")->fetch_all(MYSQLI_ASSOC);
if($_SESSION['login_type']==7){

    $value = $_SESSION['login_teams'].','.$_SESSION['login_id'];
    $leadusers = $conn->query("SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM users WHERE id IN ($value)")->fetch_all(MYSQLI_ASSOC);
}
?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
                <form id="user_productivity">
                <div class="row">
                    <div class="col-sm">
                        <div class="form-group">
							<label for="" class="control-label">Employee</label>
                            <select name="user" id="user" class="custom-select custom-select-sm">
                                <?php  if($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 2 || $_SESSION['login_type'] == 4 ){ ?>
                                 <option value="">--ALL Users--</option>

                                 <?php } ?>
                                 <?php
                                 if($_SESSION['login_type'] != 7){
                                    foreach($allusers as $data){
                                        if($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 2 || $_SESSION['login_type'] == 4 ){
                                            echo '<option value="' . $data['id'] . '"><span class="ellipsis">' . $data['name'] . '</span></option>';

                                        }else if($data['id'] == $_SESSION['login_id']){
                                            echo '<option value="' . $data['id'] . '" selected ><span class="ellipsis">' . $data['name'] . '</span></option>';
   
                                        }
                                    }
                                 }else{ ?>
                                     <option value="<?php echo $value?>">--ALL Users--</option> -->
                                    <?php
                                    foreach ($leadusers as $data) {
                                        $selected = ($data['id'] == $_SESSION['login_id']) ? 'selected' : '';
                                        echo '<option value="' . $data['id'] . '" ' . $selected . '><span class="ellipsis">' . $data['name'] . '</span></option>';
                                    }                                    
                                }                                    
                                 ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
							<label for="" class="control-label">Date From</label>
                            <input type="date"name="date_from" class="form-control form-control-sm" />
						</div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
							<label for="" class="control-label">Date To</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" />
						</div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                        <button class="btn btn-primary justify-content-center" style="margin: 27px 0;">Show</button>						</div>
                    </div>
                   
                </div>	
                </from>
            </div>
           
		</div>
		<div class="card-body">
			<div class="table-responsive">
                   <div class="productivity_table table-responsive" id="productivity_table">
                        <p class="card-success"> 
                            Please Select Date !
                        </p>
                    </div>
                </div>   
			</div>
		</div>
	</div>
</div>
<style>
	table p{
		margin: unset !important;
	}
	table td{
		vertical-align: middle !important
	}
</style>
<script>
$(document).ready(function(){
    $('#list').dataTable()

    $('.delete_progress').click(function(){
        console.log('hello @@@ = ');
        _conf("Are you sure to delete this report?", "delete_progress", [$(this).attr('data-id')]);
	})
function delete_progress($id){
    console.log('id @@@ = ',$id);
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_progress',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
}

})

$('#user_productivity').submit(function(e){
		e.preventDefault()
        if (isFormDataEmpty()) {
            return;
        }
		$.ajax({
			url:'ajax.php?action=get_productivity',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
                
                var data = JSON.parse(resp);
              
				CreateProductivityTable(data);
			}
		})
})
function isFormDataEmpty() {
    var formData = new FormData($('#user_productivity')[0]);
    var date_from = formData.get('date_from');
    var date_to = formData.get('date_to');
    
   

    if (date_from.trim() === '' ) {
        alert_toast('Please fill Date From.', 'warning');
        return true;
    }


    return false;
}
function formatDate(dateString) {
  const date = new Date(dateString);
  const options = { year: 'numeric', month: 'short', day: '2-digit' };
  return date.toLocaleDateString('en-US', options);
}

function stripTags(input) {
    return input.replace(/<\/?[^>]+(>|$)/g, '');
}
function CreateProductivityTable(resp) {
    // Initialize the HTML table structure
    let tableHTML = '<table class="table table-hover table-condensed" id="list">';
    tableHTML += '<colgroup>';
    tableHTML += '<col width="5%">';
    tableHTML += '<col width="10%">';
    tableHTML += '<col width="10%">';
    tableHTML += '<col width="15%">';
    tableHTML += '<col width="25%">';
    tableHTML += '<col width="10%">';
    tableHTML += '<col width="10%">';

    tableHTML += '</colgroup>';
    tableHTML += '<thead>';
    tableHTML += '<tr>';
    tableHTML += '<th class="text-center">#</th>';
    tableHTML += '<th>User Name</th>';
    tableHTML += '<th>Project Name</th>';
    tableHTML += '<th>Task</th>'; 
    tableHTML += '<th>Summary</th>';
    tableHTML += '<th>Hour</th>';
    tableHTML += '<th>Date</th>';
    tableHTML += '<th></th>';
    tableHTML += '<th></th>';


    tableHTML += '</tr>';
    tableHTML += '</thead>';
    tableHTML += '<tbody>';

    // Loop through the response data and populate table rows
    for (let i = 0; i < resp.length; i++) {
        tableHTML += '<tr>';
        tableHTML += '<td>' + (i + 1) + '</td>';
        tableHTML += '<td>' + resp[i].user_name + '</td>';
        tableHTML += '<td>' + resp[i].Project_Name + '</td>';
        tableHTML += '<td>' + resp[i].task_id + '</td>';
        tableHTML += '<td>' +stripTags( resp[i].comment) + '</td>';
        tableHTML += '<td>' + parseFloat(resp[i].time_rendered).toFixed(2) + ' Hours'+'</td>';
        const formattedStartDate = formatDate(resp[i].date);

        tableHTML += '<td>' + formattedStartDate + '</td>';
        if (resp[i].user_id == <?php echo $_SESSION['login_id']; ?>) {
            tableHTML += '<td><a class="dropdown-item" href="./index.php?page=edit_daily_status&id=' + resp[i].id + '" style="background-color: #007bff;color: #fff;text-align: center;">Edit</a></td>';
            tableHTML += '<td><a class="dropdown-item delete_progress" onclick="delete_progress(' + resp[i].id + ')" href="javascript:void(0)" data-id="' + resp[i].id + '" style="background-color: #ffbf00;color: #fff;text-align: center;">Delete</a></td>';
        }else{
            tableHTML += '<td></td>';
            tableHTML += '<td></td>';
        }

        tableHTML += '</tr>';
    }

    tableHTML += '</tbody>';
    tableHTML += '</table>';

    // Append the generated table to the specified HTML element
    const productivityTableContainer = document.getElementById('productivity_table');
    productivityTableContainer.innerHTML = tableHTML;

    // Destroy and reinitialize DataTable for the new task list (if using DataTables library)
    $('#list').DataTable().destroy(); // Uncomment if you are using DataTables
    $('#list').DataTable(); // Uncomment if you are using DataTables
}

function delete_progress(id){
    console.log('id @@@ = ',id);
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_progress',
			method:'POST',
			data:{id:id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
}

</script>