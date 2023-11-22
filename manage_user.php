<?php 
include('db_connect.php');
session_start();
if(isset($_GET['id'])){
$user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
foreach($user->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
}
?>
<div class="container-fluid">
	
	
	<form action="" id="manage-user">	
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<div class="form-group">
			<label for="name">First Name</label>
			<input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo isset($meta['firstname']) ? $meta['firstname'] : ''; ?>" required maxlength="20">
		</div>
		<div class="form-group">
			<label for="name">Last Name</label>
			<input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo isset($meta['lastname']) ? $meta['lastname']: '' ?>" required  maxlength="20">
		</div>
		<div class="form-group">
			<label for="email">Email</label>
			<input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" required  autocomplete="off">
			<div id="msg"></div>
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control" value="<?php echo isset($meta['password']) ? ($meta['password']): '' ?>" autocomplete="off">
			<small><i>Leave this blank if you dont want to change the password.</i></small>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Avatar</label>
			<div class="custom-file">
				<input type="file"  accept="image/jpeg,image/jpg, image/png" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
				<label class="custom-file-label" for="customFile">Choose file</label>
            </div>
		</div>
		<div class="form-group d-flex justify-content-center">
			<img src="<?php echo isset($meta['avatar']) ? 'assets/uploads/'.$meta['avatar'] :'' ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
		</div>
		

	</form>
</div>
<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
var initialFormData; // Variable to store the initial form data

function displayImg(input, _this) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#cimg').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$(document).ready(function() {
    // Store the initial form data when the page loads
    initialFormData = serializeForm($('#manage-user'));
    console.log('initialFormData', initialFormData);
});

$('#manage-user').submit(function(e){
    e.preventDefault();
    
    // Check if any changes have been made
    if (!isFormDataChanged()) {
        alert_toast('No changes done.', 'warning');
        return;
    }

    // Check if any required fields are empty
    if (isFormDataEmpty()) {
        return;
    }

    start_load();

    $.ajax({
        url: 'ajax.php?action=update_user',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function(resp) {
            if (resp == 1) {
                alert_toast('Data successfully saved', 'success');
                setTimeout(function(){
                    location.reload();
                }, 1500);
            } else {
                $('#msg').html('<div class="alert alert-danger">Username already exists</div>');
                end_load();
            }
        }
    });
});

function isFormDataChanged() {
    var currentFormData = serializeForm($('#manage-user'));
  //  delete currentFormData['password'];
	//delete initialFormData['password'];
    // Convert FormData objects to JSON for easy comparison
    var currentJSON = JSON.stringify(currentFormData);
    var initialJSON = JSON.stringify(initialFormData);
	console.log('currentJSON ', currentJSON);
	console.log('initialJSON', initialJSON);

    return currentJSON !== initialJSON;
}

function isFormDataEmpty() {
    var formData = serializeForm($('#manage-user'));
    var firstname = formData.firstname;
    var email = formData.email;

    if (firstname.trim() === '' || email.trim() === '') {
        alert_toast('Please fill in all the required fields.', 'warning');
        return true;
    }

    return false;
}

// Serialize form data into a plain JavaScript object
function serializeForm(form) {
    var formData = {};
    form.serializeArray().forEach(function(item) {
        formData[item.name] = item.value;
    });
    return formData;
}



</script>