<?php include'db_connect.php';
$tableDescriptions = array(
    "attachment_list_history" => array(
        "table" => "Attachment Table",
        "description" => "Description for Attachment Table"
    ),
    "comment_list_history" => array(
        "table" => "Comment Table",
        "description" => "Description for Comment Table"
    ),
    "project_list_history" => array(
        "table" => "Project Table",
        "description" => "Description for Project Table"
    ),
    "sprint_list_history" => array(
        "table" => "Sprint Table",
        "description" => "Description for Sprint Table"
    ),
    "sub_task_list_history" => array(
        "table" => "Sub-Task Table",
        "description" => "Description for Sub-Task Table"
    ),
    "task_list_history" => array(
        "table" => "Task Table",
        "description" => "Description for Task Table"
    ),
    "user_list_history" => array(
        "table" => "User Table",
        "description" => "Description for User Table"
    ),
    "user_productivity_list_history" => array(
        "table" => "User Productivity Table",
        "description" => "Description for User Productivity Table"
    )
);


?>
<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-header_inner">
                <form method="POST" id="myForm">
                    <div class="row">
                        <div class="col-3">
                            <label>Tables :</label>
                            <select name="table" id="table" required>
                                <option value="">--Select--</option>
                                <?php
                                foreach ($tableDescriptions as $tableName => $tableInfo) {
                                    echo '<option value="' . $tableInfo['table'] . '">' . $tableInfo['table'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-2">
                            <label>Change Type :</label>
                            <select name="changeType" id="changeType"  required>
                                <option value="">--Select--</option>
                                <option value="insert">Insert</option>
                                <option value="update">Update</option>
                                <option value="delete">Delete</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Time Between : </label><br>
                            <input type="datetime-local" name="date_from" id="date_from" required> to
                            <input type="datetime-local" name="date_to" id="date_to" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            <input type="submit" name="Show"  value="Show" class="btn btn-primary" style="width:80%;align-item:center;text-align:center;margin-top:20px;">
                        </div>
                        <div class="col-3"></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="log_table table-responsive" id="log_table">
                <p class="card-success">Please Select Projects!</p>
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
document.getElementById("myForm").addEventListener("submit", function(event) {
        event.preventDefault(); 
        var table = document.getElementById("table").value;
        var changeType = document.getElementById("changeType").value;
        var dateFrom = document.getElementById("date_from").value;
        var dateTo = document.getElementById("date_to").value;
        $.ajax({
        url: 'ajax.php?action=get_log_activity',
        data: { table: table , changeType:changeType, dateFrom:dateFrom, dateTo:dateTo},
        method: 'POST',
        dataType: 'json', // Set the expected response data type to JSON
        success: function(resp) {
           // alert(JSON.stringify(resp));
            createListView(resp);
           // console.log(' resp @@ ',resp);
        }
        }); 
});

function createListView(resp) {
    if (resp.length === 0) {
        const listViewDiv = document.getElementById('log_table');
        listViewDiv.innerHTML = '<p>No data found</p>';
        return;
    }

    const keys = Object.keys(resp[0]);

    let tableHTML = '<table class="table table-hover table-condensed" id="list">';
    tableHTML += '<colgroup>';
    tableHTML += '<col width="5%">'; // Add a fixed width column for row count
    keys.forEach(key => {
        tableHTML += '<col>';
    });
    tableHTML += '</colgroup>';
    tableHTML += '<thead>';
    tableHTML += '<tr>';
    tableHTML += '<th class="text-center">#</th>'; // Add the header for row count
    keys.forEach(key => {
        tableHTML += '<th>' + key + '</th>';
    });
    tableHTML += '</tr>';
    tableHTML += '</thead>';
    tableHTML += '<tbody>';

    for (let i = 0; i < resp.length; i++) {
        tableHTML += '<tr>';
        tableHTML += '<td>' + (i + 1) + '</td>'; // Incrementing row number
        keys.forEach(key => {
            const value = resp[i][key];
            tableHTML += '<td>' + (value !== null ? value : '') + '</td>';
        });
        tableHTML += '</tr>';
    }

    tableHTML += '</tbody>';
    tableHTML += '</table>';

    const listViewDiv = document.getElementById('log_table');
    listViewDiv.innerHTML = tableHTML;

    $('#list').DataTable();
}





</script>