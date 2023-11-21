  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="dropdown">
    <a href="./" class="brand-link">
      <?php if($_SESSION['login_type'] == 1): ?>
        <p class="text-center p-0 m-0"><b>ADMIN</b></p>
        <?php elseif($_SESSION['login_type'] == 2): ?>
        <p class="text-center p-0 m-0">MANAGER</p>
        <?php elseif($_SESSION['login_type'] == 4): ?>
        <p class="text-center p-0 m-0">TECH LEAD</p>
        <?php elseif($_SESSION['login_type'] == 5): ?>
        <p class="text-center p-0 m-0">TESTER</p>
        <?php elseif($_SESSION['login_type'] == 3): ?>
        <p class="text-center p-0 m-0">DEVELOPER</p>
        <?php elseif($_SESSION['login_type'] == 6): ?>
        <p class="text-center p-0 m-0">GUEST</p>
        <?php elseif($_SESSION['login_type'] == 7): ?>
        <p class="text-center p-0 m-0">TEAM LEAD</p>
        <?php elseif($_SESSION['login_type'] == 8): ?>
        <p class="text-center p-0 m-0">Marketing Associate</p>
        <?php endif; ?>
    </a>
      
    </div>
    <div class="sidebar pb-4 mb-4">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>  
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_project nav-view_project">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>
                Projects
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php if($_SESSION['login_type'] != 3 && $_SESSION['login_type'] != 5 && $_SESSION['login_type'] != 6 && $_SESSION['login_type'] != 8): ?>
              <li class="nav-item">
                <a href="./index.php?page=new_project" class="nav-link nav-new_project tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
            <?php endif; ?>
              <li class="nav-item">
                <a href="./index.php?page=project_list" class="nav-link nav-project_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li> 
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_project nav-View_Sprint">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>
                Sprint
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php if($_SESSION['login_type'] != 3 && $_SESSION['login_type'] != 5 && $_SESSION['login_type'] != 6 && $_SESSION['login_type'] != 8): ?>
              <li class="nav-item">
                <a href="./index.php?page=new_sprint" class="nav-link nav-new_sprint tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
            <?php endif; ?>
              <li class="nav-item">
                <a href="./index.php?page=sprint_list" class="nav-link nav-sprint_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li> 
          <li class="nav-item">
                <a href="./index.php?page=task_list" class="nav-link nav-task_list">
                  <i class="fas fa-tasks nav-icon"></i>
                  <p>Task</p>
                </a>
          </li>
          <li class="nav-item">
                <a href="./index.php?page=list_view" class="nav-link nav-list_view">
                  <i class="fas fa-tasks nav-icon"></i>
                  <p>List View</p>
                </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-calendar nav-time_sheet">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>
                Time sheet
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=calendar" class="nav-link nav-calendar tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=view_timesheet" class="nav-link nav-view_timesheet tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>View</p>
                </a>
              </li>
            </ul>
          </li>
          <?php if($_SESSION['login_type'] != 3 || $_SESSION['login_type'] != 5 || $_SESSION['login_type'] != 8): ?>
          <li class="nav-item">
                <a href="./index.php?page=reports" class="nav-link nav-reports">
                  <i class="fas fa-th-list nav-icon"></i>
                  <p>Report</p>
                </a>
          </li>
          <?php endif; ?>
          <?php if($_SESSION['login_type'] == 1): ?>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_user" class="nav-link nav-new_user tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=user_list" class="nav-link nav-user_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
                <a href="./index.php?page=activity_log" class="nav-link nav-reports">
                  <i class="fas fa-th-list nav-icon"></i>
                  <p>Log Activity</p>
                </a>
          </li>
        <?php endif; ?>
        <?php if($_SESSION['login_type'] != 6){ ?>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>
                Daily Status
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=daily_status" class="nav-link nav-daily_status tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=work_report" class="nav-link nav-work_report tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
      <?php } ?>
        </ul>
      </nav>
    </div>
  </aside>
  <script>
  	$(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  		var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if(s!='')
        page = page+'_'+s;
  		if($('.nav-link.nav-'+page).length > 0){
            $('.nav-link.nav-'+page).addClass('active')
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }
  		}
  	})
  </script>