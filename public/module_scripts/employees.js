$(document).ready(function () {
  var xin_table = $("#xin_table").dataTable({
    bDestroy: true,
    ajax: {
      url: main_url + "employees/employees_list",
      type: "GET",
    },
    language: {
      lengthMenu: dt_lengthMenu,
      zeroRecords: dt_zeroRecords,
      info: dt_info,
      infoEmpty: dt_infoEmpty,
      infoFiltered: dt_infoFiltered,
      search: dt_search,
      paginate: {
        first: dt_first,
        previous: dt_previous,
        next: dt_next,
        last: dt_last,
      },
    },
    fnDrawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
  });
  jQuery("#department_id").change(function () {
    jQuery.get(
      main_url + "employees/is_designation/" + jQuery(this).val(),
      function (data, status) {
        jQuery("#designation_ajax").html(data);
      }
    );
  });
  /* Delete data */
  $("#delete_record").submit(function (e) {
    /*Form Submit*/
    e.preventDefault();
    var obj = $(this),
      action = obj.attr("name");
    $.ajax({
      type: "POST",
      url: e.target.action,
      data: obj.serialize() + "&is_ajax=2&type=delete_record&form=" + action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != "") {
          toastr.error(JSON.error);
          $('input[name="csrf_token"]').val(JSON.csrf_hash);
          Ladda.stopAll();
        } else {
          $(".delete-modal").modal("toggle");
          xin_table.api().ajax.reload(function () {
            toastr.success(JSON.result);
          }, true);
          $('input[name="csrf_token"]').val(JSON.csrf_hash);
          Ladda.stopAll();
        }
      },
    });
  });

  // edit
  $(".edit-modal-data").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var user_id = button.data("field_id");
    var modal = $(this);
    $.ajax({
      url: main_url + "users/read",
      type: "GET",
      data: "jd=1&data=user&user_id=" + user_id,
      success: function (response) {
        if (response) {
          $("#ajax_modal").html(response);
        }
      },
    });
  });

  $(".view-modal-data").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var user_id = button.data("field_id");
    var modal = $(this);
    $.ajax({
      url: main_url + "users/read",
      type: "GET",
      data: "jd=1&type=view_user&user_id=" + user_id,
      success: function (response) {
        if (response) {
          $("#ajax_view_modal").html(response);
        }
      },
    });
  });

  /* Add data */ /*Form Submit*/ $("#xin-form").submit(function (e) {
    var fd = new FormData(this);
    var obj = $(this),
      action = obj.attr("name");
    fd.append("is_ajax", 1);
    fd.append("type", "add_record");
    fd.append("form", action);
    e.preventDefault();
    $.ajax({
      url: e.target.action,
      type: "POST",
      data: fd,
      contentType: false,
      cache: false,
      processData: false,
      success: function (JSON) {
        if (JSON.error != "") {
          toastr.error(JSON.error);
          $('input[name="csrf_token"]').val(JSON.csrf_hash);
          Ladda.stopAll();
        } else {
          xin_table.api().ajax.reload(function () {
            toastr.success(JSON.result);
          }, true);
          $('input[name="csrf_token"]').val(JSON.csrf_hash);
          $("#xin-form")[0].reset(); // To reset form fields
          $(".add-form").removeClass("show");
          window.location = JSON.redirect_url;
          Ladda.stopAll();
        }
      },
      error: function () {
        toastr.error(JSON.error);
        $('input[name="csrf_token"]').val(JSON.csrf_hash);
        Ladda.stopAll();
      },
    });
  });
});
$(document).on("click", ".delete", function () {
  $("input[name=_token]").val($(this).data("record-id"));
  $("#delete_record").attr("action", main_url + "employees/delete_staff");
});

$("#profile-img-input").on("change", function () {
  var input = this;
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $("#profile_img_view").attr("src", e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
  }
});

$("#department_id").on("change", function () {
  let value = $(this).val();
  if (value == "add_new") {
    $("#department_modal").modal("show");
  }
});

$("#xin-form-2").submit(function (e) {
  var fd = new FormData(this);
  var obj = $(this),
    action = obj.attr("name");
  fd.append("is_ajax", 1);
  fd.append("type", "add_record");
  fd.append("form", action);
  fd.append("from", "employee_add_option");
  e.preventDefault();
  $.ajax({
    url: e.target.action,
    type: "POST",
    data: fd,
    contentType: false,
    cache: false,
    processData: false,
    success: function (JSON) {
      if (JSON.error != "") {
        toastr.error(JSON.error);
        $('input[name="csrf_token"]').val(JSON.csrf_hash);
        Ladda.stopAll();
      } else {
        var $newOption = $("<option>", {
          value: JSON.data.id,
          text: JSON.data.department_name,
        });

        // Insert the new option before the last option in the list.
        $("#department_id option:last").prev().before($newOption);
        // Refresh the Select2 control to show the newly added option.
        $("#department_id").val(JSON.data.id).trigger("change");
        $('input[name="csrf_token"]').val(JSON.csrf_hash);
        $("#xin-form-2")[0].reset(); // To reset form fields
        $("#department_modal").modal("hide");

        Ladda.stopAll();
      }
    },
    error: function () {
      toastr.error(JSON.error);
      $('input[name="csrf_token"]').val(JSON.csrf_hash);
      Ladda.stopAll();
    },
  });
});

const addnewNewDesignation = () => {
  try {
    let department = $("#department_id").val();
    let designation = $("#designation_id").val();
    if (department == "add_new" && designation == "add_designation") {
      toastr.error("please choose a department first");
      $("#designation_id").val(null).trigger("change");
    } else if(department != 'add_new' && designation == 'add_designation' ) {
      $("#designation_modal").modal("show");
    }
  } catch (error) {
    console.log(error);
  }
};

$("#xin-form-designation").submit(function (e) {
  var fd = new FormData(this);
  var obj = $(this),
    action = obj.attr("name");
  fd.append("is_ajax", 1);
  fd.append("type", "add_record");
  fd.append("form", action);
  fd.append("department", $("#department_id").val());
  e.preventDefault();
  $.ajax({
    url: e.target.action,
    type: "POST",
    data: fd,
    contentType: false,
    cache: false,
    processData: false,
    success: function (JSON) {
      if (JSON.error != "") {
        toastr.error(JSON.error);
        $('input[name="csrf_token"]').val(JSON.csrf_hash);
        Ladda.stopAll();
      } else {
        console.log(JSON);
        $("#designation_modal").modal("hide");
        $('input[name="csrf_token"]').val(JSON.csrf_hash);
        $("#xin-form-designation")[0].reset(); // To reset form fields
        var $newOption = $("<option>", {
          value: JSON.data.id,
          text: JSON.data.designation_name,
        });

        $("#designation_id option:last").prev().before($newOption);
        // Refresh the Select2 control to show the newly added option.
        $("#designation_id").val(JSON.data.id).trigger("change");
        Ladda.stopAll();
      }
    },
    error: function () {
      toastr.error(JSON.error);
      $('input[name="csrf_token"]').val(JSON.csrf_hash);
      Ladda.stopAll();
    },
  });
});

const addNewRole = () => {
  try {
    let value = $("#staff_role").val();
    if(value == 'add_new_role'){
      $('#role_modal').modal('show');
    }
  } catch (error) {
    console.log(error);
  }
};


$(document).ready(function(){
	$("#role_access").change(function(){
		var sel_val = $(this).val();
		if(sel_val=='1') {
			$('.role-checkbox').prop('checked', true);
		} else {
			$('.role-checkbox').prop("checked", false);
		}
	});
});


$("#xin-form-add_role").submit(function(e){
  var fd = new FormData(this);
  var obj = $(this), action = obj.attr('name');
  fd.append("is_ajax", 1);
  fd.append("type", 'add_record');
  fd.append("form", action);
  e.preventDefault();		
  $.ajax({
    url: e.target.action,
    type: "POST",
    data:  fd,
    contentType: false,
    cache: false,
    processData:false,
    success: function(JSON)
    {
      console.log(JSON)
      if (JSON.error != '') {
        toastr.error(JSON.error);
        $('input[name="csrf_token"]').val(JSON.csrf_hash);
        Ladda.stopAll();
      } else {
       
        $('input[name="csrf_token"]').val(JSON.csrf_hash);
        $('#xin-form-add_role')[0].reset(); // To reset form fields
        $('#role_modal').modal('hide');
        
        var $newOption = $("<option>", {
          value: JSON.data.id,
          text: JSON.data.role_name,
        });

        $("#staff_role option:last").prev().before($newOption);
        // Refresh the Select2 control to show the newly added option.
        $("#staff_role").val(JSON.data.id).trigger("change");

        Ladda.stopAll();
      }
    },
    error: function() 
    {
      toastr.error(JSON.error);
      $('input[name="csrf_token"]').val(JSON.csrf_hash);
        Ladda.stopAll();
    } 	        
   });
});

const addNewShift = () => {
  try {
    let value = $("#add_new_shift_id").val();
    if(value == 'add_new_shift'){
      $('#add_shift_modal').modal('show');
    }
  } catch (error) {
    console.log(error);
  }
};

$("#xin-form-add_shift").submit(function(e){
	var fd = new FormData(this);
	var obj = $(this), action = obj.attr('name');
	fd.append("is_ajax", 1);
	fd.append("type", 'add_record');
	fd.append("form", action);
	e.preventDefault();		
	$.ajax({
		url: e.target.action,
		type: "POST",
		data:  fd,
		contentType: false,
		cache: false,
		processData:false,
		success: function(JSON)
		{
			if (JSON.error != '') {
				toastr.error(JSON.error);
				$('input[name="csrf_token"]').val(JSON.csrf_hash);
				Ladda.stopAll();
			} else {
				$('input[name="csrf_token"]').val(JSON.csrf_hash);
				$('#xin-form-add_shift')[0].reset(); // To reset form fields
        var $newOption = $("<option>", {
          value: JSON.id,
          text: JSON.shift_name,
        });

        $("#add_new_shift_id option:last").prev().before($newOption);
        // Refresh the Select2 control to show the newly added option.
        $("#add_new_shift_id").val(JSON.id).trigger("change");
        $('#add_shift_modal').modal('hide');
				Ladda.stopAll();
			}
		},
		error: function() 
		{
			toastr.error(JSON.error);
			$('input[name="csrf_token"]').val(JSON.csrf_hash);
				Ladda.stopAll();
		} 	        
   });
});