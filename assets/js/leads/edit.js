var form = document.querySelector('form#lead_edit');
var aliases = {
    firstname: 'First Name',
    lastname: 'Last Name',
    address: 'Address',
    city: 'City',
    state: 'State',
    zip: 'Postal Code',
    phone1: 'Cell Phone',
    email: 'Email',
    lead_source: 'Lead Source',
};
form.addEventListener("submit", function (e) {
    var values = validate.collectFormValues(form);
    var errors = validate(values, {
        firstname: {
            presence: true
        },
        lastname: {
            presence: true
        },
        address: {
            presence: true
        },
        city: {
            presence: true
        },
        state: {
            presence: true
        },
        zip: {
            presence: true
        },
        phone1: {
            presence: true
        },
        email: {
            email: true
        },
        lead_source: {
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        dumpster_status: {
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        materials_status: {
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        labor_status: {
            numericality: {
                notValid: ' contains invalid value'
            }
        }
    }, {
        format: 'flat',
        prettify: function prettify(string) {
            return aliases[string] || validate.prettify(string);
        }
    });
    if (errors) {
        e.preventDefault();
        displayValidationError(errors);
    }
});

var form_status = document.querySelector('form#lead_edit_status');
form_status.addEventListener("submit", function (e) {
    var values = validate.collectFormValues(form_status);
    var errors = validate(values, {
        status: {
            presence: true
        },
        category: {
            presence: true
        },
        type: {
            presence: true
        },
        classification: {
            presence: true
        }
    }, {
        format: 'flat',
        prettify: function prettify(string) {
            return aliases[string] || validate.prettify(string);
        }
    });
    if (errors) {
        e.preventDefault();
        displayValidationError(errors, 'validation-errors-status');
    }
});

var form_material_create = document.querySelector('form#lead_edit_add_material');
var aliases_material = {
    line_style_group: 'Line / Style / Group',
    po_no: 'PO #',
    projected_cost: 'Projected Cost',
    actual_cost: 'Actual Cost',
    installer_projected_cost: 'Installer Projected Cost',
    installer_actual_cost: 'Installer Actual Cost'
};
form_material_create.addEventListener("submit", function (e) {
    var values = validate.collectFormValues(form_material_create);
    var errors = validate(values, {
        material: {
            presence: true,
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        manufacturer: {
            presence: true
        },
        line_style_group: {
            presence: true
        },
        color: {
            presence: true
        },
        supplier: {
            presence: true
        },
        po_no: {
            presence: true,
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        projected_cost: {
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        actual_cost: {
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        installer: {
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        installer_projected_cost: {
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        installer_actual_cost: {
            numericality: {
                notValid: ' contains invalid value'
            }
        }
    }, {
        format: 'flat',
        prettify: function prettify(string) {
            return aliases_material[string] || validate.prettify(string);
        }
    });
    if (errors) {
        e.preventDefault();
        displayValidationError(errors, 'validation-errors-add_material');
    }
});

var form_material_edit = document.querySelector('form#lead_edit_edit_material');
form_material_edit.addEventListener("submit", function (e) {
    var values = validate.collectFormValues(form_material_edit);
    var errors = validate(values, {
        material: {
            presence: true,
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        manufacturer: {
            presence: true
        },
        line_style_group: {
            presence: true
        },
        color: {
            presence: true
        },
        supplier: {
            presence: true
        },
        po_no: {
            presence: true,
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        projected_cost: {
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        actual_cost: {
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        installer: {
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        installer_projected_cost: {
            numericality: {
                notValid: ' contains invalid value'
            }
        },
        installer_actual_cost: {
            numericality: {
                notValid: ' contains invalid value'
            }
        }
    }, {
        format: 'flat',
        prettify: function prettify(string) {
            return aliases_material[string] || validate.prettify(string);
        }
    });
    if (errors) {
        e.preventDefault();
        displayValidationError(errors, 'validation-errors-edit_material');
    }
});

$('#camera-uploads').change(function () {
    var file_data = $(this)[0].files;
    var jobid = $(this).data("jobid");
    var baseUrl = $(this).data("baseUrl");
    /*var len_files = file_data.length;
    var form_data = new FormData();
    for (var i = 0; i < len_files; i++) {
        form_data.append("photo[]", file_data[i]);
    }*/
    var len_files = file_data.length;
    for (var i = 0; i < len_files; i++) {
        var form_data = new FormData();
        form_data.append("photo[]", file_data[i]);
        $.ajax({
            url: baseUrl + 'lead/' + jobid + '/photo/upload', // point to server-side PHP script     
            dataType: 'text', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (php_script_response) {
                var obj = JSON.parse(php_script_response)
                if (obj.img && obj.img.length != 0) {
                    $.ajax({
                        type: 'POST',
                        url: baseUrl + 'lead/' + jobid + '/photo/save', // point to server-side PHP script     
                        data: {
                            id: jobid,
                            name: JSON.stringify(obj.img)
                        },
                        success: function (photoid) {
                            $('.camera-files-status div').show();
                        }
                    });
                } else if (obj.error) {
                    alert(obj.error);
                } else {
                    alert('Something went wrong!. File type not ok');
                }
            },
            error: function (jqXHR) {
                if (jqXHR.status == 413) {
                    alert('Large File, Max file size limit is 100MB.');
                } else {
                    alert('Something went wrong!. File type not ok');
                }
            }
        });
    }
});

$(document).ready(function () {
    $('#add_material-section form#lead_edit_add_material #material_create').select2({
        width: '100%'
    });
    $('#edit_material-section form#lead_edit_edit_material #material_edit').select2({
        width: '100%'
    });
    $('#add_material-section form#lead_edit_add_material #installer_create').select2({
        width: '100%'
    });
    $('#edit_material-section form#lead_edit_edit_material #installer_edit').select2({
        width: '100%'
    });

    $('.edit-material').click(function (e) {
        e.preventDefault();
        var material = $(this).data('material');
        $('#edit_material-section form#lead_edit_edit_material').trigger('reset');
        $('#edit_material-section form#lead_edit_edit_material select[name="material"]').val(material.material);
        $('#edit_material-section form#lead_edit_edit_material select[name="material"]').trigger('change');
        $('#edit_material-section form#lead_edit_edit_material input[name="manufacturer"]').val(material.manufacturer);
        $('#edit_material-section form#lead_edit_edit_material input[name="line_style_group"]').val(material.line_style_group);
        $('#edit_material-section form#lead_edit_edit_material input[name="color"]').val(material.color);
        $('#edit_material-section form#lead_edit_edit_material input[name="supplier"]').val(material.supplier);
        $('#edit_material-section form#lead_edit_edit_material input[name="po_no"]').val(material.po_no);
        $('#edit_material-section form#lead_edit_edit_material input[name="projected_cost"]').val(material.projected_cost);
        $('#edit_material-section form#lead_edit_edit_material input[name="actual_cost"]').val(material.actual_cost);
        $('#edit_material-section form#lead_edit_edit_material select[name="installer"]').val(material.installer);
        $('#edit_material-section form#lead_edit_edit_material select[name="installer"]').trigger('change');
        $('#edit_material-section form#lead_edit_edit_material input[name="installer_projected_cost"]').val(material.installer_projected_cost);
        $('#edit_material-section form#lead_edit_edit_material input[name="installer_actual_cost"]').val(material.installer_actual_cost);
        var form_url = $('#edit_material-section form#lead_edit_edit_material').data('url');
        $('#edit_material-section form#lead_edit_edit_material').attr('action', form_url + '/' + material.id);
        var delete_url = $('#edit_material-section form#lead_edit_edit_material #delete_material').data('url');
        $('#edit_material-section form#lead_edit_edit_material #delete_material').attr('href', delete_url + '/' + material.id);
        $('#edit_material-section').show();
        document.getElementById('edit_material-section').scrollIntoView();
    });

    $('.edit-material-cancel').click(function (e) {
        e.preventDefault();
        $('#edit_material-section form#lead_edit_edit_material').trigger('reset');
        $('#edit_material-section').hide();
    });
});
$('#sales_rep_id').select2({
	allowClear: true,
	placeholder: "Search Sales Rep",
	minimumInputLength: 3,
	ajax: {
		url: '/search/sales_rep',
		type: 'POST',
		delay: 250,
		dataType: 'json'
		// Additional AJAX parameters go here; see the end of this chapter for the full code of this example
	}
});
