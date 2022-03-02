(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/App/Calendar', ['exports', 'Site', 'Config'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Site'), require('Config'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Site, global.Config);
    global.AppCalendar = mod.exports;
  }
})(this, function (exports, _Site2, _Config) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.getInstance = exports.run = exports.AppCalendar = undefined;

  var _Site3 = babelHelpers.interopRequireDefault(_Site2);

  var Config = babelHelpers.interopRequireWildcard(_Config);

  var AppCalendar = function (_Site) {
    babelHelpers.inherits(AppCalendar, _Site);


    function AppCalendar() {
      babelHelpers.classCallCheck(this, AppCalendar);
      return babelHelpers.possibleConstructorReturn(this, (AppCalendar.__proto__ || Object.getPrototypeOf(AppCalendar)).apply(this, arguments));
    }

    babelHelpers.createClass(AppCalendar, [{
      key: 'processed',
      value: function processed() {
        babelHelpers.get(AppCalendar.prototype.__proto__ || Object.getPrototypeOf(AppCalendar.prototype), 'processed', this).call(this);

        this.$actionToggleBtn = $('.site-action-toggle');
        this.$addNewCalendarForm = $('#addNewCalendar').modal({
          show: false
        });

        this.handleFullcalendar();
        this.handleSelective();
        this.handleAction();
        this.handleListItem();
        this.handleEventList();
      }
    }, {
      key: 'handleFullcalendar',
      value: function handleFullcalendar() {




        var myOptions = {
          header: {
            left: null,
            center: 'prev,title,next',
            right: 'month,agendaWeek,agendaDay'
          },
          defaultDate: new Date(),
          selectable: true,
          selectHelper: true,
          select: function select() {
            $('#addNewEvent').modal('show');
          },

          editable: true,
          eventLimit: true,
          windowResize: function windowResize(view) {
            var width = $(window).outerWidth();
            var options = Object.assign({}, myOptions);
            options.events = view.calendar.getEventCache();
            options.aspectRatio = width < 667 ? 0.5 : 1.35;

            $('#holiday_calendar').fullCalendar('refresh');
            $('#holiday_calendar').fullCalendar(options);
          },
          eventClick: function eventClick(event) {

            console.log(event.training_location);
            var color = event.backgroundColor ? event.backgroundColor : Config.colors('blue', 600);
            $('#training_title').html(event.title);
            $('#training_period').html(event.training_period);           
            $('#training_location').html(event.training_location);
            $('#training_capacity').html(event.training_capacity);
            $('#showEventDetails').modal('show');
          },
         
          eventDragStart: function eventDragStart() {
            $('.site-action').data('actionBtn').show();
          },
          eventDragStop: function eventDragStop() {
            $('.site-action').data('actionBtn').hide();
          },

        //  events: testEvents,

          /*events: {
            url: 'holiday-list',
            cache: true,
            type: 'POST',
            dataType: "json", 
            data: {
                _token: $("#calendar_token").val(),
                red_clr: Config.colors('red', 600),
                green_clr: Config.colors('green', 600),
                cyan_clr: Config.colors('cyan', 600)
            }*/
            events: function (start, end, timezone, callback) { 
            var days = matchingDaysBetween(start, end, function (day) {
                return day.format('dddd') === 'Tuesday'; //test function
            });
            callback(days.map(function (day) { // map days to events
                return {
                    start: moment(day).hour(12),
                    end: moment(day).hour(14),
                    title: "lunch",
                };
            }));
        },


            success: function(response){ 
              $('#holiday_calendar').fullCalendar('refresh');
            //$('#calendar').fullCalendar('render');
              
            },
            error: function () {
                alert('There was an error while fetching events!');
            },
        },

          droppable: true
        };

        var _options = void 0;
        var myOptionsMobile = Object.assign({}, myOptions);

        myOptionsMobile.aspectRatio = 0.5;
        _options = $(window).outerWidth() < 667 ? myOptionsMobile : myOptions;

        $('#editNewEvent').modal();
        $('#holiday_calendar').fullCalendar(_options);
      }
    }, {
      key: 'handleSelective',
      value: function handleSelective() {

        var member = [{
          id: 'uid_1',
          name: 'Herman Beck',
          avatar: '../../../../global/portraits/1.jpg'
        }, {
          id: 'uid_2',
          name: 'Mary Adams',
          avatar: '../../../../global/portraits/2.jpg'
        }, {
          id: 'uid_3',
          name: 'Caleb Richards',
          avatar: '../../../../global/portraits/3.jpg'
        }, {
          id: 'uid_4',
          name: 'June Lane',
          avatar: '../../../../global/portraits/4.jpg'
        }];

        var items = [{
          id: 'uid_1',
          name: 'Herman Beck',
          avatar: '../../../../global/portraits/1.jpg'
        }, {
          id: 'uid_2',
          name: 'Caleb Richards',
          avatar: '../../../../global/portraits/2.jpg'
        }];

        $('.plugin-selective').selective({
          namespace: 'addMember',
          local: member,
          selected: items,
          buildFromHtml: false,
          tpl: {
            optionValue: function optionValue(data) {
              return data.id;
            },
            frame: function frame() {
              return '<div class="' + this.namespace + '">\n          ' + this.options.tpl.items.call(this) + '\n          <div class="' + this.namespace + '-trigger">\n          ' + this.options.tpl.triggerButton.call(this) + '\n          <div class="' + this.namespace + '-trigger-dropdown">\n          ' + this.options.tpl.list.call(this) + '\n          </div>\n          </div>\n          </div>';
            },
            triggerButton: function triggerButton() {
              return '<div class="' + this.namespace + '-trigger-button"><i class="wb-plus"></i></div>';
            },
            listItem: function listItem(data) {
              return '<li class="' + this.namespace + '-list-item"><img class="avatar" src="' + data.avatar + '">' + data.name + '</li>';
            },
            item: function item(data) {
              return '<li class="' + this.namespace + '-item"><img class="avatar" src="' + data.avatar + '" title="' + data.name + '">' + this.options.tpl.itemRemove.call(this) + '</li>';
            },
            itemRemove: function itemRemove() {
              return '<span class="' + this.namespace + '-remove"><i class="wb-minus-circle"></i></span>';
            },
            option: function option(data) {
              return '<option value="' + this.options.tpl.optionValue.call(this, data) + '">' + data.name + '</option>';
            }
          }
        });
      }
    }, {
      key: 'handleAction',
      value: function handleAction() {
        var _this2 = this;

        this.$actionToggleBtn.on('click', function (e) {
          _this2.$addNewCalendarForm.modal('show');
          e.stopPropagation();
        });
      }
    }, {
      key: 'handleEventList',
      value: function handleEventList() {
        $('#addNewEventBtn').on('click', function () {
          $('#addNewEvent').modal('show');
        });

        $('.calendar-list .calendar-event').each(function () {
          var $this = $(this),
              color = $this.data('color').split('-');
          $this.data('event', {
            title: $this.data('title'),
            stick: $this.data('stick'),
            backgroundColor: Config.colors(color[0], color[1]),
            borderColor: Config.colors(color[0], color[1])
          });
          $this.draggable({
            zIndex: 999,
            revert: true,
            revertDuration: 0,
            appendTo: '.page',
            helper: function helper() {
              return '<a class="fc-day-grid-event fc-event fc-start fc-end" style="background-color:' + Config.colors(color[0], color[1]) + ';border-color:' + Config.colors(color[0], color[1]) + '">\n          <div class="fc-content">\n            <span class="fc-title">' + $this.data('title') + '</span>\n          </div>\n          </a>';
            }
          });
        });
      }
    }, {
      key: 'handleListItem',
      value: function handleListItem() {
        this.$actionToggleBtn.on('click', function (e) {
          $('#addNewCalendar').modal('show');
          e.stopPropagation();
        });

        $(document).on('click', '[data-tag=list-delete]', function (e) {
          bootbox.dialog({
            message: 'Do you want to delete the calendar?',
            buttons: {
              success: {
                label: 'Delete',
                className: 'btn-danger',
                callback: function callback() {
                  // $(e.target).closest('.list-group-item').remove();
                }
              }
            }
          });
        });
      }
    }]);
    return AppCalendar;
  }(_Site3.default);

  var instance = null;

  function getInstance() {
    if (!instance) {
      instance = new AppCalendar();
    }
    return instance;
  }

  function run() {
    var app = getInstance();
    app.run();
  }

  exports.default = AppCalendar;
  exports.AppCalendar = AppCalendar;
  exports.run = run;
  exports.getInstance = getInstance;
});
