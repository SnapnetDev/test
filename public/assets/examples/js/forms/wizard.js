/*!
 * remark (http://getbootstrapadmin.com/remark)
 * Copyright 2016 amazingsurge
 * Licensed under the Themeforest Standard Licenses
 */
(function(document, window, $) {
    'use strict';

    var Site = window.Site;

    $(document).ready(function($) {
        Site.run();
    });

    // Example Wizard Form
    // -------------------
    (function() {
        // set up formvalidation


        // init the wizard
        var defaults = Plugin.getDefaults("wizard");
        var options = $.extend(true, {}, defaults, {
            buttonsAppendTo: '.panel-body'
        });

        var wizard = $("#exampleWizardForm").wizard(options).data('wizard');

        // setup validator
        // http://formvalidation.io/api/#is-valid

    })();


    // Example Wizard Form Container
    // -----------------------------
    // http://formvalidation.io/api/#is-valid-container
    (function() {
        var defaults = Plugin.getDefaults("wizard");
        var options = $.extend(true, {}, defaults, {
            onInit: function() {
                $('#exampleWizardFormContainer').formValidation({
                    framework: 'bootstrap',
                    fields: {
                        email: {
                            validators: {
                                notEmpty: {
                                    message: 'The email is required'
                                },
                                regexp: {
                                    regexp: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                                    message: 'The email can only consist of alphabetical, number, dot and underscore'
                                }
                            }
                        },
                        password: {
                            validators: {
                                notEmpty: {
                                    message: 'The password is required'
                                },
                                stringLength: {
                                    min: 6,
                                    max: 30,
                                    message: 'The password must be more than 6 and less than 30 characters long'
                                },
                            }
                        },
                        last_name: {
                            validators: {
                                notEmpty: {
                                    message: 'The last name is required'
                                },
                                stringLength: {
                                    min: 2,
                                    max: 30,
                                    message: 'The last name must be more than 2 and less than 30 characters long'
                                },
                            }
                        },
                        first_name: {
                            validators: {
                                notEmpty: {
                                    message: 'The first name is required'
                                },
                                stringLength: {
                                    min: 2,
                                    max: 30,
                                    message: 'The first name must be more than  and less than 30 characters long'
                                },
                            }
                        },
                        company_name: {
                            validators: {
                                notEmpty: {
                                    message: 'The Company name is required'
                                },
                                stringLength: {
                                    min: 2,
                                    max: 30,
                                    message: 'The Company name must be more than 3  and less than 30 characters long'
                                },
                            }
                        },
                        company_address: {
                            validators: {
                                notEmpty: {
                                    message: 'The Company Address is required'
                                },
                                stringLength: {
                                    min: 2,
                                    max: 150,
                                    message: 'The Company Address must be more than 3  and less than 150 characters long'
                                },
                            }
                        },
                        company_email: {
                            validators: {
                                notEmpty: {
                                    message: 'The company email is required'
                                },
                                regexp: {
                                    regexp: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                                    message: 'The company email can only consist of alphabetical, number, dot and underscore'
                                }
                            }
                        },
                        gender: {
                            validators: {
                                notEmpty: {
                                    message: 'The gender field is required'
                                },

                            }
                        },
                        department: {
                            validators: {
                                notEmpty: {
                                    message: 'The department is required'
                                },
                                stringLength: {
                                    min: 2,
                                    max: 30,
                                    message: 'The department must be more than  and less than 30 characters long'
                                },
                            }
                        },
                        job_role: {
                            validators: {
                                notEmpty: {
                                    message: 'The job role is required'
                                },
                                stringLength: {
                                    min: 2,
                                    max: 30,
                                    message: 'The job role must be more than  and less than 30 characters long'
                                },
                            }
                        },
                        emp_num: {
                            validators: {
                                notEmpty: {
                                    message: 'The Employee Number field is required'
                                },
                                stringLength: {
                                    min: 2,
                                    max: 30,
                                    message: 'The Employee Number must be more than  and less than 30 characters long'
                                },
                            }
                        },

                    },
                    err: {
                        clazz: 'text-help'
                    },
                    row: {
                        invalid: 'has-danger'
                    }
                });
            },
            validator: function() {
                var fv = $('#exampleWizardFormContainer').data('formValidation');

                var $this = $(this);

                // Validate the container
                fv.validateContainer($this);

                var isValidStep = fv.isValidContainer($this);
                if (isValidStep === false || isValidStep === null) {
                    return false;
                }
                $('#e_first_name').html("First name: "+$('#first_name').val());
                $('#e_last_name').html("Last name: "+$('#last_name').val());
                $('#e_email').html("Email: "+$('#email').val());
                $('#e_emp_num').html("Employee Number: "+$('#emp_num').val());
                $('#e_gender').html("Gender: "+$('#gender').val());
                $('#e_hiredate').html("Hire Date: "+$('#hiredate').val());
                $('#e_grade').html("Grade: "+$('#grade').val());
                $('#e_department').html("Department: "+$('#department').val());
                $('#e_job_role').html("Job Role: "+$('#job_role').val());
                $('#e_company_name').html("Company Name: "+$('#company_name').val());
                $('#e_company_email').html("Company Email: "+$('#company_email').val());
                $('#e_company_address').html("Company Address: "+$('#company_address').val());
                return true;
            },
            onFinish: function() {
                $('#exampleFormContainer')[0].submit();
            },
            buttonsAppendTo: '.panel-body'
        });

        $("#exampleWizardFormContainer").wizard(options);
    })();

    // Example Wizard Pager
    // --------------------------
    (function() {
        var defaults = Plugin.getDefaults("wizard");

        var options = $.extend(true, {}, defaults, {
            step: '.wizard-pane',
            templates: {
                buttons: function() {
                    var options = this.options;
                    var html = '<div class="btn-group btn-group-sm btn-group-flat">' +
                        '<a class="btn btn-default" href="#' + this.id + '" data-wizard="back" role="button">' + options.buttonLabels.back + '</a>' +
                        '<a class="btn btn-success btn-outline pull-xs-right" href="#' + this.id + '" data-wizard="finish" role="button">' + options.buttonLabels.finish + '</a>' +
                        '<a class="btn btn-default btn-outline pull-xs-right" href="#' + this.id + '" data-wizard="next" role="button">' + options.buttonLabels.next + '</a>' +
                        '</div>';
                    return html;
                }
            },
            buttonLabels: {
                next: '<i class="icon md-chevron-right" aria-hidden="true"></i>',
                back: '<i class="icon md-chevron-left" aria-hidden="true"></i>',
                finish: '<i class="icon md-check" aria-hidden="true"></i>'
            },

            buttonsAppendTo: '.panel-actions'
        });

        $("#exampleWizardPager").wizard(options);
    })();

    // Example Wizard Progressbar
    // --------------------------
    (function() {
        var defaults = Plugin.getDefaults("wizard");

        var options = $.extend(true, {}, defaults, {
            step: '.wizard-pane',
            onInit: function() {
                this.$progressbar = this.$element.find('.progress-bar').addClass('progress-bar-striped');
            },
            onBeforeShow: function(step) {
                step.$element.tab('show');
            },
            onFinish: function() {
                this.$progressbar.removeClass('progress-bar-striped').addClass('progress-bar-success');
            },
            onAfterChange: function(prev, step) {
                var total = this.length();
                var current = step.index + 1;
                var percent = (current / total) * 100;

                this.$progressbar.css({
                    width: percent + '%'
                }).find('.sr-only').text(current + '/' + total);
            },
            buttonsAppendTo: '.panel-body'
        });

        $("#exampleWizardProgressbar").wizard(options);
    })();

    // Example Wizard Tabs
    // -------------------
    (function() {
        var defaults = Plugin.getDefaults("wizard");
        var options = $.extend(true, {}, defaults, {
            step: '> .nav > li > a',
            onBeforeShow: function(step) {
                step.$element.tab('show');
            },
            classes: {
                step: {
                    //done: 'color-done',
                    error: 'color-error'
                }
            },
            onFinish: function() {
                alert('finish');
            },
            buttonsAppendTo: '.tab-content'
        });

        $("#exampleWizardTabs").wizard(options);
    })();

    // Example Wizard Accordion
    // ------------------------
    (function() {
        var defaults = Plugin.getDefaults("wizard");
        var options = $.extend(true, {}, defaults, {
            step: '.panel-title[data-toggle="collapse"]',
            classes: {
                step: {
                    //done: 'color-done',
                    error: 'color-error'
                }
            },
            templates: {
                buttons: function() {
                    return '<div class="panel-footer">' + defaults.templates.buttons.call(this) + '</div>';
                }
            },
            onBeforeShow: function(step) {
                step.$pane.collapse('show');
            },

            onBeforeHide: function(step) {
                step.$pane.collapse('hide');
            },

            onFinish: function() {
                alert('finish');
            },

            buttonsAppendTo: '.panel-collapse'
        });

        $("#exampleWizardAccordion").wizard(options);
    })();

})(document, window, jQuery);
