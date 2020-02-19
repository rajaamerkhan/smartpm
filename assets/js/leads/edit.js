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
    lead_source: 'Lead Source'
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