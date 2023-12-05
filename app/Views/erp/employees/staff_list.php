<?php

use App\Models\DepartmentModel;
use App\Models\DesignationModel;
use App\Models\RolesModel;
use App\Models\UsersModel;
use App\Models\ShiftModel;
use App\Models\ConstantsModel;
use App\Models\SystemModel;

$DepartmentModel = new DepartmentModel();
$DesignationModel = new DesignationModel();
$RolesModel = new RolesModel();
$UsersModel = new UsersModel();
$ConstantsModel = new ConstantsModel();
$ShiftModel = new ShiftModel();
$SystemModel = new SystemModel();
$session = \Config\Services::session();
$usession = $session->get('sup_username');

$user_info = $UsersModel->where('user_id', $usession['sup_user_id'])->first();
if ($user_info['user_type'] == 'staff') {
  $departments = $DepartmentModel->where('company_id', $user_info['company_id'])->orderBy('department_id', 'ASC')->findAll();
  $designations = $DesignationModel->where('company_id', $user_info['company_id'])->orderBy('designation_id', 'ASC')->findAll();
  $office_shifts = $ShiftModel->where('company_id', $user_info['company_id'])->orderBy('office_shift_id', 'ASC')->findAll();
  $leave_types = $ConstantsModel->where('company_id', $user_info['company_id'])->where('type', 'leave_type')->orderBy('constants_id', 'ASC')->findAll();
  $roles = $RolesModel->where('company_id', $user_info['company_id'])->orderBy('role_id', 'ASC')->findAll();
} else {
  $departments = $DepartmentModel->where('company_id', $usession['sup_user_id'])->orderBy('department_id', 'ASC')->findAll();
  $designations = $DesignationModel->where('company_id', $usession['sup_user_id'])->orderBy('designation_id', 'ASC')->findAll();
  $office_shifts = $ShiftModel->where('company_id', $usession['sup_user_id'])->orderBy('office_shift_id', 'ASC')->findAll();
  $leave_types = $ConstantsModel->where('company_id', $usession['sup_user_id'])->where('type', 'leave_type')->orderBy('constants_id', 'ASC')->findAll();
  $roles = $RolesModel->where('company_id', $usession['sup_user_id'])->orderBy('role_id', 'ASC')->findAll();
}


$xin_system = $SystemModel->where('setting_id', 1)->first();
$employee_id = generate_random_employeeid();
$get_animate = '';
?>
<?php if (in_array('staff2', staff_role_resource()) || in_array('shift1', staff_role_resource()) || in_array('staffexit1', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
  <div id="smartwizard-2" class="border-bottom smartwizard-example sw-main sw-theme-default mt-2">
    <ul class="nav nav-tabs step-anchor">
      <?php if (in_array('staff2', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
        <li class="nav-item active"> <a href="<?= site_url('erp/staff-list'); ?>" class="mb-3 nav-link"> <span class="sw-done-icon feather icon-check-circle"></span> <span class="sw-icon fas fa-user-friends"></span>
            <?= lang('Dashboard.dashboard_employees'); ?>
            <div class="text-muted small">
              <?= lang('Main.xin_set_up'); ?>
              <?= lang('Dashboard.dashboard_employees'); ?>
            </div>
          </a> </li>
      <?php } ?>
      <?php if ($user_info['user_type'] == 'company') { ?>
        <li class="nav-item clickable"> <a href="<?= site_url('erp/set-roles'); ?>" class="mb-3 nav-link"> <span class="sw-done-icon feather icon-check-circle"></span> <span class="sw-icon fas fa-user-lock"></span>
            <?= lang('Main.xin_roles_privileges'); ?>
            <div class="text-muted small">
              <?= lang('Dashboard.left_set_roles'); ?>
            </div>
          </a> </li>
      <?php } ?>
      <?php if (in_array('shift1', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
        <li class="nav-item clickable"> <a href="<?= site_url('erp/office-shifts'); ?>" class="mb-3 nav-link"> <span class="sw-done-icon feather icon-check-circle"></span> <span class="sw-icon feather icon-clock"></span>
            <?= lang('Dashboard.left_office_shifts'); ?>
            <div class="text-muted small">
              <?= lang('Dashboard.xin_manage_shifts'); ?>
            </div>
          </a> </li>
      <?php } ?>
      <?php if (in_array('staffexit1', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
        <li class="nav-item clickable"> <a href="<?= site_url('erp/employee-exit'); ?>" class="mb-3 nav-link"> <span class="sw-done-icon feather icon-check-circle"></span> <span class="sw-icon feather icon-log-out"></span>
            <?= lang('Dashboard.left_employees_exit'); ?>
            <div class="text-muted small">
              <?= lang('Main.xin_set_up'); ?>
              <?= lang('Dashboard.left_employees_exit'); ?>
            </div>
          </a> </li>
      <?php } ?>
    </ul>
  </div>
  <hr class="border-light m-0 mb-3">
<?php } ?>
<?php if (in_array('staff3', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
  <div id="accordion">
    <div id="add_form" class="collapse add-form <?php echo $get_animate; ?>" data-parent="#accordion" style="">
      <?php $attributes = array('name' => 'add_employee', 'id' => 'xin-form', 'autocomplete' => 'off'); ?>
      <?php $hidden = array('user_id' => 0); ?>
      <?= form_open_multipart('erp/employees/add_employee', $attributes, $hidden); ?>
      <div class="row">
        <div class="col-md-8">
          <div class="card mb-2">
            <div class="card-header">
              <h5>
                <?= lang('Main.xin_add_new'); ?>
                <?= lang('Dashboard.dashboard_employee'); ?>
              </h5>
              <div class="card-header-right"> <a data-toggle="collapse" href="#add_form" aria-expanded="false" class="collapsed btn btn-sm waves-effect waves-light btn-primary m-0"> <i data-feather="minus"></i>
                  <?= lang('Main.xin_hide'); ?>
                </a> </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="company_name">
                      <?= lang('Main.xin_employee_first_name'); ?>
                      <span class="text-danger">*</span> </label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                      <input type="text" class="form-control" placeholder="<?= lang('Main.xin_employee_first_name'); ?>" name="first_name">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="last_name" class="control-label">
                      <?= lang('Main.xin_employee_last_name'); ?>
                      <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                      <input type="text" class="form-control" placeholder="<?= lang('Main.xin_employee_last_name'); ?>" name="last_name">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="employee_id">
                      <?= lang('Employees.dashboard_employee_id'); ?>
                    </label>
                    <span class="text-danger">*</span>
                    <input class="form-control" placeholder="<?= lang('Employees.dashboard_employee_id'); ?>" name="employee_id" type="text" value="<?php echo $employee_id; ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="contact_number">
                      <?= lang('Main.xin_contact_number'); ?>
                      <span class="text-danger">*</span></label>
                    <input class="form-control" placeholder="<?= lang('Main.xin_contact_number'); ?>" name="contact_number" type="text">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="gender" class="control-label">
                      <?= lang('Main.xin_employee_gender'); ?>
                    </label>
                    <select class="form-control" name="gender" data-plugin="select_hrm" data-placeholder="<?= lang('Main.xin_employee_gender'); ?>">
                      <option value="1">
                        <?= lang('Main.xin_gender_male'); ?>
                      </option>
                      <option value="2">
                        <?= lang('Main.xin_gender_female'); ?>
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="email">
                      <?= lang('Main.xin_email'); ?>
                      <span class="text-danger">*</span> </label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                      <input class="form-control" placeholder="<?= lang('Main.xin_email'); ?>" name="email" type="text">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="email">
                      <?= lang('Main.dashboard_username'); ?>
                      <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                      <input class="form-control" placeholder="<?= lang('Main.dashboard_username'); ?>" name="username" type="text">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="website">
                      <?= lang('Main.xin_employee_password'); ?>
                      <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-eye-slash"></i></span></div>
                      <input class="form-control" placeholder="<?= lang('Main.xin_employee_password'); ?>" name="password" type="text">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="office_shift_id" class="control-label">
                      <?= lang('Employees.xin_employee_office_shift'); ?>
                    </label>
                    <span class="text-danger">*</span>
                    <select onchange="addNewShift()" class="form-control" name="office_shift_id" data-plugin="select_hrm" data-placeholder="<?= lang('Employees.xin_employee_office_shift'); ?>" id="add_new_shift_id">
                      <option value="">
                        <?= lang('Employees.xin_employee_office_shift'); ?>
                      </option>
                      <?php foreach ($office_shifts as $ioffice_shift) : ?>
                        <option value="<?= $ioffice_shift['office_shift_id']; ?>">
                          <?= $ioffice_shift['shift_name']; ?>
                        </option>
                      <?php endforeach; ?>
                      <option value="add_new_shift"><?= lang('Dashboard.add_new_shift');?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="role">
                      <?= lang('Main.xin_employee_role'); ?>
                      <span class="text-danger">*</span></label>
                    <select class="form-control" name="role" data-plugin="select_hrm" data-placeholder="<?= lang('Main.xin_employee_role'); ?>" onchange="addNewRole()" id="staff_role">
                      <option value=""></option>
                      <?php foreach ($roles as $role) { ?>
                        <option value="<?php echo $role['role_id'] ?>"><?php echo $role['role_name'] ?></option>
                      <?php } ?>
                      <option value="add_new_role"><?= lang('Dashboard.add_new_role'); ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="department">
                      <?= lang('Dashboard.left_department'); ?>
                    </label>
                    <span class="text-danger">*</span>
                    <select class="form-control" name="department_id" id="department_id" data-plugin="select_hrm" data-placeholder="<?= lang('Dashboard.left_department'); ?>">
                      <option value="">
                        <?= lang('Dashboard.left_department'); ?>
                      </option>
                      <?php foreach ($departments as $idepartment) : ?>
                        <option value="<?= $idepartment['department_id']; ?>">
                          <?= $idepartment['department_name']; ?>
                        </option>
                      <?php endforeach; ?>
                      <option value="add_new"><?= lang('Dashboard.add_new_department'); ?></option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6" id="designation_ajax">
                  <div class="form-group">
                    <label for="designation">
                      <?= lang('Dashboard.left_designation'); ?>
                    </label>
                    <span class="text-danger">*</span>
                    <select onchange="addnewNewDesignation()" class="form-control" disabled="disabled" name="designation_id" data-plugin="select_hrm" data-placeholder="<?= lang('Dashboard.left_designation'); ?>" id="designation_id">
                      <option value="">
                        <?= lang('Dashboard.left_designation'); ?>
                      </option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>
                      <?= lang('Employees.xin_basic_salary'); ?>
                      <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">
                          <?= $xin_system['default_currency']; ?>
                        </span></div>
                      <input type="text" class="form-control" name="basic_salary" placeholder="<?= lang('Employees.xin_gross_salary'); ?>" value="0">
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>
                      <?= lang('Employees.xin_hourly_rate'); ?>
                      <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">
                          <?= $xin_system['default_currency']; ?>
                        </span></div>
                      <input type="text" class="form-control" name="hourly_rate" placeholder="<?= lang('Employees.xin_hourly_rate'); ?>" value="0">
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="salay_type">
                      <?= lang('Employees.xin_employee_type_wages'); ?>
                      <i class="text-danger">*</i></label>
                    <select name="salay_type" id="salay_type" class="form-control" data-plugin="select_hrm">
                      <option value="1">
                        <?= lang('Membership.daily'); ?>
                      </option>
                      <option value="2">
                        <?= lang('Membership.weekly'); ?>
                      </option>
                      <option value="3">
                        <?= lang('Membership.xin_per_month'); ?>
                      </option>
                    
                    </select>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="payment_type">
                      <?= lang('Employees.payment_mode'); ?>
                      <i class="text-danger">*</i></label>
                    <select name="payment_mode" id="payment_mode" class="form-control" data-plugin="select_hrm" data-placeholder="<?= lang('Employees.select_payment'); ?>">
                      <option value="">
                        <?= lang('Employees.select_payment'); ?>
                      </option>
                      <option value="1">
                        <?= lang('Employees.ebanking'); ?>
                      </option>
                      <option value="2">
                        <?= lang('Employees.cheque'); ?>
                      </option>
                      <option value="3">
                        <?= lang('Employees.cash'); ?>
                      </option>
                    
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="reset" class="btn btn-light" href="#add_form" data-toggle="collapse" aria-expanded="false">
                <?= lang('Main.xin_reset'); ?>
              </button>
              &nbsp;
              <button type="submit" class="btn btn-primary">
                <?= lang('Main.xin_save'); ?>
              </button>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5>
                <?= lang('Main.xin_e_details_profile_picture'); ?>
              </h5>
            </div>
            <div class="card-body py-2">
              <div class="row">
                <img src="" alt="" class="img-fluid w-50" style="margin:auto;" id="profile_img_view">
                <div class="col-md-12 mt-3">
                  <label for="logo">
                    <?= lang('Main.xin_e_details_profile_picture'); ?>
                    <span class="text-danger">*</span> </label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="file" id="profile-img-input">
                    <label class="custom-file-label">
                      <?= lang('Main.xin_choose_file'); ?>
                    </label>
                    <small>
                      <?= lang('Main.xin_company_file_type'); ?>
                    </small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?= form_close(); ?>
    </div>
  </div>
<?php } ?>
<div class="card user-profile-list">
  <div class="card-header">
    <h5>
      <?= lang('Main.xin_list_all'); ?>
      <?= lang('Dashboard.dashboard_employees'); ?>
    </h5>
    <div class="card-header-right"> <a href="<?= site_url() . 'erp/staff-grid'; ?>" class="btn btn-sm waves-effect waves-light btn-primary btn-icon m-0" data-toggle="tooltip" data-placement="top" title="<?= lang('Projects.xin_grid_view'); ?>"> <i class="fas fa-th-large"></i> </a>
      <?php if (in_array('staff3', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
        <a data-toggle="collapse" href="#add_form" aria-expanded="false" class="collapsed btn waves-effect waves-light btn-primary btn-sm m-0"> <i data-feather="plus"></i>
          <?= lang('Main.xin_add_new'); ?>
        </a>
      <?php } ?>
    </div>
  </div>
  <div class="card-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table">
        <thead>
          <tr>
            <th><?= lang('Main.xin_name'); ?></th>
            <th><?= lang('Dashboard.left_designation'); ?></th>
            <th><?= lang('Main.xin_contact_number'); ?></th>
            <th><?= lang('Main.xin_employee_gender'); ?></th>
            <th><?= lang('Main.xin_country'); ?></th>
            <th><?= lang('Main.xin_employee_role'); ?></th>
            <th><?= lang('Main.dashboard_xin_status'); ?></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

<style type="text/css">
.k-in { display:none !important; }
</style>
<!-- modal begins here  -->
<div class="modal fade" id="department_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="card">
          <div class="card-header with-elements"> <span class="card-header-title mr-2"><strong>
                <?= lang('Main.xin_add_new'); ?>
              </strong>
              <?= lang('Dashboard.left_department'); ?>
            </span> </div>
          <div class="card-body">
            <?php $attributes = array('name' => 'add_department', 'id' => 'xin-form-2', 'autocomplete' => 'off',); ?>
            <?php $hidden = array('user_id' => 1); ?>
            <?php echo form_open('erp/department/add_department', $attributes, $hidden); ?>
            <div class="form-group">
              <label for="name">
                <?= lang('Dashboard.xin_name'); ?> <span class="text-danger">*</span>
              </label>
              <input type="text" class="form-control" name="department_name" placeholder="<?= lang('Dashboard.xin_name'); ?>">
            </div>
            <?php if ($user_info['user_type'] == 'company') { ?>
              <?php $staff_info = $UsersModel->where('company_id', $usession['sup_user_id'])->where('user_type', 'staff')->findAll(); ?>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="first_name">
                      <?= lang('Dashboard.xin_department_head'); ?>
                    </label>
                    <select class="form-control" name="employee_id" data-plugin="select_hrm" data-placeholder="<?= lang('Dashboard.xin_department_head'); ?>">
                      <option value=""><?= lang('Dashboard.xin_department_head'); ?></option>
                      <?php foreach ($staff_info as $staff) { ?>
                        <option value="<?= $staff['user_id'] ?>">
                          <?= $staff['first_name'] . ' ' . $staff['last_name'] ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
          <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">
              <?= lang('Main.xin_save'); ?>
            </button>
          </div>
          <?= form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- designation model begins here -->
<div class="modal fade" id="designation_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row m-b-1 <?php echo $get_animate; ?>">
          <?php if (in_array('designation2', staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
            <div class="col">
              <div class="card">
                <div class="card-header with-elements"> <span class="card-header-title mr-2"><strong>
                      <?= lang('Main.xin_add_new'); ?>
                    </strong>
                    <?= lang('Dashboard.left_designation'); ?>
                  </span> </div>
                <div class="card-body">
                  <?php $attributes = array('name' => 'add_designation', 'id' => 'xin-form-designation', 'autocomplete' => 'off'); ?>
                  <?php $hidden = array('user_id' => 1); ?>
                  <?php echo form_open('erp/designation/add_designation', $attributes, $hidden); ?>
                  <div class="form-group">
                    <label for="name">
                      <?= lang('Dashboard.left_designation_name'); ?> <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" name="designation_name" placeholder="<?= lang('Dashboard.left_designation_name'); ?>">
                  </div>
                  <div class="form-group">
                    <label for="description">
                      <?= lang('Main.xin_description'); ?>
                    </label>
                    <textarea type="text" class="form-control" name="description" placeholder="<?= lang('Main.xin_description'); ?>"></textarea>
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button type="submit" class="btn btn-primary">
                    <?= lang('Main.xin_save'); ?>
                  </button>
                </div>
                <?= form_close(); ?>
              </div>
            </div>


        </div>
      </div>
    </div>
  <?php } ?>
  </div>
</div>


<!-- role model begins here -->

<div class="modal fade" id="role_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div id="add_form" class="collapse add-form" data-parent="#accordion" style="">
          <div class="card mb-2">
            <div id="accordion">
              <div class="card-header">
                <h5>
                  <?= lang('Main.xin_add_new'); ?>
                  <?= lang('Main.xin_employee_role'); ?>
                </h5>
                <div class="card-header-right"></div>
              </div>
              <div class="card-body">
                <div class="row m-b-1">
                  <div class="col-md-12">
                    <?php $attributes = array('name' => 'add_role', 'id' => 'xin-form-add_role', 'autocomplete' => 'off'); ?>
                    <?php $hidden = array('_user' => 0); ?>
                    <?= form_open('erp/roles/add_role', $attributes, $hidden); ?>
                    <div class="form-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="role_name">
                                  <?= lang('Users.xin_role_name'); ?>
                                  <span class="text-danger">*</span> </label>
                                <input class="form-control" placeholder="<?= lang('Users.xin_role_name'); ?>" name="role_name" type="text" value="">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <input type="checkbox" name="role_resources[]" value="0" checked style="display:none;" />
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="role_access">
                                  <?= lang('Users.xin_role_access'); ?>
                                  <span class="text-danger">*</span></label>
                                <select class="form-control custom-select" id="role_access" data-plugin="select_hrm" name="role_access" data-placeholder="<?= lang('Users.xin_role_access'); ?>">
                                  <option value="">&nbsp;</option>
                                  <option value="1">
                                    <?= lang('Users.xin_role_all_menu'); ?>
                                  </option>
                                  <option value="2">
                                    <?= lang('Users.xin_role_cmenu'); ?>
                                  </option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="resources">
                                  <?= lang('Staff Apps'); ?>
                                </label>
                                <div id="all_resources">
                                  <div class="demo-section k-content">
                                    <div>
                                      <div id="treeview_r1"></div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="resources"> <?= lang('Company Apps'); ?></label>
                                <div id="all_resources">
                                  <div class="demo-section k-content">
                                    <div>
                                      <div id="treeview_r2"></div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer text-right">
                &nbsp;
                <button type="submit" class="btn btn-primary">
                  <?= lang('Main.xin_save'); ?>
                </button>
              </div>
              <?= form_close(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- office shift modal begins here  -->

<div class="modal fade" id="add_shift_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div id="add_form" class="collapse add-form" data-parent="#accordion" style="">
        <?php if(in_array('shift2',staff_role_resource()) || $user_info['user_type'] == 'company') { ?>
<div id="add_form" class="collapse add-form" data-parent="#accordion" style="">
  <div class="card">
    <div id="accordion">
      <div class="card-header">
        <h5>
          <?= lang('Main.xin_add_new');?>
          <?= lang('Employees.xin_employee_office_shift');?>
        </h5>
       
      </div>
      <?php $attributes = array('name' => 'add_office_shift', 'id' => 'xin-form-add_shift', 'autocomplete' => 'off');?>
      <?php $hidden = array('user_id' => 1);?>
      <?php echo form_open('erp/officeshifts/add_office_shift', $attributes, $hidden);?>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_shift_name');?>
                <span class="text-danger">*</span> </label>
              <input class="form-control" placeholder="<?= lang('Employees.xin_shift_name');?>" name="shift_name" type="text" value="" id="name">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_monday_in_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-1" placeholder="<?= lang('Employees.xin_shift_in_time');?>" readonly name="monday_in_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="1"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_monday_out_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-2" placeholder="<?= lang('Employees.xin_shift_out_time');?>" readonly name="monday_out_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="2"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_tuesday_in_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-3" placeholder="<?= lang('Employees.xin_shift_in_time');?>" readonly name="tuesday_in_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="3"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_tuesday_out_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-4" placeholder="<?= lang('Employees.xin_shift_out_time');?>" readonly name="tuesday_out_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="4"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_wednesday_in_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-5" placeholder="<?= lang('Employees.xin_shift_in_time');?>" readonly name="wednesday_in_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="5"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_wednesday_out_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-6" placeholder="<?= lang('Employees.xin_shift_out_time');?>" readonly name="wednesday_out_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="6"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_thursday_in_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-7" placeholder="<?= lang('Employees.xin_shift_in_time');?>" readonly name="thursday_in_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="7"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_thursday_out_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-8" placeholder="<?= lang('Employees.xin_shift_out_time');?>" readonly name="thursday_out_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="8"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_friday_in_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-9" placeholder="<?= lang('Employees.xin_shift_in_time');?>" readonly name="friday_in_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="9"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_friday_out_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-10" placeholder="<?= lang('Employees.xin_shift_out_time');?>" readonly name="friday_out_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="10"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_saturday_in_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-11" placeholder="<?= lang('Employees.xin_shift_in_time');?>" readonly name="saturday_in_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="11"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_saturday_out_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-12" placeholder="<?= lang('Employees.xin_shift_out_time');?>" readonly name="saturday_out_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="12"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_sunday_in_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-13" placeholder="<?= lang('Employees.xin_shift_in_time');?>" readonly name="sunday_in_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="13"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_name">
                <?= lang('Employees.xin_sunday_out_time');?>
                <span class="text-danger">*</span> </label>
              <div class="input-group">
                <input class="form-control timepicker clear-14" placeholder="<?= lang('Employees.xin_shift_out_time');?>" readonly name="sunday_out_time" type="text" value="">
                <div class="input-group-append clear-time" data-clear-id="14"><span class="input-group-text text-danger"><i class="fas fa-trash-alt"></i></span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary">
        <?= lang('Main.xin_save');?>
        </button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>
</div>
<?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>