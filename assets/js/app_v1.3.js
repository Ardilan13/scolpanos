/*
 *** XA Technologies & Madworks
 *** Front-end Developers: Rudy Croes
 *** Back-end Developers: Elbert Lumenier, Gildward Maduro
 */

/*
 *** Custom WebApi
 *** WebApi is assigned with the document_end.php WebApi scope
 *** $ is assigned to jQuery plugin (http://jquery.com)
 *** Modernizr is assigned to modernizr (https://modernizr.com/)
 *** window is assigned on the window
 *** document is assigned on the document
 *** undefined will catch errors if one of the above is not present
 */
var WebApi = (function projectInit(
  WebApi,
  $,
  Modernizr,
  window,
  document,
  undefined
) {
  /*
   *** Always use strict mode => 'use strict'.
   *** Read about strict mode here: http://ejohn.org/blog/ecmascript-5-strict-mode-json-and-more/
   */
  "use strict";

  /*
   *** Cache all your variables
   *** 1. For performance it's faster (Sizzle)
   *** 2. This will avoid re-querying
   *** 3. These cached variables are global, you can use them in every function and avoid point 1 and 2.
   *** 4. Use $ for variables, to clearly indicate that the variable contains a jQuery wrapped set
   */
  var $window = $(window),
    $document = $(document),
    $tabs = $(".nav-tabs"),
    $sliders = $(".slider"),
    $streetnames_streets = $("#streetnames"),
    $dataRequest = $(".table").attr("data-table"),
    $createcustomer = $("#createcustomer"),
    $canvasGraph = $(".canvasjs"),
    $modalBtn = $(".modal-btn"),
    $datepicker = $(".calendar"),
    $datepickerfutureday = $(".calendarfutureday"),
    $datepickerpast = $(".calendarpast"),
    $datepickerfull = $(".calendarfull"),
    $scrollContainer = $(".scroll-container"),
    $innerScrollContainer = $scrollContainer.find(".scroll-inner"),
    $dropdown = $(".dropdown-toggle"),
    $mobileNav = $("#mobile-nav"),
    $fullCalendar = $("#fullcalendar"),
    $toggleCalendar = $("#toggle-calendar"),
    $leerlingLogboek = $("#leerling-logboek"),
    $formDataRetriever = $(".form-data-retriever"),
    $dataRetrieverOnLoad = $(".dataRetrieverOnLoad"),
    $dataRetrieverOnClick = $(".dataRetrieverOnClick"),
    $formSubmission = $(".form-submission"),
    $editableField = $(".editable"),
    $formLogin = $("#form-signin-spn"),
    $formAddStudent = $("#form-addStudent1"),
    $verticalTabs = $(".nav-vertical"),
    $equalHeightsonDivs = $(".equal-height"),
    $multilevelNav = $(".multilevel"),
    $fixedFilterBar = $(".filter-bar"),
    $vakKeydown = $("#vak.table"),
    $invoicePaymentType = $("#invoicepaymenttype"),
    $dataSchoolOnLoad = $(".dataSchoolOnLoad"),
    $dataClassOnLoad = $(".dataClassOnLoad"),
    $frm_user_account = $("#frm_user_account"),
    $dataStudentOnLoad = $(".dataStudentOnLoad"),
    $dataStudentInvoiceOnLoad = $(".dataStudentInvoiceOnLoad"),
    $notificationTrigger = $("#notification-trigger"),
    $dataTable = $(".data-table"),
    $search = $("#search"),
    $telerikPlugin = $(".telerik-plugin"),
    // CaribeDevelopers

    $formSendMessageInbox = $("#form-sendmessage-inbox"),
    $dataUsersOnLoad = $(".dataUsersOnLoad"),
    $dataDocentOnLoad = $(".dataDocentOnLoad"),
    $formAddMDCRegistration = $("#form-add-mdc-registration-list"),
    $formAddSocialWork = $("#form-addsocialwork"), //CaribeDevelopers code add social work
    $datatablecontacts = $(".datatablecontacts"),
    $formAddContact = $("#form-addContact"),
    $dataUsersOnLoad = $(".dataUsersOnLoad"),
    $id_class = $("#klas"),
    $formAddTestRegistration = $("#form-add-test-registration-list"),
    $formAddEvent = $("#form-addevent"),
    $formAddCalendar = $("#form-add-calendar"),
    $formAddDaily = $("#form-add-daily"),
    $formAddplanner = $("#form-add-planner"),
    $formAddRemedial = $("#form-remedial"),
    $formAddRemedialDetail = $("#form-remedial-detail"),
    $divRemedialDetail = $("#div_remedial"),
    $divRemedialDetailDetail = $("#div_remedial_detail"),
    $linkReturnRemedial = $("#return-remedial"),
    $lblClassAvi = $("#lblClassAvi"),
    $dataClassAvi = $(".dataClassAvi"),
    $btnSearchByClass = $("#btn-search-by-class"),
    $fromAviRegistration = $("#form-avi"),
    $data_student_by_class = $("#data_student_by_class"),
    $invoice_type = $("#invoice_type"),
    $opslaan_toets = $("#opslaan-toets"),
    $form_vak = $("#form-vak"),
    $formDocumentUpload = $("#form-document"),
    $formAddSetting = $("#form-setting"),
    $formAddTodo = $("#form-todo");
  var cijfer_object = [],
    i_cijfer_array = 0,
    check_ls_data = 0,
    check_ss_data = 0,
    extra_cijfer_object = [],
    i_extra_cijfer_array = 0,
    i_btn_extra_save = 0,
    wrong_cells = [];

  // End code CaribeDevelopers

  $window.on("resize", function () {
    throttle(
      function () {
        initEqualHeights();
      },
      this,
      500
    );

    if ($window.width() < 321) {
      $mobileNav.addClass("animateback");

      $("#sub-nav").css({
        "margin-left": "-190px",
      });
      $(".push-content-220").css({
        "margin-left": 0,
      });
    } else {
      $("#sub-nav").css({
        "margin-left": 0,
      });
      $(".push-content-220").css({
        "margin-left": 190,
      });
    }
  });

  if ($search.length) {
    initAutoCompleteStudentList();
  }

  if ($("#suggestie-form").length) {
    $("#suggestie-form").on("submit", function (e) {
      e.preventDefault();

      $.post(
        "ajax/april4th_el_addsuggestion.php",
        { subject: $("#subject").val(), suggestion: $("#suggestion").val() },
        function (data) {
          alert("Thank you for your suggestion!");
        }
      );

      return false;
    });
  }

  if ($("#klassenboek-home").length) {
    $.get("ajax/april2nd_el_chart_klassenboek.php", function (data) {})
      .done(function (data) {
        $("#klassenboek-home").append(data);
      })
      .fail(function () {
        alert("error");
      });
  }

  // On document ready the document will load the following javascript
  $document.one("ready", function () {
    var $subNavigation = $("#sub-nav"),
      hrefLink = $(".dataRetrieverOnLoad").attr("data-ajax-href"),
      replaceDiv = $(".dataRetrieverOnLoad").attr("data-display");

    initEqualHeights();

    // If exists get JSON from the back-end and loop through the data
    if ($("#verzuim_klassen_lijst").length) {
      verzuimVakkenLijst();
    }

    if ($("#cijfers_klassen_lijst").length) {
      cijfersVakkenLijst();
    }
    if ($("#houding_klassen_lijst").length) {
      houdingVakkenLijst();
    }
    if ($("#toggle-koppenkaart").length) {
      $("#toggle-koppenkaart").on("click", function () {
        $(".listleerling").hide();
        $(".koppenkaart").show();

        $.get("ajax/getleerling_pics.php", {}, function (data) {
          $("#dataRequest-student_pic").html(data);
        });

        return false;
      });
    }

    if ($("#toggle-leerlinglist").length) {
      $("#toggle-leerlinglist").on("click", function () {
        $(".koppenkaart").hide();
        $(".listleerling").show();

        return false;
      });
    }

    if ($telerikPlugin.length) {
      Modernizr.load({
        test: $.kendoChart,
        nope: [
          WebApi.Config.baseUri + "assets/telerik/styles/kendo.common.min.css",
          WebApi.Config.baseUri + "assets/telerik/styles/kendo.default.min.css",
          WebApi.Config.baseUri + "assets/telerik/js/kendo.all.min.js",
        ],
        complete: function executeTelerik() {
          if ($("#graph-three-periods").length) {
            $("#graph-three-periods").kendoChart({
              dataSource: {
                transport: {
                  read: {
                    //url: "ajax/cijfers-graph.php",
                    url: "ajax/getcijfersgraph.php",
                    dataType: "json",
                  },
                },
                sort: {
                  field: "vakken",
                },
              },
              title: {
                text: "Gemiddelde van alle vakken in 3 periodes",
              },
              series: [
                {
                  field: "periode1",
                  name: "Periode 1",
                  color: "#8AD6E2",
                },
                {
                  field: "periode2",
                  name: "Periode 2",
                  color: "#FFDC66",
                },
                {
                  field: "periode3",
                  name: "Periode 3",
                  color: "#FF0044",
                },
              ],
              categoryAxis: {
                labels: {
                  rotation: {
                    angle: -65,
                  },
                },
                field: "vakken",
                majorGridLines: {
                  visible: false,
                },
              },
              valueAxis: {
                labels: {
                  format: "N0",
                },
                majorUnit: 1,
                plotBands: [
                  {
                    from: 0,
                    to: 5.5,
                    color: "#FF0044",
                    opacity: 0.2,
                  },
                ],
                max: 10,
                line: {
                  visible: false,
                },
              },
              tooltip: {
                visible: true,
                format: "Gemiddelde afgerond: {0:N0}",
              },
            });
          }

          if ($("#chart-pie-1").length) {
            $("#chart-pie-1").kendoChart({
              title: {
                position: "bottom",
                text: "Share of Internet Population Growth, 2007 - 2012",
              },
              legend: {
                visible: false,
              },
              chartArea: {
                background: "",
              },
              seriesDefaults: {
                labels: {
                  visible: true,
                  background: "transparent",
                  template: "#= category #: \n #= value#%",
                },
              },
              series: [
                {
                  type: "pie",
                  startAngle: 150,
                  data: [
                    {
                      category: "Asia",
                      value: 53.8,
                      color: "#9de219",
                    },
                    {
                      category: "Europe",
                      value: 16.1,
                      color: "#90cc38",
                    },
                    {
                      category: "Latin America",
                      value: 11.3,
                      color: "#068c35",
                    },
                    {
                      category: "Africa",
                      value: 9.6,
                      color: "#006634",
                    },
                    {
                      category: "Middle East",
                      value: 5.2,
                      color: "#004d38",
                    },
                    {
                      category: "North America",
                      value: 3.6,
                      color: "#033939",
                    },
                  ],
                },
              ],
              tooltip: {
                visible: true,
                format: "{0}%",
              },
            });
          }

          if ($("#chart-pie-2").length) {
            var dataSource = new kendo.data.DataSource({
              transport: {
                read: {
                  url: "ajax/april4th_el_skoadashboard_topxabsent.php",
                  dataType: "json",
                },
              },
            });
            $("#chart-pie-2").kendoChart({
              autoBind: false,
              dataSource: dataSource,
              series: [
                { field: "Count", type: "pie", categoryField: "Category" },
              ],
            });
            dataSource.read();
          }

          if ($("#chart").length) {
            var dataSource = new kendo.data.DataSource({
              transport: {
                read: {
                  url: "http://localhost:8888/scolpanos/ajax/april2nd_el_chart_cijfers.php",
                  dataType: "json",
                },
              },
            });

            $("#chart").kendoChart({
              autoBind: false,
              dataSource: dataSource,
              series: [
                {
                  field: "Gemiddelde",
                  type: "column",
                  color: "#ff0044",
                  labels: {
                    visible: true,
                  },
                },
              ],
              categoryAxis: {
                majorGridLines: {
                  visible: true,
                },
                labels: {
                  rotation: {
                    angle: -90,
                  },
                },
                categories: [
                  "Klas 1B",
                  "Klas 1B",
                  "Klas 1B",
                  "Klas 3A",
                  "Klas 3A",
                  "Klas 3A",
                  "Klas 6A",
                  "Klas 6A",
                  "Klas 6A",
                ],
              },
              tooltip: {
                visible: true,
                format: "{0}",
              },
            });

            dataSource.read();

            /*
            $.getJSON('ajax/april2nd_el_chart_cijfers.php', function( data ) {

          }).done(function(data) {

          $.each( data, function( key, elem ) {

          var $gemiddelde 	= data[key].Gemiddelde,
          $klas 			= data[key].Klas,
          $rapportNummer 	= data[key].RapNummer;

          console.log($klas);

          $("#chart").kendoChart({
          categoryAxis: [{
          labels: {
          rotation: {
          angle: -90
        }
      },
      categories: ['1a', '1b', '1c']
    }],


    series: [{
    data: [1, 2, 3, 9, 9, 6, 7, 8, 9],
    color: '#ffdc66' // rapport 1
  },
  {
  data: [1, 2, 3, 4, 5, 6, 7, 8, 9],
  color: '#8ad6e2' // rapport 2
},
{
data: [1, 2, 3, 4, 5, 6, 7, 8, 9],
color: '#ff0044' // rapport 3
}],
});
});


}).fail(function() {
alert( "error" );
});
*/
          }

          if ($("#chart-absent").length) {
            $.getJSON("ajax/april2nd_el_chart_absent.php", function (data) {})
              .done(function (data) {
                var $present = data[0].KlasPresent,
                  $absent = data[0].KlasAbsent;

                var series = [
                  {
                    legend: {
                      position: "bottom",
                    },
                  },
                  {
                    name: "Aanwezig",
                    data: [$present],
                    markers: { type: "square" },
                    color: "#ffdc66",
                  },
                  {
                    name: "Absent",
                    data: [$absent],
                    // Line chart marker type
                    color: "#ff0044",
                  },
                ];

                $("#chart-absent").kendoChart({
                  title: {
                    text: "Absentie",
                  },

                  seriesDefaults: {
                    type: "column",
                    stack: true,
                  },
                  chartArea: {
                    height: 200,
                  },
                  series: series,
                  valueAxis: {
                    line: {
                      visible: true,
                    },
                    majorUnit: 30,
                  },
                  categoryAxis: {
                    categories: ["Studenten"],
                    majorGridLines: {
                      visible: true,
                    },
                  },
                  tooltip: {
                    visible: true,
                    format: "{0}",
                  },
                });
              })
              .fail(function () {
                alert("error");
              });
          }

          if ($("#chart-laat").length) {
            $.getJSON("ajax/april2nd_el_chart_telaat.php", function (data) {})
              .done(function (data) {
                var $present = data[0].KlasPresent,
                  $teLaat = data[0].KlasTeLaat;

                var series = [
                  {
                    name: "Aanwezig",
                    data: [$present],
                    markers: { type: "square" },
                    color: "#ffdc66",
                  },
                  {
                    name: "Te Laat",
                    data: [$teLaat],
                    // Line chart marker type
                    color: "#ff0044",
                  },
                ];

                $("#chart-laat").kendoChart({
                  title: {
                    text: "Te laat",
                  },
                  seriesDefaults: {
                    type: "column",
                    stack: true,
                  },
                  chartArea: {
                    height: 200,
                  },
                  series: series,
                  valueAxis: {
                    line: {
                      visible: true,
                    },
                    majorUnit: 30,
                  },
                  categoryAxis: {
                    categories: ["Studenten"],
                    majorGridLines: {
                      visible: true,
                    },
                  },
                  tooltip: {
                    visible: true,
                    format: "{0}",
                  },
                });
              })
              .fail(function () {
                alert("error");
              });
          }

          if ($("#chart-uitgestuurd").length) {
            $.getJSON(
              "ajax/april2nd_el_chart_uitgestuurd.php",
              function (data) {}
            )
              .done(function (data) {
                var $present = data[0].KlasPresent,
                  $uitgestuurd = data[0].KlasUitgestuurd;

                var series = [
                  {
                    name: "Aanwezig",
                    data: [$present],
                    markers: { type: "square" },
                    color: "#ffdc66",
                  },
                  {
                    name: uit,
                    data: [$uitgestuurd],
                    // Line chart marker type
                    color: "#ff0044",
                  },
                ];

                $("#chart-uitgestuurd").kendoChart({
                  title: {
                    text: uit,
                  },
                  seriesDefaults: {
                    type: "column",
                    stack: true,
                  },
                  chartArea: {
                    height: 200,
                  },
                  series: series,
                  valueAxis: {
                    line: {
                      visible: true,
                    },
                    majorUnit: 30,
                  },
                  categoryAxis: {
                    categories: ["Studenten"],
                    majorGridLines: {
                      visible: true,
                    },
                  },
                  tooltip: {
                    visible: true,
                    format: "{0}",
                  },
                });
              })
              .fail(function () {
                alert("error");
              });
          }

          if ($("#chart-huiswerk").length) {
            $.getJSON(
              "ajax/april2nd_el_chart_geenhuiswerk.php",
              function (data) {}
            )
              .done(function (data) {
                var $present = data[0].KlasPresent,
                  $geenHuiswerk = data[0].KlasGeenHuiswerk;

                var series = [
                  {
                    name: "Aanwezig",
                    data: [$present],
                    markers: { type: "square" },
                    color: "#ffdc66",
                  },
                  {
                    name: "Geen Huiswerk",
                    data: [$geenHuiswerk],
                    // Line chart marker type
                    color: "#ff0044",
                  },
                ];
                $("#chart-huiswerk").kendoChart({
                  title: {
                    text: "Geen huiswerk",
                  },
                  seriesDefaults: {
                    type: "column",
                    stack: true,
                  },
                  chartArea: {
                    height: 200,
                  },
                  series: series,
                  valueAxis: {
                    line: {
                      visible: true,
                    },
                    majorUnit: 30,
                  },
                  categoryAxis: {
                    categories: ["Studenten"],
                    majorGridLines: {
                      visible: true,
                    },
                  },
                  tooltip: {
                    visible: true,
                    format: "{0}",
                  },
                });
              })
              .fail(function () {
                alert("error");
              });
          }

          if ($("#chart-financial").length) {
            var series = [
              {
                data: [0, 0, 0, 225, 0, 0, 0],
                color: "#ffdc66",
              },
            ];

            $("#chart-financial").kendoChart({
              title: {
                text: "Financieel",
              },
              seriesDefaults: {
                type: "column",
                stack: true,
              },
              chartArea: {
                height: 200,
              },
              series: series,
              valueAxis: {
                line: {
                  visible: false,
                },
                title: {
                  text: "Bedrag in AWG.",
                  visible: true,
                  font: "11px sans-serif",
                },
              },
              categoryAxis: {
                categories: ["0", "5", "10", "15", "20", "25", "30"],
                majorGridLines: {
                  visible: false,
                },
                title: {
                  text: "Aantal studenten",
                  visible: true,
                  font: "11px sans-serif",
                },
              },
              tooltip: {
                visible: true,
                format: "Schoolgeld: {0}",
              },
            });
          }

          if ($("#chart-aangemeld").length) {
            var series = [
              {
                name: "SMW",
                data: [225, 100, 250, 300, 350, 225],

                // Line chart marker type
                markers: { type: "square" },
                color: "#ffdc66",
              },
              {
                name: "MDC",
                data: [125, 400, 275, 232, 145, 146],
                color: "#ff0044",
              },
            ];

            $("#chart-aangemeld").kendoChart({
              title: {
                text: "SMW / MDC aangemeld",
              },
              legend: {
                position: "bottom",
              },
              seriesDefaults: {
                type: "column",
                stack: false,
              },
              chartArea: {
                height: 200,
              },
              series: [
                {
                  data: [1, 2, 3, 4, 5, 6],
                  color: "#ffdc66",
                },
                {
                  data: [1, 2, 3, 4, 5, 6],
                  color: "#ff0044",
                },
              ],
              valueAxis: {
                line: {
                  visible: false,
                },
              },
              categoryAxis: {
                categories: ["1", "2", "3", "4", "5", "6"],
                majorGridLines: {
                  visible: false,
                },
              },
              tooltip: {
                visible: true,
                format: "{0}",
              },
            });
          }
        },
      });
    }

    if ($dataTable.length) {
      dataTablePlugin();
    }

    if ($("#verzuim_klassen_lijst").length) {
      // cijfersVerzuimLijst();
    }

    if ($("#rapport_klassen_lijst").length) {
      cijfersRapportLijst();
    }

    $subNavigation.css({
      "min-height": $window.outerHeight(),
    });

    $leerlingLogboek.show();

    if ($leerlingLogboek.length) {
      $("#fullcalendar-container").hide();
    }

    if ($(".dataRetrieverOnLoad").length) {
      $(".dataRetrieverOnLoad").each(function (index, element) {
        var $subNavigation = $("#sub-nav"),
          hrefLink = $(this).attr("data-ajax-href"),
          replaceDiv = $(this).attr("data-display");

        $.get(hrefLink, function (data) {
          $("." + replaceDiv + "").html(data);
        })
          .done(function () {
            if ($("#dataRequest-student").length) {
              dataTablePlugin();
            }
          })
          .fail(function () {
            alert("error");
          });
      });
    }

    if ($("#table_invoice").length) {
      var hrefLink = $("#table_invoice").attr("data-ajax-href"),
        replaceDiv = $("#table_invoice").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#table_invoice_result").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    if ($("#lblschool").length) {
      var hrefLink = $("#lblschool .dataSchoolOnLoad").attr("data-ajax-href"),
        replaceDiv = $("#lblschool .dataSchoolOnLoad").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#data_school").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    if ($("#lblClass").length) {
      var hrefLink = $("#lblClass .dataClassOnLoad").attr("data-ajax-href"),
        replaceDiv = $("#lblClass .dataClassOnLoad").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#data_class").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    if ($("#lblDocent").length) {
      var hrefLink = $("#lblDocent .dataDocentOnLoad").attr("data-ajax-href"),
        replaceDiv = $("#lblDocent .dataDocentOnLoad").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#data_docent").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    if ($("#lblKlassen").length) {
      var hrefLink = $("#lblKlassen .dataKlassenOnLoad").attr("data-ajax-href"),
        replaceDiv = $("#lblKlassen .dataKlassenOnLoad").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#data_klassen").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    if ($("#student").length) {
      var hrefLink = $("#student .dataStudentOnLoad").attr("data-ajax-href"),
        replaceDiv = $("#student .dataStudentOnLoad").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#data_student").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    if ($("#invoice").length) {
      var hrefLink = $("#invoice .dataStudentInvoiceOnLoad").attr(
          "data-ajax-href"
        ),
        replaceDiv = $("#invoice .dataStudentInvoiceOnLoad").attr(
          "data-display"
        );

      $.get(hrefLink, function (data) {
        $("#data_student_invoice").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    // Begin code CaribeDevelopers

    if ($("#unread_messages").length) {
      var hrefLink = $("#unread_messages").attr("data-ajax-href"),
        replaceDiv = $("#unread_messages").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#count_unread_messages").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }
    if ($("#unread_notifications").length) {
      var hrefLink = $("#unread_notifications").attr("data-ajax-href"),
        replaceDiv = $("#unread_notifications").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#count_unread_notifications").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }
    if ($("#table_messages").length) {
      var hrefLink = $("#table_messages").attr("data-ajax-href"),
        replaceDiv = $("#table_messages").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#table_messages_result").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    if ($("#table_message_detail").length) {
      var hrefLink = $("#table_message_detail").attr("data-ajax-href"),
        replaceDiv = $("#table_message_detail").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#table_message_result_detail").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    if ($("#lblUsers").length) {
      var hrefLink = $("#lblUsers .dataUsersOnLoad").attr("data-ajax-href"),
        replaceDiv = $("#lblUsers .dataUsersOnLoad").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#data_users").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    /* Notifications*/
    if ($("#table_notifications").length) {
      var hrefLink = $("#table_notifications").attr("data-ajax-href"),
        replaceDiv = $("#table_notifications").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#table_notifications_result_").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    if ($("#tbl_birthdaylist").length) {
      var hrefLink = $("#tbl_birthdaylist").attr("data-ajax-href"),
        replaceDiv = $("#tbl_birthdaylist").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#tbl_birthdaylist_result").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    $divRemedialDetailDetail.hide();

    if ($("#table_avi_detail").length) {
      var hrefLink = $("#table_avi_detail").attr("data-ajax-href"),
        replaceDiv = $("#table_avi_detail").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#table_avi_result_detail").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    /* Avi Class control */
    if ($("#lblClassAvi").length) {
      var hrefLink = $("#lblClassAvi .dataClassAvi").attr("data-ajax-href"),
        replaceDiv = $("#lblClassAvi .dataClassAvi").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#class_list_result").html(data);
        // console.log(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    /* Avi Level control */
    if ($("#lblLevelAvi").length) {
      var hrefLink = $("#lblLevelAvi .dataLevelAvi").attr("data-ajax-href"),
        replaceDiv = $("#lblLevelAvi .dataLevelAvi").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#level_list_result").html(data);
        // console.log(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    $(function () {
      $("#list_class_search").on("click", "li", function () {
        // here I want to get the clicked id of the li (e.g. bakkerLink)
        var id = this.id;
        $("#class_search").val(id);
      });
    });

    $btnSearchByClass.on("click", function () {
      $.post(
        "ajax/getaviregister_tabel.php",
        { class: $("#class_search").val() },
        function (data) {
          $("#table_avi_result_detail").empty();
          $("#table_avi_result_detail").append(data);
        }
      );
    });

    //User Account table
    if ($("#tbl_user_account").length) {
      var hrefLink = $("#tbl_user_account").attr("data-ajax-href"),
        replaceDiv = $("#tbl_user_account").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#tbl_list_users_accounts").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }

    if ($("#tbl_group_hs").length) {
      var hrefLink = $("#tbl_group_hs").attr("data-ajax-href"),
        replaceDiv = $("#tbl_group_hs").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#tbl_list_groups_hs_").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }
    if ($("#tbl_le_vakken").length) {
      var hrefLink = $("#tbl_le_vakken").attr("data-ajax-href"),
        replaceDiv = $("#tbl_le_vakken").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#tbl_list_le_vakken").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }
    // End code CaribeDevelopers
    if ($("#tbl_settings").length) {
      var hrefLink = $("#tbl_settings").attr("data-ajax-href"),
        replaceDiv = $("#tbl_settings").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#dataRequest-setting-detail").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }
    // End code CaribeDevelopers
    if ($("#div_todo_list").length) {
      var hrefLink = $("#div_todo_list").attr("data-ajax-href"),
        replaceDiv = $("#div_todo_list").attr("data-display");

      $.get(hrefLink, function (data) {
        $("#div_todo_list_detail").html(data);
      })
        .done(function () {})
        .fail(function () {
          alert("error");
        });
    }
  });

  $notificationTrigger.on("click", function () {
    // console.log('get the dynamic notification');
  });

  $("#cijfers_klassen_lijst").on("change", function () {
    $("#cijfers_vakken_lijst").empty();
    var $klas = $(this).val();

    $.getJSON("ajax/getvakken_json.php", { klas: $klas }, function (result) {
      var vak = $("#cijfers_vakken_lijst");

      $.each(result, function () {
        vak.append($("<option />").val(this.id).text(this.vak));
      });
    });
  });

  $("#kalendar_klassen_lijst").on("change", function () {
    $("#kalendar_vakken_lijst").empty();
    var $klas = $(this).val();

    $.getJSON("ajax/getvakken_json.php", { klas: $klas }, function (result) {
      var vak = $("#kalendar_vakken_lijst");

      $.each(result, function () {
        vak.append($("<option />").val(this.id).text(this.vak));
      });
    });
  });

  $("#verzuim_klassen_lijst").on("change", function () {
    $("#verzuim_vakken_lijst").empty();
    var $klas = $(this).val();

    $.getJSON("ajax/getvakken_json.php", { klas: $klas }, function (result) {
      var vak = $("#verzuim_vakken_lijst");

      $.each(result, function () {
        vak.append($("<option />").val(this.id).text(this.vak));
      });
    });
  });

  $("#houding_klassen_lijst").on("change", function () {
    $("#houding_vakken_lijst").empty();
    var $klas = $(this).val();

    $.getJSON("ajax/getvakken_json.php", { klas: $klas }, function (result) {
      var vak = $("#houding_vakken_lijst");

      $.each(result, function () {
        vak.append($("<option />").val(this.id).text(this.vak));
      });
    });
  });

  /* Caribe Dev
$('#cijfers_vakken_lijst').on('change', function() { //CaribeDeveloper
$('#cijfers_vakken_lijst').empty();
var $vak = $(this).val();
$.getJSON("ajax/getvakken_json.php", { vak : $vak } ,function(result){
var vak = $("#cijfers_vakken_lijst");
$.each(result, function(){
vak.append($("<option />").val(this.id).text(this.vak));
});
});
});*/

  $invoicePaymentType.on("change", function () {
    var valInvoice = $("#invoicepaymenttype option:selected").val();

    if (valInvoice == "Payment") {
      $("#invoice").removeClass("hidden");
      $("#lblinvoice_type").addClass("hidden");
      $("#student").removeClass("hidden");
      $("#lblClass").removeClass("hidden");
      $("#lbldudate").addClass("hidden");

      $.post("ajax/getlistschool.php", {}, function (data) {
        $("#data_school").html(data);

        /*class change*/
        $.post(
          "ajax/getlistclass.php",
          { idSchool: $("#invoicepaymentschool option:selected").val() },
          function (data1) {
            $("#data_class").html(data1);

            //         $.post("ajax/getlistpaymentstudent.php", { id_student : $("#data_student_by_class option:selected").val()}, function(data2) {
            //           alert(id_student);
            //         $("#invoice_by_student").html(data2);
            // });
          }
        );
      });
    } else {
      $("#invoice").addClass("hidden");
      $("#lblinvoice_type").removeClass("hidden");
      $("#lbldudate").removeClass("hidden");

      $.post("ajax/getlistschool.php", {}, function (data) {
        $("#data_school").html(data);

        /*class change*/
        $.post(
          "ajax/getlistclass.php",
          { idSchool: $("#invoicepaymentschool option:selected").val() },
          function (data1) {
            $("#data_class").html(data1);

            /*Student change*/
            // $.post("ajax/getliststudent.php", { idSchool : $("#invoicepaymentschool option:selected").val(), idClass : $("#invoicepaymentclass option:selected").val() }, function(data2) {
            //     $("#data_student").html(data2);
            // });
          }
        );
      });
    }
  });
  //Luis Bello for caribe developers
  $data_student_by_class.on("change", function () {
    var varCClass = $("#data_student_by_class option:selected").val();
    $.post(
      "ajax/getlistpaymentstudent.php",
      {
        idStudentInvoice: varCClass,
      },

      function (data) {
        $("#select_invoice").html(data);
      }
    );
  }); //Close data_student_by_class Change

  $invoice_type.on("change", function () {
    var valInvoiceType = $("#invoice_type option:selected").val();

    if (valInvoiceType === "3") {
      $("#lblClass").addClass("hidden");
      $("#student").addClass("hidden");
      $("#lblschool").removeClass("hidden");
    } else {
      if (valInvoiceType === "1") {
        //Single Student

        $("#student").addClass("hidden");
        $("#lblClass").removeClass("hidden");
        $("#lblschool").addClass("hidden");
      } else {
        $("#student").removeClass("hidden");
        $("#lblClass").removeClass("hidden");
        $("#lblschool").addClass("hidden");
      }
    }
  });

  // BEGIN CaribeDevelopers

  $formSendMessageInbox.on("submit", function (e) {
    /* prevent refresh */
    e.preventDefault();

    if (
      $("#idSchool").val().length === 0 ||
      $("#subject_message").val().length === 0 ||
      $("#message").val().length === 0 ||
      $("#selectedto").val().length === 0
    ) {
      $(this)
        .find(".alert-danger")
        .removeClass("hidden")
        .delay(3000)
        .queue(function (next) {
          $(this).addClass("hidden");
        });
      setTimeout(function () {
        $formSendMessageInbox.find(".alert-danger").addClass("hidden");
      }, 3000);
      $(this).find(".alert-info").addClass("hidden");
    } else {
      /* prevent refresh */
      e.preventDefault();

      /* begin post */
      $.ajax({
        url: "ajax/sendmessageinbox.php",
        data: $formSendMessageInbox.serialize(),
        type: "POST",
        dataType: "text",
        success: function (text) {
          if (text != "1") {
            $("#btn-clear-message").trigger("click");
            $formSendMessageInbox
              .find(".alert-error")
              .removeClass("hidden")
              .delay(3000)
              .queue(function (next) {
                $(this).addClass("hidden");
              });
            setTimeout(function () {
              $formSendMessageInbox.find(".alert-error").addClass("hidden");
            }, 3000);
            $formSendMessageInbox.find(".alert-info").addClass("hidden");
            $formSendMessageInbox.find(".alert-warning").addClass("hidden");
            $("#message").val("");
            $("#subject_message").val("");
            $("#count_users_selected").val("");
            $("#users_selected").val("");
            $("#selectedto").val("");
            $("#users").prop("selectedIndex", "-1");
            // $('#data_users').val('');
            $("#data_users").prop("selectedIndex", 0);

            $("input[type=text]").each(function () {
              $(this).val("");
            });
          } else if (text == "1") {
            $("#btn-clear-message").trigger("click");
            $formSendMessageInbox
              .find(".alert-info")
              .removeClass("hidden")
              .delay(3000)
              .queue(function (next) {
                $(this).addClass("hidden");
              });
            setTimeout(function () {
              $formSendMessageInbox.find(".alert-info").addClass("hidden");
            }, 3000);
            $formSendMessageInbox.find(".alert-error").addClass("hidden");
            $formSendMessageInbox.find(".alert-warning").addClass("hidden");
            $("#message").val("");
            $("#subject_message").val("");
            $("#count_users_selected").val("");
            $("#users_selected").val("");
            $("#selectedto").val("");

            // $('#data_users').val('');
            $("#users").prop("selectedIndex", "-1");

            //  document.getElementById("btn-clear-message").click();

            $("input[type=text]").each(function () {
              $(this).val("");
            });

            $.get("ajax/getinboxmessages_tabel.php", function (data) {
              $("#table_messages_result").empty();
              $("#table_messages_result").append(data);
            });
          }
        },
        error: function (xhr, status, errorThrown) {
          console.log("error");
        },
        complete: function (xhr, status) {
          // var $message_selectedto     = $('#selectedto').val(),
          //     $subject_message        = $('#subject_message').val(),
          //     $message                = $('#message').val();
          //
          //     var t = new Date();
          // 		var ftime = ((t.getHours()+1)<10 ? '0' : '') + (t.getHours()) + ':' + ((t.getMinutes()+1)<10 ? '0' : '') + (t.getMinutes()) + ':' + (t.getSeconds()<10 ? '0' : '') + t.getSeconds();
          //
          // 		var i = 0;
          // 		var icon_colors = "";
          // 		var from_message = "me";
          // 		var message_inbox = "Lo que sea";
          //
          // 		icon_colors = ["blue", "green","grey","red"];
          //
          // 		//$('#dataRequest-message tbody').prepend('<tr><td>'+ $leerlingNR +'</td><td>'+ $voornaam +'</td><td>'+ $achternaam +'</td><td>'+ $geslacht +'</td><td>'+ $geboortedatum +'</td><td>'+ $klas +'</td><td></</tr>');
          // 		$('#dataRequest-message tbody').prepend("<tr><td><h4><span class=\"" + icon_colors[i] + "\"</span>" + from_message.substring(0, 1).toUpperCase() + "</h4></td><td>" + from_message + "</td><td><b>" + $subject_message + "</b><br>" + $message.substring(0,50) +"</td><td>" + ftime + "</td></</tr>");
        },
      });
    }
  });

  $formAddMDCRegistration.on("submit", function (e) {
    /* prevent refresh */
    e.preventDefault();

    if (
      $("#id_student").val().length === 0 ||
      $("#mdc_school_year").val().length === 0 ||
      $("#mdc_date").val().length === 0 ||
      $("#mdc_reason").val().length === 0 ||
      $("#mdc_class").attr("placeholder").length === 0
    ) {
      $(this).find(".alert-error").removeClass("hidden");
    } else {
      /* prevent refresh */
      e.preventDefault();

      if ($("input:radio[id=mdc_no_pending]").prop("checked"))
        $("#mdc_pending_selected").val(0);
      else $("#mdc_pending_selected").val(1);

      $("#mdc_reason_text").val($("#mdc_reason option:selected").text());
      $("#mdc_class_value").val($("#mdc_class").attr("placeholder"));

      if ($("#id_mdc").val() == "0") {
        // ADD a MDC

        /* begin post */
        $.ajax({
          url: "ajax/addmdc.php",
          data: $formAddMDCRegistration.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text <= 0) {
              $formAddMDCRegistration
                .find(".alert-error")
                .removeClass("hidden");
              $formAddMDCRegistration.find(".alert-info").addClass("hidden");
              $formAddMDCRegistration.find(".alert-warning").addClass("hidden");
            } else {
              alert("MDC added successfully!");
              $.get(
                "ajax/getmdclist_tabel.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_mdc").empty();
                  $("#div_table_mdc").append(data);
                }
              );
              $("#id_mdc").val("0");
              $("#btn-clear-mdc").text("CLEAR");
              // Clear all object of form except class
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#mdc_class").val($id_class.html());
              $("#mdc_observation").val("");
              $formAddMDCRegistration.find(".alert-error").addClass("hidden");
              $formAddMDCRegistration.find(".alert-info").addClass("hidden");
              $formAddMDCRegistration.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
          },
        });
      } else {
        // UPDATE a MDC

        /* begin post */

        $.ajax({
          url: "ajax/updatemdc.php",
          data: $formAddMDCRegistration.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text <= 0) {
              $formAddMDCRegistration
                .find(".alert-error")
                .removeClass("hidden");
              $formAddMDCRegistration.find(".alert-info").addClass("hidden");
              $formAddMDCRegistration.find(".alert-warning").addClass("hidden");
            } else {
              alert("MDC updated successfully!");
              $.get(
                "ajax/getmdclist_tabel.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_mdc").empty();
                  $("#div_table_mdc").append(data);
                }
              );
              $("#id_mdc").val("0");
              $("#btn-clear-mdc").text("CLEAR");
              // Clear all object of form except class
              $("input[type=text]").each(function () {
                if (
                  $(this).attr("disabled") == false ||
                  typeof $(this).attr("disabled") == "undefined"
                ) {
                  $(this).val("");
                }
              });
              $("#mdc_class").val($id_class.html());
              $("#mdc_observation").val("");

              $("#mdc_observation").val("");
              $formAddMDCRegistration.find(".alert-error").addClass("hidden");
              $formAddMDCRegistration.find(".alert-info").addClass("hidden");
              $formAddMDCRegistration.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
          },
        });
      }
    }
  });

  $formAddMDCRegistration.on("reset", function (e) {
    if ($("#btn-clear-mdc").text().toUpperCase() == "CLEAR") {
      $("#id_mdc").val("0");
      $("#btn-clear-mdc").text("CLEAR");
      // Clear all object of form except class
      $("input[type=text]").each(function () {
        $(this).val("");
      });
      $("#mdc_class").val($id_class.html());
      $("#mdc_observation").val("");
      $formAddMDCRegistration.find(".alert-error").addClass("hidden");
      $formAddMDCRegistration.find(".alert-info").addClass("hidden");
      $formAddMDCRegistration.find(".alert-warning").addClass("hidden");
    } else {
      // DELETE a MDC
      var r = confirm("Delete MDC?");
      if (r == true) {
        /* begin post */
        $.ajax({
          url: "ajax/deletemdc.php",
          data: $formAddMDCRegistration.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text != "1") {
              $formAddMDCRegistration
                .find(".alert-error")
                .removeClass("hidden");
              $formAddMDCRegistration.find(".alert-info").addClass("hidden");
              $formAddMDCRegistration.find(".alert-warning").addClass("hidden");
            } else if (text == "1") {
              alert("MDC deleted successfully!");
              $.get(
                "ajax/getmdclist_tabel.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_mdc").empty();
                  $("#div_table_mdc").append(data);
                }
              );
              $("#id_test").val("0");
              $("#btn-clear-mdc").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#mdc_class").val($id_class.html());
              $("#test_class_value").val("");
              $formAddMDCRegistration.find(".alert-error").addClass("hidden");
              $formAddMDCRegistration.find(".alert-info").addClass("hidden");
              $formAddMDCRegistration.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
          },
        });
      } else {
        $("#btn-clear-mdc").text("CLEAR");
      }
    }
  });

  //CaribeDevelopers code test

  $formAddTestRegistration.on("submit", function (e) {
    /* prevent refresh */
    e.preventDefault();

    if (
      $("#id_student").val().length === 0 ||
      $("#test_date").val().length === 0 ||
      $("#test_type").val().length === 0 ||
      $("#test_class").attr("placeholder").length === 0
    ) {
      $(this).find(".alert-error").removeClass("hidden");
    } else {
      /* prevent refresh */
      e.preventDefault();

      $("#test_class_value").val($("#test_class").attr("placeholder"));

      if ($("#id_test").val() == "0") {
        // ADD a TEST

        /* begin post */
        $.ajax({
          url: "ajax/addtest.php",
          data: $formAddTestRegistration.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text != "1") {
              //alert('Error');
              $formAddTestRegistration
                .find(".alert-error")
                .removeClass("hidden");
              $formAddTestRegistration.find(".alert-info").addClass("hidden");
              $formAddTestRegistration
                .find(".alert-warning")
                .addClass("hidden");
            } else if (text == "1") {
              alert("Test add successfully!");
              $.get(
                "ajax/gettestlist_tabel.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_test").empty();
                  $("#div_table_test").append(data);
                }
              );
              $("#id_test").val("0");
              $("#btn-clear-test").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#test_class").val($id_class.html());
              $("#test_observation").val("");
              $formAddTestRegistration.find(".alert-error").addClass("hidden");
              $formAddTestRegistration.find(".alert-info").addClass("hidden");
              $formAddTestRegistration
                .find(".alert-warning")
                .addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
          },
        });
      } else {
        // UPDATE a Test

        /* begin post */

        $.ajax({
          url: "ajax/updatetest.php",
          data: $formAddTestRegistration.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text != "1") {
              $formAddTestRegistration
                .find(".alert-error")
                .removeClass("hidden");
              $formAddTestRegistration.find(".alert-info").addClass("hidden");
              $formAddTestRegistration
                .find(".alert-warning")
                .addClass("hidden");
            } else if (text == "1") {
              alert("Test update successfully!");
              $.get(
                "ajax/gettestlist_tabel.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_test").empty();
                  $("#div_table_test").append(data);
                }
              );
              $("#id_test").val("0");
              $("#btn-clear-test").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#test_class").val($id_class.html());
              $("#test_observation").val("");
              $formAddTestRegistration.find(".alert-error").addClass("hidden");
              $formAddTestRegistration.find(".alert-info").addClass("hidden");
              $formAddTestRegistration
                .find(".alert-warning")
                .addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
          },
        });
      }
    }
  });

  $formAddTestRegistration.on("reset", function (e) {
    if ($("#btn-clear-test").text().toUpperCase() == "CLEAR") {
      $("#id_test").val("0");
      $("#btn-clear-test").text("CLEAR");
      // Clear all object of form
      $("input[type=text]").each(function () {
        $(this).val("");
      });
      $("#test_class").val($id_class.html());
      $("#test_class_value").val("");
      $formAddTestRegistration.find(".alert-error").addClass("hidden");
      $formAddTestRegistration.find(".alert-info").addClass("hidden");
      $formAddTestRegistration.find(".alert-warning").addClass("hidden");
    } else {
      // DELETE a TEST
      var r = confirm("Delete Test?");
      if (r == true) {
        /* begin post */
        $.ajax({
          url: "ajax/deletetest.php",
          data: $formAddTestRegistration.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text != "1") {
              $formAddTestRegistration
                .find(".alert-error")
                .removeClass("hidden");
              $formAddTestRegistration.find(".alert-info").addClass("hidden");
              $formAddTestRegistration
                .find(".alert-warning")
                .addClass("hidden");
            } else if (text == "1") {
              alert("Test deleted successfully!");
              $.get(
                "ajax/gettestlist_tabel.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_test").empty();
                  $("#div_table_test").append(data);
                }
              );
              $("#id_test").val("0");
              $("#btn-clear-test").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#test_class").val($id_class.html());
              $("#test_class_value").val("");
              $formAddTestRegistration.find(".alert-error").addClass("hidden");
              $formAddTestRegistration.find(".alert-info").addClass("hidden");
              $formAddTestRegistration
                .find(".alert-warning")
                .addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
          },
        });
      } else {
        $("#btn-clear-test").text("CLEAR");
      }
    }
  });

  // CaribeDevelopers code add social work
  $formAddSocialWork.on("submit", function (e) {
    /* prevent refresh */
    e.preventDefault();

    if (
      $("#social_work_school_year").val().length === 0 ||
      $("#social_work_date").val().length === 0 ||
      $("#social_work_reason").val().length === 0 ||
      $("#social_work_class").attr("placeholder").length === 0 ||
      $("#social_work_observation").val().length === 0 ||
      $("#id_student").val().length === 0
    ) {
      $(this).find(".alert-error").removeClass("hidden");
    } else {
      /* prevent refresh */
      e.preventDefault();
      if ($("#social_work_pending").is(":checked")) {
        $("#social_work_pending_selected").val("1");
      } else {
        $("#social_work_pending_selected").val("0");
      }

      $("#social_work_class_hidden").val(
        $("#social_work_class").attr("placeholder")
      );

      /* begin post */
      if ($("#id_social_work").val() == "0") {
        $.ajax({
          url: "ajax/addsocialwork.php",
          data: $formAddSocialWork.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text <= 0) {
              $formAddSocialWork.find(".alert-error").removeClass("hidden");
              $formAddSocialWork.find(".alert-info").addClass("hidden");
              $formAddSocialWork.find(".alert-warning").addClass("hidden");
            } else {
              alert("Social Work added successfully!");
              $.get(
                "ajax/getsocialworklist_table.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_social_work").empty();
                  $("#div_table_social_work").append(data);
                }
              );
              $("#id_social_work").val("0");
              $("#btn-clear-social-work").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#social_work_class").val($id_class.html());
              $("#social_work_observation").val("");
              $formAddSocialWork.find(".alert-error").addClass("hidden");
              $formAddSocialWork.find(".alert-info").addClass("hidden");
              $formAddSocialWork.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
          },
        });
      } else {
        $.ajax({
          url: "ajax/updatesocialwork.php",
          data: $formAddSocialWork.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text != "1") {
              $formAddSocialWork.find(".alert-error").removeClass("hidden");
              $formAddSocialWork.find(".alert-info").addClass("hidden");
              $formAddSocialWork.find(".alert-warning").addClass("hidden");
            } else if (text == "1") {
              alert("Social Work updated successfully!");
              $.get(
                "ajax/getsocialworklist_table.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_social_work").empty();
                  $("#div_table_social_work").append(data);
                }
              );
              $("#id_social_work").val("0");
              $("#btn-clear-social-work").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#social_work_class").val($id_class.html());
              $("#social_work_observation").val("");
              $formAddSocialWork.find(".alert-error").addClass("hidden");
              $formAddSocialWork.find(".alert-info").addClass("hidden");
              $formAddSocialWork.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
          },
        });
      }
    }
  });

  //Delete social work
  $formAddSocialWork.on("reset", function (e) {
    if ($("#btn-clear-social-work").text().toUpperCase() == "CLEAR") {
      $("#id_social_work").val("0");
      $("#btn-clear-social-work").text("CLEAR");
      // Clear all object of form
      $("input[type=text]").each(function () {
        $(this).val("");
      });
      $("#social_work_class").val($id_class.html());
      $("#social_work_observation").val("");
      $formAddSocialWork.find(".alert-error").addClass("hidden");
      $formAddSocialWork.find(".alert-info").addClass("hidden");
      $formAddSocialWork.find(".alert-warning").addClass("hidden");
    } else {
      // DELETE a Social Work
      var r = confirm("Delete Social Work?");
      if (r == true) {
        /* begin post */
        $.ajax({
          url: "ajax/deletesocialwork.php",
          data: $formAddSocialWork.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text.trim() != "1") {
              $formAddSocialWork.find(".alert-error").removeClass("hidden");
              $formAddSocialWork.find(".alert-info").addClass("hidden");
              $formAddSocialWork.find(".alert-warning").addClass("hidden");
            } else if (text.trim() == "1") {
              alert("Social Work deleted successfully!");
              $.get(
                "ajax/getsocialworklist_table.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_social_work").empty();
                  $("#div_table_social_work").append(data);
                }
              );
              $("#id_social_work").val("0");
              $("#btn-clear-social-work").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#social_work_class").val($id_class.html());
              $("#social_work_observation").val("");
              $formAddSocialWork.find(".alert-error").addClass("hidden");
              $formAddSocialWork.find(".alert-info").addClass("hidden");
              $formAddSocialWork.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
          },
        });
      } else {
        $("#btn-clear-social-work").text("CLEAR");
      }
    }
  });

  // CaribeDevelopers codde add contact

  $formAddContact.on("submit", function (e) {
    e.preventDefault();

    if (
      $("#tutor").val().length === 0 ||
      $("#type").val().length === 0 ||
      $("#full_name").val().length === 0
    ) {
      //Show Alerts and back on top LB
      $formAddContact.find(".alert-error").removeClass("hidden");
      // $("#contactpersoon").scroll();
      $("html, body").animate(
        { scrollTop: $("#detaill_seccion_top").offset().top },
        2000
      );
      return false;
    } else {
      /* prevent refresh */

      e.preventDefault();
      /* begin post */
      if ($("#id_contact").val() == "0") {
        $.ajax({
          url: "ajax/addcontact.php",
          data: $formAddContact.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text.trim() != "1") {
              $formAddContact.find(".alert-error").removeClass("hidden");
              $formAddContact.find(".alert-info").addClass("hidden");
              $formAddContact.find(".alert-warning").addClass("hidden");
            } else if (text.trim() == "1") {
              alert("Contact added successfully!");
              $.get(
                "ajax/getcontactlist_table.php",
                { id: $("#id_family").val() },
                function (data) {
                  $("#div_table_contact").empty();
                  $("#div_table_contact").append(data);
                }
              );
              $("#id_contact").val("0");
              $("#btn-clear-contact").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#observation").val("");
              $('#tutor option:contains("Select Tutor")').attr(
                "selected",
                "selected"
              );
              $('#type option:contains("Select Type")').attr(
                "selected",
                "selected"
              );
              $("#email").val("");
              $formAddContact.find(".alert-error").addClass("hidden");
              $formAddContact.find(".alert-info").addClass("hidden");
              $formAddContact.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },

          complete: function (xhr, status) {
            // $("#contactpersoon").scroll();
            $("html, body").animate(
              { scrollTop: $("#detaill_seccion_top").offset().top },
              "fast"
            );
            // $('html, body').animate({scrollTop:100}, 'fast');
            return false;
          },
        });
      } else {
        $.ajax({
          url: "ajax/updatecontact.php",
          data: $formAddContact.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text.trim() != "1") {
              $formAddContact.find(".alert-error").removeClass("hidden");
              $formAddContact.find(".alert-info").addClass("hidden");
              $formAddContact.find(".alert-warning").addClass("hidden");
            } else if (text.trim() == "1") {
              alert("Contact updated successfully!");
              $.get(
                "ajax/getcontactlist_table.php",
                { id: $("#id_family").val() },
                function (data) {
                  $("#div_table_contact").empty();
                  $("#div_table_contact").append(data);
                }
              );
              $("#id_contact").val("0");
              $("#btn-clear-contact").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#observation").val("");
              $('#tutor option:contains("Select Tutor")').attr(
                "selected",
                "selected"
              );
              $('#type option:contains("Select Type")').attr(
                "selected",
                "selected"
              );
              $("#email").val("");
              $formAddContact.find(".alert-error").addClass("hidden");
              $formAddContact.find(".alert-info").addClass("hidden");
              $formAddContact.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },

          complete: function (xhr, status) {
            // $("#contactpersoon").scroll();
            $("html, body").animate(
              { scrollTop: $("#detaill_seccion_top").offset().top },
              "fast"
            );
            // $('html, body').animate({scrollTop:100}, 'fast');
            return false;
          },
        });
      }
    }
  });

  $formAddContact.on("reset", function (e) {
    if ($("#btn-clear-contact").text().toUpperCase() == "CLEAR") {
      $("#id_contact").val("0");
      $formAddContact.find(".alert-error").addClass("hidden");
      $formAddContact.find(".alert-info").addClass("hidden");
      $formAddContact.find(".alert-warning").addClass("hidden");
      $("#btn-clear-contact").text("CLEAR");
      // Clear all object of form
      $("input[type=text]").each(function () {
        $(this).val("");
      });
      $("#observation").val("");
      $('#tutor option:contains("Select Tutor")').attr("selected", "selected");
      $('#type option:contains("Select Type")').attr("selected", "selected");
      $("#email").val("");
    } else {
      // DELETE a Contact
      var r = confirm("Delete Contact?");
      if (r == true) {
        /* begin post */
        $.ajax({
          url: "ajax/deletecontact.php",
          data: $formAddContact.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text == "0") {
              $formAddContact.find(".alert-error").removeClass("hidden");
              $formAddContact.find(".alert-info").addClass("hidden");
              $formAddContact.find(".alert-warning").addClass("hidden");
            } else if (text != "0") {
              alert("Contact deleted successfully!");
              $.get(
                "ajax/getcontactlist_table.php",
                { id: $("#id_family").val() },
                function (data) {
                  $("#div_table_contact").empty();
                  $("#div_table_contact").append(data);
                }
              );
              $("#id_contact").val("0");
              $("#btn-clear-contact").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#observation").val("");
              $('#tutor option:contains("Select Tutor")').attr(
                "selected",
                "selected"
              );
              $('#type option:contains("Select Type")').attr(
                "selected",
                "selected"
              );
              $("#email").val("");
              $formAddContact.find(".alert-error").addClass("hidden");
              $formAddContact.find(".alert-info").addClass("hidden");
              $formAddContact.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            // $("#contactpersoon").scroll();
            $("html, body").animate(
              { scrollTop: $("#detaill_seccion_top").offset().top },
              "fast"
            );
            // $('html, body').animate({scrollTop:0}, 'fast');
          },
        });
      } else {
        $("#btn-clear-contact").text("CLEAR");
      }
    }
  });
  // CaribeDevelopers

  // CaribeDevelopers Event (Janio Acero)

  $formAddEvent.on("submit", function (e) {
    e.preventDefault();

    if ($("#reason").val().length === 0) {
      //Show Alerts and back on top LB
      $formAddEvent.find(".alert-error").removeClass("hidden");
      $("html, body").animate({ scrollTop: 0 }, "fast");
      return false;
    } else {
      /* prevent refresh */

      e.preventDefault();
      /* begin post */

      if ($("#event_private").is(":checked")) {
        $("#event_private_selected").val("1");
      } else {
        $("#event_private_selected").val("0");
      }

      if ($("#important_notice").is(":checked")) {
        $("#important_notice_selected").val("1");
      } else {
        $("#important_notice_selected").val("0");
      }

      if ($("#id_event").val() == "0") {
        $.ajax({
          url: "ajax/add_event.php",
          data: $formAddEvent.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text.trim() != "1") {
              $formAddEvent.find(".alert-error").removeClass("hidden");
              $formAddEvent.find(".alert-info").addClass("hidden");
              $formAddEvent.find(".alert-warning").addClass("hidden");
            } else if (text.trim() == "1") {
              alert("Event added successfully!");
              $.get(
                "ajax/getevent.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_event").empty();
                  $("#div_table_event").append(data);
                }
              );
              $("#id_event").val("0");
              $("#btn-clear-event").val("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#observation_event").val("");
              $formAddEvent.find(".alert-error").addClass("hidden");
              $formAddEvent.find(".alert-info").addClass("hidden");
              $formAddEvent.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },

          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
            return false;
          },
        });
      } else {
        $.ajax({
          url: "ajax/update_event.php",
          data: $formAddEvent.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text.trim() != "1") {
              $formAddEvent.find(".alert-error").removeClass("hidden");
              $formAddEvent.find(".alert-info").addClass("hidden");
              $formAddEvent.find(".alert-warning").addClass("hidden");
            } else if (text.trim() == "1") {
              alert("Event updated successfully!");
              $.get(
                "ajax/getevent.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_event").empty();
                  $("#div_table_event").append(data);
                }
              );
              $("#id_event").val("0");
              $("#btn-clear-event").val("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#observation_event").val("");
              $formAddEvent.find(".alert-error").addClass("hidden");
              $formAddEvent.find(".alert-info").addClass("hidden");
              $formAddEvent.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },

          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
            return false;
          },
        });
      }
    }
  });

  $formAddEvent.on("reset", function (e) {
    if ($("#btn-clear-event").val().toUpperCase() == "CLEAR") {
      $("#id_event").val("0");
      $("#btn-clear-event").val("CLEAR");
      // Clear all object of form
      $("input[type=text]").each(function () {
        $(this).val("");
      });
      $("#observation_event").val("");
      $formAddEvent.find(".alert-error").addClass("hidden");
      $formAddEvent.find(".alert-info").addClass("hidden");
      $formAddEvent.find(".alert-warning").addClass("hidden");
    } else {
      // DELETE a Social Work
      var r = confirm("Delete Event?");
      if (r == true) {
        /* begin post */
        $.ajax({
          url: "ajax/delete_event.php",
          data: $formAddEvent.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text.trim() != "1") {
              $formAddEvent.find(".alert-error").removeClass("hidden");
              $formAddEvent.find(".alert-info").addClass("hidden");
              $formAddEvent.find(".alert-warning").addClass("hidden");
            } else if (text.trim() == "1") {
              alert("Event deleted successfully!");
              $.get(
                "ajax/getevent.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_event").empty();
                  $("#div_table_event").append(data);
                }
              );
              $("#id_event").val("0");
              $("#btn-clear-event").val("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#observation_event").val("");
              $formAddEvent.find(".alert-error").addClass("hidden");
              $formAddEvent.find(".alert-info").addClass("hidden");
              $formAddEvent.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
          },
        });
      } else {
        $("#btn-clear-event").val("CLEAR");
      }
    }
  });

  // End Event (Janio Acero)

  // Calendar (Janio y Yosnel)
  $formAddCalendar.on("submit", function (e) {
    e.preventDefault();
    // var pattern = /^[a-zA-Z0-9,. ]*$/;
    var pattern = false;
    //var not_allowed_commands = ["?", "%", "'","&", "+", "-", "$"]
    var not_allowed_commands = ["%", "'", "+", "-", "$"];

    for (var i = 0, len = not_allowed_commands.length; i < len; ++i) {
      if (
        $("#calendar_observation").val().includes(not_allowed_commands[i]) ||
        $("#calendar_observation").val().indexOf("'") >= 0 ||
        $("#calendar_observation").val().indexOf('"') >= 0
      ) {
        pattern = true;
      }
    }

    if (pattern) {
      $formAddCalendar.find(".alert-error-bad-charecter").removeClass("hidden");
      $("html, body").animate({ scrollTop: 0 }, "fast");
    } else {
      if ($("#calendar_date").val().length === 0) {
        //Show Alerts and back on top LB
        $formAddCalendar.find(".alert-error").removeClass("hidden");
        $("html, body").animate({ scrollTop: 0 }, "fast");
      } else {
        /* begin post */
        if ($("#id_calendar").val() == "0") {
          $.ajax({
            url: "ajax/add_calendar.php",
            // data: $formAddCalendar.serialize(),
            // type: "POST",
            // data: new FormData(this),
            // dataType: "text",
            cache: false,
            contentType: false,
            processData: false,
            data: new FormData(this),
            type: "POST",
            success: function (text) {
              if (text.trim() == "0") {
                $formAddCalendar.find(".alert-error").removeClass("hidden");
                $formAddCalendar.find(".alert-info").addClass("hidden");
                $formAddCalendar.find(".alert-warning").addClass("hidden");
              } else {
                alert("Calendar added successfully!");
                $("#id_calendar").val("0");
                // Clear all object of form
                $("input[type=text]").each(function () {
                  $(this).val("");
                });
                $("#calendar_observation").val("");
                $formAddCalendar.find(".alert-error").addClass("hidden");
                $formAddCalendar.find(".alert-info").addClass("hidden");
                $formAddCalendar.find(".alert-warning").addClass("hidden");
                location.reload();
              }
            },
            error: function (xhr, status, errorThrown) {
              console.log("error");
            },
            complete: function (xhr, status) {
              $("html, body").animate({ scrollTop: 0 }, "fast");
              return false;
            },
          });
        }
      }
    }
  });

  // End Calendar (Janio y Yosnel)

  // Planner Janio
  $formAddplanner.on("submit", function (e) {
    e.preventDefault();
    var pattern = /^[a-zA-Z0-9,. ]*$/;
    if (
      $("#planner_week").val().length === 0 ||
      $("#cijfers_klassen_lijst").val() == "NONE" ||
      !pattern.test($("#planner_observation").val())
    ) {
      //Show Alerts and back on top LB
      $("#warning_planner").removeClass("hidden");
      $("#warning_planner").fadeOut(3500);
      $("html, body").animate({ scrollTop: 0 }, "fast");
      return false;
    } else {
      /* prevent refresh */

      e.preventDefault();
      /* begin post */

      if ($("#id_planner").val() == "0") {
        $.ajax({
          url: "ajax/add_planner.php",
          data: $formAddplanner.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text.trim() != "1") {
              $formAddplanner.find(".alert-error").removeClass("hidden");
              $formAddplanner.find(".alert-info").addClass("hidden");
              $formAddplanner.find(".alert-warning").addClass("hidden");
            } else {
              alert("Planner added successfully!");
              $("#id_planner").val("0");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#planner_observation").val("");
              $formAddplanner.find(".alert-error").addClass("hidden");
              $formAddplanner.find(".alert-info").addClass("hidden");
              $formAddplanner.find(".alert-warning").addClass("hidden");
              location.reload();
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },

          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
            return false;
          },
        });
      } else {
      }
    }
  });
  // End Planner Janio
  // Daily Yosnel
  $formAddDaily.on("submit", function (e) {
    e.preventDefault();
    var pattern = /^[a-zA-Z0-9,. ]*$/;
    $("#end_date_time").val($("#start_date_time").val());
    if (
      $("#start_date_time").val().length === 0 ||
      $("#end_date_time").val().length === 0 ||
      $("#cijfers_klassen_lijst").val() == "NONE" ||
      new Date($("#end_date_time").val()) <
        new Date($("#start_date_time").val()) ||
      ($("#end_date_time").val() === $("#start_date_time").val() &&
        new Date("1/1/1900 " + $("#daily_time_end").val()) <
          new Date("1/1/1900 " + $("#daily_time_start").val())) ||
      !pattern.test($("#daily_observation").val())
    ) {
      //Show Alerts and back on top LB
      $formAddDaily.find(".alert-error").removeClass("hidden");
      $("html, body").animate({ scrollTop: 0 }, "fast");
      $formAddDaily.find(".alert-error").fadeOut(3000);
      return false;
    } else {
      /* prevent refresh */

      e.preventDefault();
      /* begin post */
      if ($("#end_date_time").val().length === 0) {
        //Show Alerts and back on top LB
        $formAddDaily.find(".alert-error").removeClass("hidden");
        $("html, body").animate({ scrollTop: 0 }, "fast");
        return false;
      } else {
        /* prevent refresh */

        e.preventDefault();
        /* begin post */

        if ($("#id_daily").val() == "0") {
          $.ajax({
            url: "ajax/add_daily.php",
            data: $formAddDaily.serialize(),
            type: "POST",
            dataType: "text",
            success: function (text) {
              if (text.trim() != "1") {
                $formAddDaily.find(".alert-error").removeClass("hidden");
                $formAddDaily.find(".alert-info").addClass("hidden");
                $formAddDaily.find(".alert-warning").addClass("hidden");
              } else {
                alert("Daily added successfully!");
                $("#id_daily").val("0");
                // Clear all object of form
                $("input[type=text]").each(function () {
                  $(this).val("");
                });
                $("#daily_observation").val("");
                $formAddDaily.find(".alert-error").addClass("hidden");
                $formAddDaily.find(".alert-info").addClass("hidden");
                $formAddDaily.find(".alert-warning").addClass("hidden");
                location.reload();
              }
            },
            error: function (xhr, status, errorThrown) {
              console.log("error");
            },

            complete: function (xhr, status) {
              $("html, body").animate({ scrollTop: 0 }, "fast");
              return false;
            },
          });
        } else {
        }
      }
    }
  });

  // End Daily Yosnel

  // CaribeDevelopers (Lerrin Aldana) Begin code Remedial

  $formAddRemedial.on("submit", function (e) {
    /* prevent refresh */
    e.preventDefault();

    if (
      $("#id_student").val().length === 0 ||
      $("#remedial_school_year").val().length === 0 ||
      $("#remedial_begin_date").val().length === 0 ||
      $("#remedial_end_date").val().length === 0 ||
      $("#remedial_subject").val().length === 0
    )
      $(this).find(".alert-error").removeClass("hidden");
    //alert('Faltan datos');
    else {
      var bd = $("#remedial_begin_date").val().split("-");
      if (bd[0].length < 2) bd[0] = "0" + bd[0];
      if (bd[1].length < 2) bd[1] = "0" + bd[1];
      var bdate = new Date(bd[2], bd[1] - 1, bd[0]);

      var ed = $("#remedial_end_date").val().split("-");
      if (ed[0].length < 2) ed[0] = "0" + ed[0];
      if (ed[1].length < 2) ed[1] = "0" + ed[1];
      var edate = new Date(ed[2], ed[1] - 1, ed[0]);

      if (bdate <= edate) {
        /* prevent refresh */
        e.preventDefault();

        $("#remedial_class_value").val(
          $("#remedial_class").attr("placeholder")
        );
        $("#remedial_docent_value").val(
          $("#remedial_docent").attr("placeholder")
        );

        if ($("#id_remedial").val() == "0") {
          // Add a remedial

          /* begin post */
          $.ajax({
            url: "ajax/addremedial.php",
            data: $formAddRemedial.serialize(),
            type: "POST",
            dataType: "text",
            success: function (text) {
              if (text != 1) {
                $formAddRemedial.find(".alert-error").removeClass("hidden");
                $formAddRemedial.find(".alert-info").addClass("hidden");
                $formAddRemedial.find(".alert-warning").addClass("hidden");
                $formAddRemedial.find(".alert-constrain").addClass("hidden");
                $formAddRemedial.find(".alert-date").addClass("hidden");
              } else if (text == 1) {
                alert("Remedial add successfully!");
                $.get(
                  "ajax/getremediallist_tabel.php",
                  { id: $("#id_student").val() },
                  function (data) {
                    $("#div_table_remedial").empty();
                    $("#div_table_remedial").append(data);
                    //$("#div_table_remedial").append("<a type='submit' name='btn_leerling_detail_remedial_print' id='btn_leerling_detail_remedial_print'class='btn btn-default btn-m-w pull-right mrg-left' target='_blank'>PRINT</a>")
                  }
                );
                $("#id_remedial").val("0");
                $("#btn-clear-remedial").text("CLEAR");
                // Clear all object of form
                $("input[type=text]").each(function () {
                  $(this).val("");
                });
                $("#remedial_class").val($id_class.html());
                $formAddRemedial.find(".alert-info").removeClass("hidden");
                $formAddRemedial.find(".alert-error").addClass("hidden");
                $formAddRemedial.find(".alert-warning").addClass("hidden");
                $formAddRemedial.find(".alert-constrain").addClass("hidden");
                $formAddRemedial.find(".alert-date").addClass("hidden");
              }
            },
            error: function (xhr, status, errorThrown) {
              console.log("error");
            },
            complete: function (xhr, status) {
              $("html, body").animate({ scrollTop: 0 }, "fast");
              setTimeout(function () {
                $(".alert-info").fadeOut(1500);
                $(".alert-error").fadeOut(1500);
                $(".alert-warning").fadeOut(1500);
                $(".alert-constrain").fadeOut(1500);
                $(".alert-date").fadeOut(1500);
              }, 3000);
            },
          });
        } else {
          // UPDATE a Remedial

          /* begin post */

          $.ajax({
            url: "ajax/updateremedial.php",
            data: $formAddRemedial.serialize(),
            type: "POST",
            dataType: "text",
            success: function (text) {
              if (text != 1) {
                $formAddRemedial.find(".alert-error").removeClass("hidden");
                $formAddRemedial.find(".alert-info").addClass("hidden");
                $formAddRemedial.find(".alert-warning").addClass("hidden");
                $formAddRemedial.find(".alert-constrain").addClass("hidden");
              } else if (text == 1) {
                alert("Remedial update successfully!");
                $.get(
                  "ajax/getremediallist_tabel.php",
                  { id: $("#id_student").val() },
                  function (data) {
                    $("#div_table_remedial").empty();
                    $("#div_table_remedial").append(data);
                  }
                );
                $("#btn-clear-remedial").text("CLEAR");
                // Clear all object of form
                $("input[type=text]").each(function () {
                  $(this).val("");
                });
                $("#remedial_class").val($id_class.html());
                $formAddRemedial.find(".alert-info").removeClass("hidden");
                $formAddRemedial.find(".alert-error").addClass("hidden");
                $formAddRemedial.find(".alert-warning").addClass("hidden");
                $formAddRemedial.find(".alert-constrain").addClass("hidden");
              }
            },
            error: function (xhr, status, errorThrown) {
              console.log("error");
            },
            complete: function (xhr, status) {
              $("html, body").animate({ scrollTop: 0 }, "fast");
              $("#id_remedial_post").val("0");
              $("#id_remedial").val("0");
              setTimeout(function () {
                $(".alert-info").fadeOut(1500);
                $(".alert-error").fadeOut(1500);
                $(".alert-warning").fadeOut(1500);
                $(".alert-constrain").fadeOut(1500);
                $(".alert-date").fadeOut(1500);
              }, 3000);
            },
          });
        }
      } else {
        $formAddRemedial.find(".alert-date").removeClass("hidden");
        $formAddRemedial.find(".alert-info").addClass("hidden");
        $formAddRemedial.find(".alert-error").addClass("hidden");
        $formAddRemedial.find(".alert-warning").addClass("hidden");
        $formAddRemedial.find(".alert-constrain").addClass("hidden");
        setTimeout(function () {
          $(".alert-date").fadeOut(1500);
        }, 3000);
      }
    }
  });

  // Remedial Delete & Clear
  $formAddRemedial.on("reset", function (e) {
    if ($("#btn-clear-remedial").text().toUpperCase() == "CLEAR") {
      $("#id_remedial").val("0");
      $("#id_remedial_post").val("0");
      $("#btn-clear-remedial").text("CLEAR");
      // Clear all object of form
      $("input[type=text]").each(function () {
        $(this).val("");
      });
      $("#remedial_class").val($id_class.html());
      $("#remedial_class_value").val("");
    } else {
      var r = confirm("Delete Remedial?");
      if (r) {
        /* begin post */
        $.ajax({
          url: "ajax/deleteremedial.php",
          data: $formAddRemedial.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text == 1451) {
              alert("Constrain");
              $formAddRemedial.find(".alert-constrain").removeClass("hidden");
              $formAddRemedial.find(".alert-error").addClass("hidden");
              $formAddRemedial.find(".alert-info").addClass("hidden");
              $formAddRemedial.find(".alert-warning").addClass("hidden");
              $formAddRemedial.find(".alert-date").addClass("hidden");
            } else if (text != 1) {
              $formAddRemedial.find(".alert-error").removeClass("hidden");
              $formAddRemedial.find(".alert-info").addClass("hidden");
              $formAddRemedial.find(".alert-warning").addClass("hidden");
              $formAddRemedial.find(".alert-constrain").addClass("hidden");
              $formAddRemedial.find(".alert-date").addClass("hidden");
            } else if (text == 1) {
              alert("Remedial deleted successfully!");
              $.get(
                "ajax/getremediallist_tabel.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_remedial").empty();
                  $("#div_table_remedial").append(data);
                }
              );
              $("#id_remedial").val("0");
              $("#btn-clear-remedial").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#remedial_class").val($id_class.html());
              $("#remedial_class_value").val("");
              $formAddRemedial.find(".alert-error").addClass("hidden");
              $formAddRemedial.find(".alert-info").addClass("hidden");
              $formAddRemedial.find(".alert-warning").addClass("hidden");
              $formAddRemedial.find(".alert-constrain").addClass("hidden");
              $formAddRemedial.find(".alert-date").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
            $("#id_remedial_post").val("0");
            $("#id_remedial").val("0");
            $("#btn-clear-remedial").text("CLEAR");
            setTimeout(function () {
              $(".alert-info").fadeOut(1500);
              $(".alert-error").fadeOut(1500);
              $(".alert-warning").fadeOut(1500);
              $(".alert-constrain").fadeOut(1500);
              $(".alert-date").fadeOut(1500);
            }, 3000);
          },
        });
      } else {
        $("#btn-clear-remedial").text("CLEAR");
      }
    }
  });

  $formAddRemedialDetail.on("submit", function (e) {
    /* prevent refresh */
    e.preventDefault();

    if (
      $("#id_remedial_post").val().length === 0 ||
      $("#remedial_detail_observation").val().length === 0 ||
      $("#remedial_detail_date").val().length === 0
    )
      $(this).find(".alert-error").removeClass("hidden");
    //alert('Faltan datos');
    else {
      /* prevent refresh */
      e.preventDefault();

      if ($("#id_remedial_detail").val() == "0") {
        // Add a remedial

        /* begin post */
        $.ajax({
          url: "ajax/addremedialdetail.php",
          data: $formAddRemedialDetail.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text != 1) {
              $formAddRemedialDetail.find(".alert-error").removeClass("hidden");
              $formAddRemedialDetail.find(".alert-info").addClass("hidden");
              $formAddRemedialDetail.find(".alert-warning").addClass("hidden");
            } else if (text == 1) {
              alert("Remedial Detail add successfully!");
              $.get(
                "ajax/getremedialdetaillist_tabel.php",
                { id_remedial: $("#id_remedial_post").val() },
                function (data) {
                  $("#div_table_remedial_detail").empty();
                  $("#div_table_remedial_detail").append(data);
                }
              );
              $("#id_remedial_detail").val("0");
              $("#btn-clear-remedial-detail").text("CLEAR");
              // Clear all object of form
              $("#remedial_detail_date").val("");
              $("#remedial_detail_observation").val("");

              $formAddRemedialDetail.find(".alert-info").removeClass("hidden");
              $formAddRemedialDetail.find(".alert-error").addClass("hidden");
              $formAddRemedialDetail.find(".alert-warning").addClass("hidden");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
            setTimeout(function () {
              $(".alert-info").fadeOut(1500);
              $(".alert-error").fadeOut(1500);
              $(".alert-warning").fadeOut(1500);
            }, 3000);
          },
        });
      } else {
        // UPDATE a Remedial Detail

        /* begin post */
        $.ajax({
          url: "ajax/updateremedialdetail.php",
          data: $formAddRemedialDetail.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text != 1) {
              $formAddRemedialDetail.find(".alert-error").removeClass("hidden");
              $formAddRemedialDetail.find(".alert-info").addClass("hidden");
              $formAddRemedialDetail.find(".alert-warning").addClass("hidden");
            } else if (text == 1) {
              alert("Remedial detail update successfully!");
              $.get(
                "ajax/getremedialdetaillist_tabel.php",
                { id_remedial: $("#id_remedial_post").val() },
                function (data) {
                  $("#div_table_remedial_detail").empty();
                  $("#div_table_remedial_detail").append(data);
                }
              );
              $formAddRemedialDetail.find(".alert-error").addClass("hidden");
              $formAddRemedialDetail.find(".alert-info").removeClass("hidden");
              $formAddRemedialDetail.find(".alert-warning").addClass("hidden");

              $("#id_remedial_detail").val("0");
              $("#btn-clear-remedial-detail").text("CLEAR");
              // Clear all object of form
              $("#remedial_detail_date").val("");
              $("#remedial_detail_observation").val("");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
            setTimeout(function () {
              $(".alert-info").fadeOut(1500);
              $(".alert-error").fadeOut(1500);
              $(".alert-warning").fadeOut(1500);
            }, 3000);
          },
        });
      }
    }
  });

  // Remedial Detail Delete & Clear
  $formAddRemedialDetail.on("reset", function (e) {
    if ($("#btn-clear-remedial-detail").text().toUpperCase() == "CLEAR") {
      $("#id_remedial_detail").val("0");
      $("#btn-clear-remedial").text("CLEAR");
      // Clear all object of form
      $("input[type=text]").each(function () {
        $(this).val("");
      });
      $formAddRemedialDetail.find(".alert-error").addClass("hidden");
      $formAddRemedialDetail.find(".alert-info").addClass("hidden");
      $formAddRemedialDetail.find(".alert-warning").addClass("hidden");
    } else {
      // DELETE a Remedial detail
      var r = confirm("Delete Remedial Detail?");
      if (r) {
        /* begin post */
        $.ajax({
          url: "ajax/deleteremedialdetail.php",
          data: $formAddRemedialDetail.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text != 1) {
              $formAddRemedialDetail.find(".alert-error").removeClass("hidden");
              $formAddRemedialDetail.find(".alert-info").addClass("hidden");
              $formAddRemedialDetail.find(".alert-warning").addClass("hidden");
            } else if (text == 1) {
              alert("Remedial deleted successfully!");
              $.get(
                "ajax/getremedialdetaillist_tabel.php",
                { id_remedial: $("#id_remedial_post").val() },
                function (data) {
                  $("#div_table_remedial_detail").empty();
                  $("#div_table_remedial_detail").append(data);
                }
              );

              $formAddRemedialDetail.find(".alert-info").addClass("hidden");
              $formAddRemedialDetail.find(".alert-error").addClass("hidden");
              $formAddRemedialDetail.find(".alert-warning").addClass("hidden");

              $("#id_remedial_detail").val("0");
              $("#btn-clear-remedial-detail").text("CLEAR");
              // Clear all object of form
              $("#remedial_detail_date").val("");
              $("#remedial_detail_observation").val("");
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
            setTimeout(function () {
              $(".alert-info").fadeOut(1500);
              $(".alert-error").fadeOut(1500);
              $(".alert-warning").fadeOut(1500);
            }, 3000);
          },
        });
      } else {
        $("#btn-clear-remedial-detail").text("CLEAR");
      }
    }
  });

  $linkReturnRemedial.on("click", function () {
    $("#div_table_remedial_detail").empty();
    $("#id_remedial_detail").val("0");
    $("#btn-clear-remedial").text("CLEAR");
    // Clear all object of form
    $("#remedial_detail_date").val("");
    $("#remedial_detail_observation").val("");
    $("#remedial_begin_date").val("");
    $("#remedial_end_date").val("");
    $("#id_remedial_post").val("0");
    $("#id_remedial").val("0");

    $divRemedialDetail.show();
    $divRemedialDetailDetail.hide();
  });

  // CaribeDevelopers (Lerrin Aldana) End code Remedial

  /* Form SETTING registration*/

  // $('#btn-add-setting').on("click", function(e)
  //
  // {
  //   e.preventDefault();
  //   if($('#setting_rapnumber_1').prop('checked')&& $('#setting_rapnumber_2').prop('checked') && $('#setting_rapnumber_3').prop('checked'))
  //   {
  //     alert("todos vacios")
  //   }
  //   else
  //   {
  //     alert("error")
  //   }
  // });
  function format_system_date(string_date) {
    var sfecha = string_date.split("-");

    var formattedDate = new Date(sfecha[2] + "/" + sfecha[1] + "/" + sfecha[0]);

    var d =
      formattedDate.getDate().toString().length < 2
        ? "0" + formattedDate.getDate().toString()
        : formattedDate.getDate().toString();
    var m = formattedDate.getMonth();
    m += 1;
    m = m.toString().length < 2 ? "0" + m.toString() : m.toString();
    var y = formattedDate.getFullYear();

    return y + "/" + m + "/" + d;
  }

  $formAddSetting.on("submit", function (e) {
    /* prevent refresh */
    e.preventDefault();

    var begin_rap1 = Date.parse(
      format_system_date($("#setting_begin_rap_1").val())
    );
    var end_rap1 = Date.parse(
      format_system_date($("#setting_end_rap_1").val())
    );
    var begin_rap2 = Date.parse(
      format_system_date($("#setting_begin_rap_2").val())
    );
    var end_rap2 = Date.parse(
      format_system_date($("#setting_end_rap_2").val())
    );
    var begin_rap3 = Date.parse(
      format_system_date($("#setting_begin_rap_3").val())
    );
    var end_rap3 = Date.parse(
      format_system_date($("#setting_end_rap_3").val())
    );

    if (
      $("#setting_begin_rap_1").val().length === 0 ||
      $("#setting_end_rap_1").val().length === 0 ||
      $("#setting_begin_rap_2").val().length === 0 ||
      $("#setting_end_rap_2").val().length === 0 ||
      $("#setting_begin_rap_3").val().length === 0 ||
      $("#setting_end_rap_3").val().length === 0
    ) {
      alert("Sorry, please enter data");
    } else {
      // Validate dates
      if (
        begin_rap1 > end_rap1 ||
        begin_rap2 > end_rap2 ||
        begin_rap3 > end_rap3
      ) {
        alert("Sorry, the start dates should be less than end dates");
      } else {
        if (begin_rap2 < end_rap1 || begin_rap3 < end_rap2) {
          alert(
            "Sorry,The start date must be greater or equal than the previous End Date "
          );
        } else {
          var action_url = "";
          if ($("#id_setting").length > 0) {
            action_url = "ajax/updatesetting.php";
          } else {
            action_url = "ajax/add_setting.php";
          }

          $.ajax({
            url: action_url,
            data: $formAddSetting.serialize(),
            type: "POST",
            dataType: "text",
            success: function (text) {
              if (text != "1") {
                $formAddSetting.find(".alert-error").removeClass("hidden");
              } else if (text == "1") {
                $formAddSetting
                  .find("#updated_suscessfully")
                  .removeClass("hidden");
                $.get("ajax/getsettingregister_tabel.php", function (data) {
                  $("#dataRequest-setting-detail").empty();
                  $("#dataRequest-setting-detail").append(data);
                });
                $("#btn_clear_setting").text("CLEAR");
                // Clear all object of form
                $("input[type=text]").each(function () {
                  if ($(this).attr("name") != "school_name") $(this).val("");
                });
              }
            },
            error: function (xhr, status, errorThrown) {
              console.log("error");
            },
            complete: function (xhr, status) {
              $("html, body").animate({ scrollTop: 0 }, "fast");
              $("#id_setting_post").val("0");
              $("#id_setting").val("0");
              setTimeout(function () {
                $(".alert-info").fadeOut(1500);
                $(".alert-error").fadeOut(1500);
                $(".alert-warning").fadeOut(1500);
                $("#updated_suscessfully").fadeOut(1500);
                $formAddSetting
                  .find("#updated_suscessfully")
                  .addClass("hidden");
              }, 3000);
            },
          });
        } //else interno
      }
    }
  });

  $formAddTodo.on("submit", function (e) {
    /* prevent refresh */
    e.preventDefault();

    var action_url = "";

    action_url = "ajax/addtodo.php";

    $.ajax({
      url: action_url,
      data: $formAddTodo.serialize(),
      type: "POST",
      dataType: "text",
      success: function (text) {
        if (text != "1") {
          $formAddTodo.find(".alert-error").removeClass("hidden");
        } else if (text == "1") {
          $formAddTodo.find("#updated_suscessfully").removeClass("hidden");
          $.get("ajax/getsettingregister_tabel.php", function (data) {
            $("#dataRequest-setting-detail").empty();
            $("#dataRequest-setting-detail").append(data);
          });
          $("#btn_clear_todo").text("CLEAR");
          // Clear all object of form
          $("input[type=text]").each(function () {
            if ($(this).attr("message") != "todo_message") $(this).val("");
          });
        }
      },
      error: function (xhr, status, errorThrown) {
        console.log("error");
      },
      complete: function (xhr, status) {
        $("html, body").animate({ scrollTop: 0 }, "fast");
        $("#id_setting_post").val("0");
        $("#id_todo").val("0");
        setTimeout(function () {
          $(".alert-info").fadeOut(1500);
          $(".alert-error").fadeOut(1500);
          $(".alert-warning").fadeOut(1500);
          $("#updated_suscessfully").fadeOut(1500);
          $formAddTodo.find("#updated_suscessfully").addClass("hidden");
        }, 3000);
      },
    });
  });

  /* Form AVI registration*/

  $fromAviRegistration.on("submit", function (e) {
    /* prevent refresh */
    e.preventDefault();

    if (
      $("#list_class_avi").val().length === 0 ||
      $("#data_student_by_class").val().length === 0 ||
      $("#period").val().length === 0 ||
      $("#level").val().length === 0
    )
      $fromAviRegistration.find(".alert-error").removeClass("hidden");
    else {
      /* prevent refresh */
      e.preventDefault();
      // Class
      $("#class_hidden").val($("#list_class_avi").val());
      // Period
      $("#period_hidden").val($("#period").val());
      // Student
      $("#data_student_by_class_hidden").val($("#data_student_by_class").val());
      // Level
      $("#level_hidden").val($("#level").val());

      if ($("input:radio[id=avi_no_promoted]").prop("checked"))
        $("#promoted_hidden").val(0);
      else $("#promoted_hidden").val(1);

      if ($("#id_avi").val() == "") {
        // Add a Avi

        /* begin post */
        $.ajax({
          url: "ajax/addavi.php",
          data: $fromAviRegistration.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text != "1") {
              $fromAviRegistration.find(".alert-error").removeClass("hidden");
            } else if (text == "1") {
              $fromAviRegistration
                .find("#created_suscessfully")
                .removeClass("hidden");
              $.get("ajax/getaviregister_tabel.php", function (data) {
                $("#table_avi_result_detail").empty();
                $("#table_avi_result_detail").append(data);
              });
              $("#id_avi").val("0");
              $("#btn-clear-avi").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              $("#observation").val("");
              // $fromAviRegistration.find('.alert-info').removeClass('hidden');
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
            setTimeout(function () {
              $(".alert-info").fadeOut(1500);
              $(".alert-error").fadeOut(1500);
              //$fromAviRegistration.find('.alert-info').addClass('hidden');
              //$fromAviRegistration.find('.alert-error').addClass('hidden');
              $("#created_suscessfully").fadeOut(1500);
            }, 3000);
          },
        });
      } else {
        // UPDATE a AVi

        /* begin post */

        $.ajax({
          url: "ajax/updateavi.php",
          data: $fromAviRegistration.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text != "1") {
              $fromAviRegistration.find(".alert-error").removeClass("hidden");
            } else if (text == "1") {
              // alert('Avi update successfully!');
              $fromAviRegistration
                .find("#updated_suscessfully")
                .removeClass("hidden");
              $.get("ajax/getaviregister_tabel.php", function (data) {
                $("#table_avi_result_detail").empty();
                $("#table_avi_result_detail").append(data);
              });
              $("#btn-clear-avi").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
                $("#list_class_avi").val("-1");
                $("#data_student_by_class").val("");
                $("#period").val("");
                $("#level").val("");
                $("#observation").val("");
              });
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
            $("#id_remedial_post").val("0");
            $("#id_remedial").val("0");
            setTimeout(function () {
              $(".alert-info").fadeOut(1500);
              $(".alert-error").fadeOut(1500);
              $(".alert-warning").fadeOut(1500);
              $("#updated_suscessfully").fadeOut(1500);
            }, 3000);
          },
        });
      }
    }
  });

  // Avi Delete & Clear
  $fromAviRegistration.on("reset", function (e) {
    if ($("#btn-clear-avi").text().toUpperCase() == "CLEAR") {
      $("#id_avi").val("");
      $("#btn-clear-avi").text("CLEAR");
      // Clear all object of form
      $("input[type=text]").each(function () {
        $(this).val("");
      });
      $("#observation").val("");

      $("#data_student_by_class")
        .empty()
        .append('<option selected="selected">Select One Student</option>');
    } else {
      // DELETE a TEST
      var r = confirm("Delete Avi?");
      if (r) {
        /* begin post */
        $.ajax({
          url: "ajax/deleteavi.php",
          data: $fromAviRegistration.serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text != "1") {
              $fromAviRegistration.find(".alert-error").removeClass("hidden");
              //		$fromAviRegistration.find('.alert-info').addClass('hidden');
              //		$fromAviRegistration.find('.alert-warning').addClass('hidden');
            } else if (text == "1") {
              // alert('Avi deleted successfully!');
              $fromAviRegistration
                .find("#deleted_suscessfully")
                .removeClass("hidden");
              $.get("ajax/getaviregister_tabel.php", function (data) {
                $("#table_avi_result_detail").empty();
                $("#table_avi_result_detail").append(data);
              });
              $("#id_avi").val("");
              $("#btn-clear-avi").text("CLEAR");
              // Clear all object of form
              $("input[type=text]").each(function () {
                $(this).val("");
              });
              //$fromAviRegistration.find('.alert-error').addClass('hidden');
              //$fromAviRegistration.find('.alert-info').addClass('hidden');
              //$fromAviRegistration.find('.alert-warning').addClass('hidden');
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            $("html, body").animate({ scrollTop: 0 }, "fast");
            $("#id_remedial").val("");
            $("#btn-clear-avi").text("CLEAR");
            setTimeout(function () {
              $(".alert-info").fadeOut(1500);
              $(".alert-error").fadeOut(1500);
              $(".alert-warning").fadeOut(1500);
              $("#deleted_suscessfully").fadeOut(1500);
            }, 3000);
          },
        });
      } else {
        $("#btn-clear-avi").text("CLEAR");
      }
    }
  });

  /* End form AVI registration */

  // CaribeDevelopers begin code Document

  $formDocumentUpload.on("submit", function (e) {
    /* prevent refresh */
    e.preventDefault();
    $("#klas_student").val($("#klas").text());

    if ($("#id_student").val().length === 0) {
      $(this).find(".alert-error").removeClass("hidden");
    } else {
      /* prevent refresh */
      e.preventDefault();
      // UPLOAD Document

      /* begin post */
      $.ajax({
        url: "ajax/upload.php",
        // data: $formDocumentUpload.serialize(),
        cache: false,
        contentType: false,
        processData: false,
        data: new FormData(this),
        type: "POST",
        // dataType: "text",
        success: function (text) {
          if (text != 1) {
            $formDocumentUpload.find(".alert-error").removeClass("hidden");
            $formDocumentUpload.find(".alert-info").addClass("hidden");
            $formDocumentUpload.find(".alert-warning").addClass("hidden");
          } else {
            alert("Document upload successfully!");
            $.get("ajax/addaudit_document.php"),
              $.get(
                "ajax/getdocuments.php",
                { id: $("#id_student").val() },
                function (data) {
                  $("#div_table_document").empty();
                  $("#div_table_document").append(data);
                }
              );
            // Clear all object of form except class
            $("#description").val("");
            $formDocumentUpload.find(".alert-error").addClass("hidden");
            $formDocumentUpload.find(".alert-info").addClass("hidden");
            $formDocumentUpload.find(".alert-warning").addClass("hidden");
          }
        },
        error: function (xhr, status, errorThrown) {
          console.log("error");
        },
        complete: function (xhr, status) {
          $("html, body").animate({ scrollTop: 0 }, "fast");
        },
      });
    }
  });

  // Begin DELETE document function

  $("#btn_document_delete").click(function (e) {
    // DELETE a document
    var r = confirm("Delete document?");
    if (r) {
      // For each checked element
      var sList = "";
      $("input[type=checkbox]").each(function () {
        if (this.name != "") {
          var _document_id = "";
          var _document_name = "";
          /* begin post */
          if (this.checked) {
            _document_id = $(this).val();
            _document_name = $(this).attr("doc");

            $.post(
              "ajax/deletedocument.php",
              { document_id: _document_id, document_name: _document_name },

              function (data) {
                console.log(data);
              }
            )
              .done(function (data) {
                /* it's done */
                if (data == 1) {
                  alert("Document deleted successfully!");
                  $.get(
                    "ajax/getdocuments.php",
                    { id: $("#id_student").val() },
                    function (data) {
                      $("#div_table_document").empty();
                      $("#div_table_document").append(data);
                    }
                  );
                } else {
                  $formDocumentUpload
                    .find(".alert-error")
                    .removeClass("hidden");
                  $formDocumentUpload.find(".alert-info").addClass("hidden");
                  $formDocumentUpload.find(".alert-warning").addClass("hidden");
                }
              })
              .fail(function () {
                alert("Error, please contact developers.");
              });
          }
        }
      });
    }
  });

  // End DELETE document function

  // CaribeDevelopers end code Document

  // End code CaribeDevelopers

  // FROM VENEZUELA
  //luisinvoice
  $("#form-addInvoicepayment").on("submit", function (e) {
    var b = $("#invoice_id option:selected").text();
    var bal = b.indexOf(":");
    var bal2 = bal + 1;
    var balance = b.substring(bal2);
    // alert (balance);
    /* prevent refresh */
    e.preventDefault();
    if ($("#invoicepaymenttype").val() === "Invoice") {
      // Invoice
      //"#data_student_by_class option:selected").val()
      if ($("#invoice_type option:selected").val() != "0") {
        if (
          $("#invoicepaymentnumber").val().length === 0 ||
          $("#invoicepaymentammount").val().length === 0 ||
          $("#invoicepaymentdate").val().length === 0 ||
          $("#invoicepaymentmemo").val().length === 0
        ) {
          alert("Sorry , complete missing data");
        } else {
          /* prevent refresh */
          e.preventDefault();
          $("#data_student_by_class option:selected").val();

          /* begin post */

          $.ajax({
            url: "ajax/addinvoice.php",
            data: $("#form-addInvoicepayment").serialize(),
            type: "POST",
            dataType: "text",
            success: function (text) {
              console.log(text);
              if (text != 1) {
                alert("Could not add invoice");
              } else if (text == 1) {
                alert("invoice Added Successfully");
                location.reload();
              }
            },
            error: function (xhr, status, errorThrown) {
              console.log("error");
            },
            complete: function (xhr, status) {
              //console.log("complete");
            },
          });
        }
      } else {
        alert("Sorry , you must select at least one value");
      }
    } else {
      // Payment

      if (
        $("#invoicepaymentnumber").val().length === 0 ||
        $("#invoicepaymentammount").val().length === 0 ||
        $("#invoicepaymentdate").val().length === 0 ||
        $("#invoice_id").val().length === 0
      ) {
        alert("Sorry , complete missing data");
      } else if (
        parseFloat($("#invoicepaymentammount").val()) > parseFloat(balance)
      ) {
        alert("Sorry , you must pay the equal or less amount balance");
      } else {
        /* prevent refresh */
        e.preventDefault();

        /* begin post */
        $.ajax({
          url: "ajax/addpayment.php",
          data: $("#form-addInvoicepayment").serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            console.log(text);
            if (text != 1) {
              alert("Could not add payment");
              console.log("results: " + text);
            } else if (text == 1) {
              alert("Payment Added Successfully");
              location.reload();
            }
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            //console.log("complete");
          },
        });
      }
      // } else {
      //     alert("Sorry , you must select at least one value");
      // }
    }
  });
  //@ljbello Begin Code form user account Caribe Developer
  $("#btn_create_user_account").on("click", function (e) {
    /* prevent refresh */ e.preventDefault();

    if (
      $("#user_email").val().length === 0 ||
      $("#user_password").val().length === 0 ||
      $("#user_firts_name").val().length === 0 ||
      $("#user_last_name").val().length === 0 ||
      $("#user_rights").val().length === 0 ||
      // $("#user_school_id").val().length === 0 ||
      $("#user_class").val().length === 0
    ) {
      $frm_user_account.find(".alert-error").removeClass("hidden");
    } else {
      /* prevent refresh */
      // e.preventDefault();
      // Add a User Account

      /* begin post */
      $.ajax({
        url: "ajax/adduser_account.php",
        data: $frm_user_account.serialize(),
        type: "POST",
        dataType: "text",
        success: function (text) {
          if (text.trim() != "1") {
            alert("ERROR Adding user");
            $frm_user_account.find(".alert-error").removeClass("hidden");
          } else if (text.trim() == "1") {
            $frm_user_account
              .find("#created_suscessfully")
              .removeClass("hidden");
            $.get("ajax/getuseraccount_tabel.php", function (data) {
              $("#tbl_list_users_accounts").empty();
              $("#tbl_list_users_accounts").append(data);
            });
            //  $('#id_user_account').val("0");
            $("#btn_clear_user").text("CLEAR");
            // Clear all object of form
            $("input[type=text]").each(function () {
              $(this).val("");
            });
            $("input[type=email]").each(function () {
              $(this).val("");
            });
            $("input[type=password]").each(function () {
              $(this).val("");
            });
            $("input[type=password]").each(function () {
              $(this).val("");
            });
          }
        },
        error: function (xhr, status, errorThrown) {
          alert("ERROR Adding User Error");
          console.log("error");
        },
        complete: function (xhr, status) {
          $("html, body").animate({ scrollTop: 0 }, "fast");
          setTimeout(function () {
            //$(".alert-info").fadeOut(1500);
            //  $(".alert-error").fadeOut(1500);
            //$fromuser_accountRegistration.find('.alert-info').addClass('hidden');
            //$fromuser_accountRegistration.find('.alert-error').addClass('hidden');
            $("#created_suscessfully").fadeOut(1500);
          }, 3000);
        },
      });
    }
  });
  $("#btn_update_user_account").on("click", function (e) {
    // UPDATE a user_account
    e.preventDefault();

    /* begin post */

    $.ajax({
      url: "ajax/updateuser_account.php",
      data: $frm_user_account.serialize(),
      type: "POST",
      dataType: "text",
      success: function (text) {
        if (text.trim() != "1") {
          $frm_user_account.find(".alert-error").removeClass("hidden");
        } else if (text.trim() == "1") {
          // alert('user_account update successfully!');
          $frm_user_account.find("#updated_suscessfully").removeClass("hidden");
          $.get("ajax/getuseraccount_tabel.php", function (data) {
            $("#tbl_list_users_accounts").empty();
            $("#tbl_list_users_accounts").append(data);
          });
          $("#btn_clear_user_account").text("CLEAR");
          // Clear all object of form
          $("input[type=text]").each(function () {
            $(this).val("");
          });
        }
      },
      error: function (xhr, status, errorThrown) {
        console.log("error Updating User");
      },
      complete: function (xhr, status) {
        $("html, body").animate({ scrollTop: 0 }, "fast");

        setTimeout(function () {
          $(".alert-info").fadeOut(1500);
          $(".alert-error").fadeOut(1500);
          $(".alert-warning").fadeOut(1500);
          $("#updated_suscessfully").fadeOut(1500);
        }, 3000);
      },
    });
  });
  $("#btn_delete_user_account").on("click", function (e) {
    // DELETE a TEST
    var r = confirm("Delete user_account?");
    if (r) {
      /* begin post */
      $.ajax({
        url: "ajax/deleteuser_account.php",
        data: $frm_user_account.serialize(),
        type: "POST",
        dataType: "text",
        success: function (text) {
          if (text != "1") {
            $frm_user_account.find(".alert-error").removeClass("hidden");
            //		$fromuser_accountRegistration.find('.alert-info').addClass('hidden');
            //		$fromuser_accountRegistration.find('.alert-warning').addClass('hidden');
          } else if (text == "1") {
            // alert('user_account deleted successfully!');
            $frm_user_account
              .find("#deleted_suscessfully")
              .removeClass("hidden");
            $.get("ajax/getuseraccount_tabel.php", function (data) {
              $("#tbl_list_users_accounts").empty();
              $("#tbl_list_users_accounts").append(data);
            });

            $("#btn_clear_user_account").text("CLEAR");
            // Clear all object of form
            $("input[type=text]").each(function () {
              $(this).val("");
            });
            //$fromuser_accountRegistration.find('.alert-error').addClass('hidden');
            //$fromuser_accountRegistration.find('.alert-info').addClass('hidden');
            //$fromuser_accountRegistration.find('.alert-warning').addClass('hidden');
          }
        },
        error: function (xhr, status, errorThrown) {
          console.log("error");
        },
        complete: function (xhr, status) {
          $("html, body").animate({ scrollTop: 0 }, "fast");
          $("#id_remedial").val("");
          $("#btn_clear_user_account").text("CLEAR");
          setTimeout(function () {
            $(".alert-info").fadeOut(1500);
            $(".alert-error").fadeOut(1500);
            $(".alert-warning").fadeOut(1500);
            $("#deleted_suscessfully").fadeOut(1500);
          }, 3000);
        },
      });
    } else {
      $("#btn_clear_user_account").text("CLEAR");
    }
  });
  //CALCULATE CIJFERS FOOT GEMIDDELDE (ejaspe - caribeDevelopers)
  function calculate_gemiddelde() {
    var $total = 0.0;
    var $count_t = 0;

    for (var $c = 1; $c <= 21; $c++) {
      var $gemiddeld = 0.0;
      var $count = 0;

      $('#lblName1[data-cijfer="c' + $c + '"]').each(function () {
        if ($(this).text() != null && $(this).text() != "") {
          $gemiddeld = $gemiddeld + parseFloat($(this).text());
          $count++;
        }
      });

      if ($gemiddeld > 0 && $count > 0) {
        $total = $total + Math.round(($gemiddeld / $count) * 10) / 10;
        $count_t++;
      }

      $("#gemiddeld_" + $c).text(Math.round(($gemiddeld / $count) * 10) / 10);

      if (Math.round(($gemiddeld / $count) * 10) / 10 < 5.5) {
        $("#gemiddeld_" + $c)
          .parent("td")
          .removeClass();
        $("#gemiddeld_" + $c)
          .parent("td")
          .addClass("quaternary-bg-color default-secondary-color");
      } else {
        $("#gemiddeld_" + $c)
          .parent("td")
          .removeClass("quaternary-bg-color default-secondary-color");
      }

      if ($("#gemiddeld_" + $c).text() === "NaN") {
        $("#gemiddeld_" + $c).text("");
      }
    }

    if ($count_t)
      $("#gemiddeld_total").text(Math.round(($total / $count_t) * 10) / 10);
    else $("#gemiddeld_total").text(0);
  }

  // END FROM VENEZUELA

  // Validation login
  $formLogin.on("submit", function (e) {
    var user_email_login = $("#username").val();
    if ($("#username").val().length === 0 || $("#password").val.length === 0) {
      e.preventDefault();
      $("#form-signin-spn").find(".alert").removeClass("hidden");
      $("#username").addClass("error");
      $("#password").addClass("error");
    } else {
      $.ajax({
        url: "ajax/authenticate.php",
        data: $("#form-signin-spn").serialize(),
        type: "POST",
        dataType: "text",
        success: function (text) {
          if (text == 0) {
            $("#form-signin-spn").find(".alert").removeClass("hidden");
            $("#form-signin-spn").find("input").css({
              border: "1px solid #990000",
            });
            return false;
          } else if (text == 1) {
            //   $.ajax({
            // url:"ajax/sms.php",
            // data: "user="+user_email_login,
            // type  : 'POST',
            // dataType: "HTML",
            // cache: false,
            // async :false,
            // success: function(data){
            // }
            // })

            //console.log("authenticated");
            window.location.replace("home.php");
          } else if (text == 2) {
            window.location.replace("cijfers_teacher.php");
          }
        },
        error: function (xhr, status, errorThrown) {
          console.log("error");
        },
        complete: function (xhr, status) {},
      });

      e.preventDefault();
    }
  });

  // Submit a form, GET the data and return the data to the DOM
  $formDataRetriever.on("submit", function (e) {
    var buttonLink = $(this).find("button").attr("data-ajax-href"),
      replaceDiv = $(this).find("button").attr("data-display");

    e.preventDefault();

    $.get(buttonLink, $formDataRetriever.serialize(), function (data) {
      $(".alert").fadeOut(0);
      $("." + replaceDiv + "").html(data);
      dataTablePlugin();
    })
      .done(function () {
        if ($('[data-toggle-tooltip="tooltip"]').length) {
          Modernizr.load({
            test: $.fn.tooltip,
            nope: WebApi.Config.baseUri + "assets/js/lib/jquery.tooltip.min.js",
            complete: function tooltipInit() {
              $('[data-toggle-tooltip="tooltip"]').tooltip();
            },
          });
        }

        // NAVIGATE WITH ARROW INTO THE TABLE
        var $datum = $("#datum").val();
        var $period = "";
        if ($("#period_hs").val()) {
          $period = $("#period_hs").val();
        }
        var $vakid_hs = "";
        if ($("#verzuim_vakken_lijst").val()) {
          $vakid_hs = $("#verzuim_vakken_lijst").val();
        }

        $("#dataRequest-verzuim").find("tr").attr("data-datum", $datum);

        if ($(".telaat-field").length) {
          $(".telaat-field").on("click", function () {
            $(this)
              .parent("td")
              .next()
              .find(".absentie-field")
              .prop("checked", false);
          });
          $(".absentie-field").on("click", function () {
            $(this)
              .parent("td")
              .prev()
              .find(".telaat-field")
              .prop("checked", false);
          });
        }

        if ($("#dataRequest-verzuim").length) {
          $("#dataRequest-verzuim .form-control").on("change", function () {
            var $studentID = $(this).closest("tr").attr("data-student-id"),
              $klas = $(this).closest("tr").attr("data-klas"),
              $datum = $(this).closest("tr").attr("data-datum"),
              $laatField = $(this)
                .closest("tr")
                .children("td")
                .children()[0].checked,
              $absentField = $(this)
                .closest("tr")
                .children("td")
                .children()[1].checked,
              $toetsInhalenField = $(this)
                .closest("tr")
                .children("td")
                .children()[2].checked,
              $uitsturenField = $(this)
                .closest("tr")
                .children("td")
                .children()[3].checked,
              $lpField = $(this)
                .closest("tr")
                .children("td")
                .children("select.lp-field")
                .val(),
              $geenHuiswerkField = $(this)
                .closest("tr")
                .children("td")
                .children()[5].checked,
              $opmerking = $(this)
                .closest("tr")
                .children("td")
                .children("input.opmerking-input")
                .val(),
              $_verzuimid = $(this).attr("verzuim");

            var $firstDatePicker = $("#form-laat-absent #datum").val(),
              $currentDate = $(this).closest("tr").attr("data-datum");

            if ($firstDatePicker != $currentDate) {
              alert("Klik op zoeken!");
            } else {
              //
              $.post(
                "ajax/update_verzuim.php",
                {
                  studentid: $studentID,
                  klas: $klas,
                  datum: $datum,
                  telaat: $laatField,
                  absentie: $absentField,
                  toetsinhalen: $toetsInhalenField,
                  uitsturen: $uitsturenField,
                  lp: $lpField,
                  huiswerk: $geenHuiswerkField,
                  opmerking: $opmerking,
                  period_hs: $period,
                  verzuimid: $_verzuimid,
                },
                function (data) {
                  /* do something if needed */
                  // PRIMER UPDATE
                  $("#loader_spn").removeClass("hidden");
                }
              )
                .done(function (data) {
                  /* it's done */
                  if (data == 1) {
                    $("#loader_spn").addClass("hidden");
                  } else {
                    $("#loader_spn").addClass("hidden");
                    // RE-TRY FUNCTION
                  }
                })
                .fail(function () {
                  alert("Error, please contact developers.");
                  $("#loader_spn").addClass("hidden");
                });
            }
          });
        }

        if ($("#form-vak").length) {
          reInitializeModal();
        }

        $(".editable-select").each(function () {
          $(this).on("change", function () {
            var $studentid = $(this).attr("data-student-id"),
              $houdingname = $(this).attr("data-houding"),
              $houding_id = $(this).attr("id_houding_table"),
              $klas = $(this).attr("data-klas"),
              $rapport = $(this).attr("data-rapport"),
              $houdingvalue = $(this).val(),
              $parentSelector = $(this).parent("td");

            $.post(
              "ajax/update_houding.php",
              {
                houding_id: $houding_id,
                studentid: $studentid,
                houdingname: $houdingname,
                houdingvalue: $houdingvalue,
                klas: $klas,
                rapport: $rapport,
              },
              function (data, $parentSelector) {
                /* do something if needed */
              }
            )
              .done(function (data) {
                /* it's done */
                if (data == 1) {
                  $parentSelector.css({
                    "background-color": "rgba(145,206,162,0.9)",
                  });

                  setTimeout(function () {
                    $parentSelector.css({ "background-color": "transparent" });
                  }, 2500);
                } else {
                  // RE-TRY FUNCTION
                }
              })
              .fail(function () {
                alert("Error, please contact developers.");
              });
          });
        });

        $(".lblhouding").each(function () {
          $(this).on("focusout", function () {
            var $studentid = $(this).attr("data-student-id"),
              $houdingname = $(this).attr("data-houding"),
              $houding_id = $(this).attr("id_houding_table"),
              $klas = $(this).attr("data-klas"),
              $rapport = $(this).attr("data-rapport"),
              $houdingvalue = $(this).val(),
              $parentSelector = $(this).parent("td");

            $.post(
              "ajax/update_houding.php",
              {
                houding_id: $houding_id,
                studentid: $studentid,
                houdingname: $houdingname,
                houdingvalue: $houdingvalue,
                klas: $klas,
                rapport: $rapport,
              },
              function (data, $parentSelector) {
                /* do something if needed */
              }
            )
              .done(function (data) {
                /* it's done */
                if (data == 1) {
                  $parentSelector.css({
                    "background-color": "rgba(145,206,162,0.9)",
                  });

                  setTimeout(function () {
                    $parentSelector.css({ "background-color": "transparent" });
                  }, 2500);
                } else {
                  // RE-TRY FUNCTION
                }
              })
              .fail(function () {
                alert("Error, please contact developers.");
              });
          });
        });

        $(".editable").each(function () {
          var label = $(this);

          if ($(".lblopmerking").length) {
            //  label.after("<input class = 'form-control opmerking-input' type = 'text' style = 'display:block'/>");
          } else {
            label.after("<input type = 'text' style = 'display:none'/>");
          }

          var textbox = $(this).next();

          textbox[0].name = this.id.replace("lbl", "txt");

          textbox.val(label.html());

          label.on("click", function () {
            $(this).closest("tr").addClass("active");

            $(this).hide();

            var vakInput = $(this).next(),
              strLength = vakInput.val().length * 2;

            $(this).next().show().focus();
            vakInput.focus();
            vakInput[0].setSelectionRange(strLength, strLength);

            if ($("#vak").length) {
              Modernizr.load({
                test: $.fn.setCursorPosition,
                nope:
                  WebApi.Config.baseUri +
                  "assets/js/lib/jquery.setcursorposition.js",
                complete: function beginArrowNavigation() {
                  var $vak = $("#vak"),
                    $vakTDs = $vak.find("td");

                  $vakTDs.on("keydown", function (e) {
                    var charPos = e.target.selectionStart,
                      strLength = e.target.value.length,
                      prevPos = $(this).data("prevPos");

                    switch (e.which) {
                      case 37:
                        // left

                        $(this)
                          .closest("td")
                          .prev()
                          .find("span")
                          .css({ display: "none" });
                        $(this)
                          .closest("td")
                          .prev()
                          .find("input")
                          .css({ display: "block" });

                        $(this)
                          .closest("td")
                          .prev()
                          .find("input")
                          .setCursorPosition(5);
                        $(this).closest("td").prev().find("input").focus();

                        $(this).data("prevPos", null);

                        break;

                      case 38:
                        // up
                        $(this)
                          .closest("tr")
                          .prev()
                          .find("td:eq(" + $(this).closest("td").index() + ")")
                          .find("span")
                          .css({ display: "none" });
                        $(this)
                          .closest("tr")
                          .prev()
                          .find("td:eq(" + $(this).closest("td").index() + ")")
                          .find("input")
                          .css({ display: "block" });
                        $(this)
                          .closest("tr")
                          .prev()
                          .find("td:eq(" + $(this).closest("td").index() + ")")
                          .find("input")
                          .setCursorPosition(5);
                        $(this)
                          .closest("tr")
                          .prev()
                          .find("td:eq(" + $(this).closest("td").index() + ")")
                          .find("input")
                          .focus();
                        $(this).data("prevPos", null);

                        break;

                      case 39:
                        // right

                        if (
                          charPos == strLength &&
                          (prevPos == null || prevPos == charPos)
                        ) {
                          $(this)
                            .closest("td")
                            .next()
                            .find("span")
                            .css({ display: "none" });
                          $(this)
                            .closest("td")
                            .next()
                            .find("input")
                            .css({ display: "block" });
                          $(this)
                            .closest("td")
                            .next()
                            .find("input")
                            .setCursorPosition(5);
                          $(this).closest("td").next().find("input").focus();

                          $(this).data("prevPos", null);
                        } else {
                          $(this).data("prevPos", charPos);
                        }

                        break;

                      case 40:
                        // down

                        $(this)
                          .closest("tr")
                          .next()
                          .find("td:eq(" + $(this).closest("td").index() + ")")
                          .find("input")
                          .css({ display: "none" });
                        $(this)
                          .closest("tr")
                          .next()
                          .find("td:eq(" + $(this).closest("td").index() + ")")
                          .find("input")
                          .css({ display: "block" });
                        $(this)
                          .closest("tr")
                          .next()
                          .find("td:eq(" + $(this).closest("td").index() + ")")
                          .find("input")
                          .setCursorPosition(5);
                        $(this)
                          .closest("tr")
                          .next()
                          .find("td:eq(" + $(this).closest("td").index() + ")")
                          .find("input")
                          .focus();
                        $(this).data("prevPos", null);

                        break;

                      default:
                        return;
                    }
                    e.preventDefault();
                  });
                },
              });
            }
          });

          textbox.on("focusout", function () {
            if ($(".lblopmerking").length) {
              var $studentID = $(this).closest("tr").attr("data-student-id"),
                $klas = $(this).closest("tr").attr("data-klas"),
                $datum = $(this).closest("tr").attr("data-datum"),
                $laatField = $(this)
                  .closest("tr")
                  .children("td")
                  .children()[0].checked,
                $absentField = $(this)
                  .closest("tr")
                  .children("td")
                  .children()[1].checked,
                $toetsInhalenField = $(this)
                  .closest("tr")
                  .children("td")
                  .children()[2].checked,
                $uitsturenField = $(this)
                  .closest("tr")
                  .children("td")
                  .children()[3].checked,
                $lpField = $(this)
                  .closest("tr")
                  .children("td")
                  .children("select.lp-field")
                  .val(),
                $geenHuiswerkField = $(this)
                  .closest("tr")
                  .children("td")
                  .children()[5].checked,
                $opmerking = $(this)
                  .closest("tr")
                  .children("td")
                  .children("input.opmerking-input")
                  .val(),
                $_verzuimid = $(this).attr("verzuim");

              $.post(
                "ajax/update_verzuim.php",
                {
                  studentid: $studentID,
                  klas: $klas,
                  datum: $datum,
                  telaat: $laatField,
                  absentie: $absentField,
                  toetsinhalen: $toetsInhalenField,
                  uitsturen: $uitsturenField,
                  lp: $lpField,
                  huiswerk: $geenHuiswerkField,
                  opmerking: $opmerking,
                },
                function (data) {
                  // sEGUNDO UPDATE
                  //calculate_gemiddelde();
                  /* do something if needed */
                }
              )
                .done(function (data) {
                  /* it's done */
                  if (data == 1) {
                  } else {
                    // RE-TRY FUNCTION
                  }
                })
                .fail(function () {
                  alert("Error, please contact developers.");
                });
            }

            var n = $(this).val();

            if (n >= 0 && n <= 10) {
              $(this).closest("td").removeClass("error");

              $(this).closest("tr").addClass("active");

              $(this).hide();
              $(this).prev().html($(this).val());
              $(this).prev().show();

              var $studentNr = $(this).prev().attr("data-student-id"),
                $id_cijfer = $(this).prev().attr("id_cijfer_table"),
                $column = $(this).prev().attr("data-column"),
                $row = $(this).prev().attr("data-row"),
                $cijferName = $(this).prev().attr("data-cijfer"),
                $klas = $(this).prev().attr("data-klas"),
                $vak = $(this).prev().attr("data-vak"),
                $rapport = $(this).prev().attr("data-rapport"),
                $cijfer = $(this).val(),
                $parentSelector = $(this).parent("td"),
                $cell_cijfer = $(this).prev().attr("id_cell_cijfer");

              if (jQuery.inArray($cell_cijfer, wrong_cells) != -1) {
                var d = wrong_cells.indexOf($cell_cijfer);
                wrong_cells.splice(d, 1);
              }

              extra_save_cijfer_sessionStorage(
                $id_cijfer,
                $studentNr,
                $cijferName,
                $cijfer,
                $klas,
                $rapport,
                $vak,
                $cell_cijfer,
                i_btn_extra_save
              );
              i_btn_extra_save++;
              $("#btn_extra_save_cijfer").text("Ex. Save: " + i_btn_extra_save);
              // POST TO UPDATE 'CIJFERS'
              // Upload Cijfers 3
              $.post(
                "ajax/update_cijfers.php",
                {
                  id_cijfer: $id_cijfer,
                  studentid: $studentNr,
                  cijfername: $cijferName,
                  cijfervalue: $cijfer,
                  klas: $klas,
                  rapport: $rapport,
                  vak: $vak,
                },
                function (data, $parentSelector) {
                  /* do something if needed */
                }
              )
                .done(function (data) {
                  if (data != 1) {
                    $parentSelector.css({
                      "background-color": "rgba(145,206,162,0.9)",
                    });

                    setTimeout(function () {
                      $parentSelector.css({ "background-color": "" });
                    }, 1000);
                  }

                  if (data == 0 || data == -2) {
                    save_cijfer_localstorage(
                      $id_cijfer,
                      $studentNr,
                      $cijferName,
                      $cijfer,
                      $klas,
                      $rapport,
                      $vak
                    );
                    //localStorage.clear();
                  }
                  /*
                   *** When updated is done, get the 'gemiddelde' from the back-end
                   *** Update the 'gemiddelde' from the current row that is updated
                   */

                  // POST TO UPDATE 'GEMIDDELDE'
                  $.post(
                    "ajax/getgemiddelde.php",
                    {
                      studentid: $studentNr,
                      klas: $klas,
                      rapport: $rapport,
                      vak: $vak,
                    },
                    function (data) {}
                  )
                    .done(function (data) {
                      // if (data==0){
                      //
                      //   var nrow = ($('#vak >tbody >tr').length);
                      //   var nColumn = ($("#vak tr:last td").length)-3;
                      //   var narr = "Filas: "+nrow+" - Columnas: "+nColumn;
                      //   alert(msg);

                      //}
                      /*
                       ** Update the last td in the current tr
                       */
                      // $('#vak').find('tr.active td:last-child').html(data);
                      $("#ge" + $row).text(data);
                      $("#vak").find("tr.active").removeClass("active");
                    })
                    .fail(function () {
                      // alert('There is no connection to the database, your data will be stored offline and will be sent to the database once the connection is restored');
                      save_cijfer_localstorage(
                        $id_cijfer,
                        $studentNr,
                        $cijferName,
                        $cijfer,
                        $klas,
                        $rapport,
                        $vak
                      );
                    });
                })
                .fail(function () {
                  // alert('There is no connection to the database, your data will be stored offline and will be sent to the database once the connection is restored');
                  save_cijfer_localstorage(
                    $id_cijfer,
                    $studentNr,
                    $cijferName,
                    $cijfer,
                    $klas,
                    $rapport,
                    $vak
                  );
                });

              /* Original codeblock by Rudy */
              // POST TO UPDATE 'GEMIDDELDE'
              //$.post( "ajax/getgemiddelde.php", {studentid: $studentNr, klas: $klas, rapport : $rapport, vak : $vak }, function(data) {
              //
              //}).done(function(data) {
              //   /*
              //    ** Update the last td in the current tr
              //    */
              //    $('#vak').find('tr.active td:last-child').html(data);
              //    $('#vak').find('tr.active').removeClass('active');
              //}).fail(function() {
              //    alert('Error, please contact developers.');
              //});
            } else {
              var $wrong_cijfer = $(this).prev().attr("id_cell_cijfer");

              //asing ccs orange
              if (n != "") {
                $parentSelector = $(this).parent("td");
                wrong_cells.push($wrong_cijfer);
                // $cell_cijfer_orange = $(this).prev().attr('id_cell_cijfer');
                $parentSelector.css({
                  "background-color": "rgba(238, 108, 44, 0.9)",
                });
              }
            }
            //CALCULATE FOOT GEMIDDELDE (ejaspe - caribeDevelopers)

            calculate_gemiddelde();
          });
        });
      })
      .fail(function () {
        alert("error");
      });

    return false;
  });

  $formAddStudent.on("submit", function (e) {
    /* prevent refresh */
    e.preventDefault();

    if (
      $("#studentnummer").val().length === 0 ||
      $("#achternaam").val().length === 0 ||
      $("#voornamen").val().length === 0 ||
      $("#student-klas").val().length === 0
    ) {
      $(this).find(".alert-danger").removeClass("hidden");
      $(this).find(".alert-info").addClass("hidden");
    } else if (!vd_less_today($("#geboortedatum").val())) {
      alert("Please select a date less than today");
    } else {
      /* prevent refresh */
      e.preventDefault();

      if ($("#student_id").val().length === 0) {
        var new_id = 0;
        /* begin post */

        $.ajax({
          url: "ajax/add_leerling.php",
          cache: false,
          contentType: false,
          processData: false,
          //data: $formAddStudent.serialize(),
          data: new FormData(this),
          type: "POST",
          //dataType: "text",
          success: function (text) {
            if (text.trim() == null) {
              //alert('ERROR');
              $("html, body").animate({ scrollTop: 0 }, "fast");
              $formAddStudent.find(".alert-danger").removeClass("hidden");
            } else if (text.trim() == "1" || text.trim() == 1) {
              var $leerlingNR = $("#studentnummer").val(),
                $klas = $("#student-klas").val(),
                $achternaam = $("#achternaam").val(),
                $voornamen = $("#voornamen").val(),
                $geslacht = $("#geslacht").val(),
                $geboortedatum = $("#geboortedatum").val(),
                $geboorteplaats = $("#geboorteplaats").val(),
                $adres = $("#adres").val(),
                $telefoon = $("#telefoon").val(),
                $studentStatus = $("#status").val();

              var hidden_values = "";
              var link_detail =
                "<td><a href='" +
                $("#baseurl").val() +
                "/" +
                $("#detailpage").val() +
                "?id=" +
                new_id +
                " class='link quaternary-color'>MEER <i class='fa fa-angle-double-right quaternary-color'></i></a>";

              $("#dataRequest-student tbody").prepend(
                "<tr><td>" +
                  $leerlingNR +
                  "</td><td>" +
                  $voornamen +
                  "</td><td>" +
                  $achternaam +
                  "</td><td>" +
                  $geslacht +
                  "</td><td>" +
                  $geboortedatum +
                  "</td><td>" +
                  $klas +
                  link_detail +
                  "</</tr>"
              );

              $("input[type=text]").each(function () {
                $(this).val("");
              });
              // $formAddStudent.find('.alert-info').addClass('hidden');
              alert("Bedankt voor het toevoegen van een nieuwe leerling!");
              location.reload();
            } else {
              //  $formAddStudent.find('.alert-info').addClass('hidden');
              console.log(text.trim());
              $("html, body").animate({ scrollTop: 0 }, "fast");
              $formAddStudent.find(".alert-warning").removeClass("hidden");
            }
          },
        });
      } else {
        // Update
        $("#student-klas").removeAttr("disabled");
        $.ajax({
          url: "ajax/edit_leerling.php",
          cache: false,
          contentType: false,
          processData: false,
          //data: $formAddStudent.serialize(),
          data: new FormData(this),
          type: "POST",
          //dataType: "text",
          success: function (text) {
            $formAddStudent.find(".alert-info-update").removeClass("hidden");
            $formAddStudent.find(".alert-info").addClass("hidden");
            $formAddStudent.find(".alert-warning").addClass("hidden");
          },
          error: function (xhr, status, errorThrown) {
            console.log("error");
          },
          complete: function (xhr, status) {
            var $leerlingNR = $("#studentnummer").val(),
              $klas = $("#student-klas").val(),
              $achternaam = $("#achternaam").val(),
              $voornaam = $("#voornaam").val(),
              $geslacht = $("#geslacht").val(),
              $geboortedatum = $("#geboortedatum").val(),
              $geboorteplaats = $("#geboorteplaats").val(),
              $adres = $("#adres").val(),
              $telefoon = $("#telefoon").val(),
              $studentStatus = $("#status").val();

            //TODO: Ubicar el registro a actualizar

            //$('#dataRequest-student tbody').prepend('<tr><td>'+ $leerlingNR +'</td><td>'+ $voornaam +'</td><td>'+ $achternaam +'</td><td>'+ $geslacht +'</td><td>'+ $geboortedatum +'</td><td>'+ $klas +'</td><td></</tr>');

            $("input[type=text]").each(function () {
              $(this).val("");
            });
            alert("The student was updated successfully!");
            location.reload();
          },
        });
      }
    }
  });

  // Start CaribeDevelopers code validates date less than today

  function vd_less_today(date) {
    var x = new Date();
    var date = date.split("-");
    x.setFullYear(date[2], date[1] - 1, date[0]);
    var today = new Date();

    if (x >= today) return false;
    else return true;
  }
  // End CaribeDevelopers code

  // Mobile navigation. Click to hide the navigation on the left (Toggle hide/show)
  $mobileNav.on("click", function () {
    if ($mobileNav.hasClass("animateback")) {
      $mobileNav.removeClass("animateback");

      $("#sub-nav").css({
        "margin-left": 0,
      });
      $(".push-content-220").css({
        "margin-left": "190px",
      });
    } else {
      $mobileNav.addClass("animateback");

      $("#sub-nav").css({
        "margin-left": "-190px",
      });
      $(".push-content-220").css({
        "margin-left": 0,
      });
    }
  });

  $multilevelNav.find(".nav-item").on("click", function () {
    var $this = $(this);

    $this.addClass("open active");
    $this.parent(".multilevel").addClass("open active");

    $this.parent(".multilevel").siblings().removeClass("open active");

    $this.find("i").removeClass("fa-angle-down").addClass("fa-angle-left");

    $this.siblings().find(".nav-second-level").css({
      display: "none",
    });

    $this.find("i").removeClass("fa-angle-left").addClass("fa-angle-down");
    $this.parent("li").removeClass("open");
    $this
      .parent("li")
      .siblings("li")
      .find(".nav-item i")
      .removeClass("fa-angle-down")
      .addClass("fa-angle-left");

    $this.find(".nav-second-level").css({
      display: "block",
    });

    return false;
  });

  // FIXED FILTER NAVIGATION WHEN SCROLLING
  if ($fixedFilterBar.length) {
    var iStartOffset = $fixedFilterBar.position().top,
      elm = $fixedFilterBar[0];

    // CHECK WITH MODERNIZR IF CSS TRANSFORM IS SUPPORTED IN THE BROWSER
    if (Modernizr.csstransforms) {
      // REPOSITION NAVIGATION TO BE FIXED USING CSS3 TRANSFORM
      $(window).on(
        "scroll.offsetpagenav",
        $.proxy(reposPageNav, window, elm, iStartOffset)
      );
    } else {
      // REPOSITION NAVIGATION TO BE FIXED IF BROWSER DO NOT CSS3 TRANSFORMING
      $(window).on(
        "scroll.offsetpagenav",
        $.proxy(reposPageNavNoTransforms, window, elm, iStartOffset)
      );
    }
  }

  // Toggle calendar
  if ($toggleCalendar.length) {
    $toggleCalendar.on("click", function () {
      $toggleCalendar.addClass("toggleBack");
      $leerlingLogboek.hide();
      $("#fullcalendar-container").show();
    });
  }

  // If exists call the function
  if ($canvasGraph.length) {
    initGraphs();
  }

  // If exists call the function
  if ($tabs.length) {
    initTabs();
  }

  // If exists call the function
  if ($sliders.length) {
    initSlider();
  }

  // If exists call the function
  if ($dataRequest == "yes") {
    initLoadDataTablePlugin();
  }

  // If exists do the on click event
  if ($("#calculateloanbtn").length) {
    $("#calculateloanbtn").on("click", function () {
      $("#calculatedbox").removeClass("hidden");
      $("#calculatedbox").removeClass("loading");
    });
  }

  // If exists do the on click event
  if ($createcustomer.length) {
    $("#showform").on("click", function () {
      $createcustomer.removeClass("hidden");
    });
  }

  // If exists do the on click event
  // Test if the identifier exist and do a GET or go to a function
  if ($modalBtn.length) {
    $modalBtn.on("click", function () {
      var currentID = $(this).attr("data-id");

      if ($("#modalinfo").length) {
        $.get("", { id: currentID }, function (data) {
          initOpenModal(currentID);
        });
      } else if ($("#laatabsent").length) {
        initOpenModal();
      } else {
        initOpenModal();
      }
    });
  }

  // If exists go to function
  if ($datepicker.length) {
    initDatePicker();
  }

  // CaribeDevelopers begin Code

  // If exists go to function
  if ($datepickerpast.length) {
    initDatePickerPast();
  }
  // CaribeDevelopers End Code

  // If exists go to function
  if ($datepickerfull.length) {
    initDatePickerFull();
  }
  // CaribeDevelopers End Code

  // If exists go to function
  if ($scrollContainer.length) {
    initCreateScrollBar();
  }

  // If exists go to function
  if ($dropdown.length) {
    initDropdown();
  }

  // If exists go to function
  if ($fullCalendar.length) {
    initFullCalendarRender();
  }

  /* Studenten-controls */

  // If exists get JSON from the back-end and loop through the data
  if ($("#student-klas").length) {
    $.getJSON("ajax/getklassen_json.php", function (result) {
      var klas = $("#student-klas");
      $.each(result, function () {
        klas.append($("<option />").val(this.klas).text(this.klas));
      });
      klas.append($("<option />").val("Nieuw").text("Nieuw"));
      klas.append($("<option />").val("Aanmelding").text("Aanmelding"));
      klas.append($("<option />").val("Dropout").text("Dropout"));
      klas.append($("<option />").val("Uitgeschreven").text("Uitgeschreven"));
    });
  }

  // If exists get JSON from the back-end and loop through the data
  if ($("#studenten_lijst").length) {
    $.getJSON("ajax/getleerling_json.php", function (result) {
      var student = $("#studenten_lijst");
      $.each(result, function () {
        student.append($("<option />").val(this.id).text(this.student));
      });
      student.val($("#id_student").val());
    });
  }

  /* Studenten-controls */

  /* Klas-controls */

  /* Klas-control */

  /* Cijfers.php Vak-controls */

  //If exists get JSON from the back-end and loop through the data

  /* Cijfers.php Klas-control */

  function cijfersVakkenLijst() {
    $.getJSON("ajax/getklassen_json.php", function (result) {
      var klas = $("#cijfers_klassen_lijst");
      $.each(result, function () {
        klas.append($("<option />").val(this.klas).text(this.klas));
      });
      //Caribe Dev
      var g_klass = $("#cijfers_klassen_lijst option:selected").val();
      $.getJSON(
        "ajax/getvakken_json.php",
        { klas: g_klass },
        function (result) {
          var vak = $("#cijfers_vakken_lijst");
          $.each(result, function () {
            vak.append($("<option />").val(this.id).text(this.vak));
          });
        }
      );
    });
  }

  function houdingVakkenLijst() {
    $.getJSON("ajax/getklassen_json.php", function (result) {
      var klas = $("#houding_klassen_lijst");
      $.each(result, function () {
        klas.append($("<option />").val(this.klas).text(this.klas));
      });
      //Caribe Dev
      var g_klass = $("#houding_klassen_lijst option:selected").val();
      $.getJSON(
        "ajax/getvakken_json.php",
        { klas: g_klass },
        function (result) {
          var vak = $("#houding_vakken_lijst");
          $.each(result, function () {
            vak.append($("<option />").val(this.id).text(this.vak));
          });
        }
      );
    });
  }

  function verzuimVakkenLijst() {
    $.getJSON("ajax/getklassen_json.php", function (result) {
      var klas = $("#verzuim_klassen_lijst");
      $.each(result, function () {
        klas.append($("<option />").val(this.klas).text(this.klas));
      });
      //Caribe Dev
      var g_klass = $("#verzuim_klassen_lijst option:selected").val();
      $.getJSON(
        "ajax/getvakken_json.php",
        { klas: g_klass },
        function (result) {
          var vak = $("#verzuim_vakken_lijst");
          $.each(result, function () {
            vak.append($("<option />").val(this.id).text(this.vak));
          });
        }
      );
    });
  }

  /* Verzuim.php Klas-control */

  // function cijfersVerzuimLijst() {
  //   $.getJSON("ajax/getklassen_json.php", function(result){
  //     var klas = $("#verzuim_klassen_lijst");
  //     $.each(result, function(){
  //       klas.append($("<option />").val(this.klas).text(this.klas));
  //     });
  //   });
  // };

  /* Houding.php Klas-control */

  // function cijfersHoudingLijst() {
  //   $.getJSON("ajax/getklassen_json.php", function(result){
  //     var klas = $("#houding_klassen_lijst");
  //     $.each(result, function(){
  //       klas.append($("<option />").val(this.klas).text(this.klas));
  //     });
  //   });
  // };

  /* Rapport_export.php Klas-control */

  function cijfersRapportLijst() {
    $.getJSON("ajax/getklassen_json.php", function (result) {
      var klas = $("#rapport_klassen_lijst");
      $.each(result, function () {
        klas.append($("<option />").val(this.klas).text(this.klas));
      });
    });
  }

  // Function for the re-initializing the Modal
  function reInitializeModal() {
    $(".modal-btn").on("click", function () {
      var currentID = $(this).attr("data-id");

      if ($(".modal-btn").length) {
        $.get("", { id: currentID }, function (data) {
          initOpenModal(currentID);
        });
      } else {
        initOpenModal();
      }
    });
  }

  // Function for the Date picker
  function initDatePicker() {
    var _$datepicker = $datepicker;

    //CaribeDevelopers end code

    var _config_calendar =
      $("#calendar_config").length > 0 ? $("#calendar_config").val() : "";

    var _startdate =
      _config_calendar.length > 0 ? _config_calendar.split("|")[0] : "";
    var _enddate =
      _config_calendar.length > 0 ? _config_calendar.split("|")[1] : "0d";
    var _todayHighlight =
      _config_calendar.length > 0
        ? _config_calendar.split("|")[2] === "true"
        : true;
    var _daysOfWeekDisabled =
      _config_calendar.length > 0 ? _config_calendar.split("|")[3] : "";

    Modernizr.load({
      test: $.fn.datepicker,
      nope: [
        WebApi.Config.baseUri + "assets/js/lib/jquery.datepicker.min.js",
        WebApi.Config.baseUri + "assets/css/datepicker.min.css",
      ],
      complete: function openDatePicker() {
        $datepicker.datepicker({
          format: "d-m-yyyy",
          startDate: _startdate,
          // endDate             : _enddate,
          daysOfWeekDisabled: _daysOfWeekDisabled,
          todayHighlight: _todayHighlight,
        });

        //CaribeDevelopers end code
      },
    });
  }

  // Function for the Date picker
  function initDatePickerPast() {
    var _$datepicker = $datepickerpast;
    alert("Entramos al pasado");
    alert(_$datepicker.attr("name"));

    //CaribeDevelopers end code

    var _config_calendar =
      $("#calendar_" + _$datepicker.attr("name")).length > 0
        ? $("#calendar_" + _$datepicker.attr("name")).val()
        : "";
    alert("Esta es la configuracion del pasado: " + _config_calendar);

    var _startdate =
      _config_calendar.length > 0 ? _config_calendar.split("|")[0] : "";
    var _enddate =
      _config_calendar.length > 0 ? _config_calendar.split("|")[1] : "0d";
    var _todayHighlight =
      _config_calendar.length > 0
        ? _config_calendar.split("|")[2] === "true"
        : true;
    var _daysOfWeekDisabled =
      _config_calendar.length > 0 ? _config_calendar.split("|")[3] : "0123456";

    alert(_startdate);
    alert(_enddate);
    alert(_todayHighlight);
    alert(_daysOfWeekDisabled);

    Modernizr.load({
      test: $.fn.datepicker,
      nope: [
        WebApi.Config.baseUri + "assets/js/lib/jquery.datepicker.min.js",
        WebApi.Config.baseUri + "assets/css/datepicker.min.css",
      ],
      complete: function openDatePicker() {
        _$datepicker.datepicker({
          format: "d-m-yyyy",
          startDate: _startdate,
          endDate: _enddate,
          daysOfWeekDisabled: _daysOfWeekDisabled,
          todayHighlight: _todayHighlight,
        });

        //CaribeDevelopers end code
      },
    });
  }

  // Function for the Date picker
  function initDatePickerFull() {
    var _$datepicker = $datepickerfull;
    alert("Entramos al Full");
    alert(_$datepicker.attr("name"));

    //CaribeDevelopers end code

    var _config_calendar =
      $("#calendar_" + _$datepicker.attr("name")).length > 0
        ? $("#calendar_" + _$datepicker.attr("name")).val()
        : "";
    alert("Esta es la configuracion del pasado: " + _config_calendar);

    var _startdate =
      _config_calendar.length > 0 ? _config_calendar.split("|")[0] : "";
    var _enddate =
      _config_calendar.length > 0 ? _config_calendar.split("|")[1] : "0d";
    var _todayHighlight =
      _config_calendar.length > 0
        ? _config_calendar.split("|")[2] === "true"
        : true;
    var _daysOfWeekDisabled =
      _config_calendar.length > 0 ? _config_calendar.split("|")[3] : "0123456";

    alert(_startdate);
    alert(_enddate);
    alert(_todayHighlight);
    alert(_daysOfWeekDisabled);

    Modernizr.load({
      test: $.fn.datepicker,
      nope: [
        WebApi.Config.baseUri + "assets/js/lib/jquery.datepicker.min.js",
        WebApi.Config.baseUri + "assets/css/datepicker.min.css",
      ],
      complete: function openDatePicker() {
        _$datepicker.datepicker({
          format: "d-m-yyyy",
          startDate: _startdate,
          endDate: _enddate,
          daysOfWeekDisabled: _daysOfWeekDisabled,
          todayHighlight: _todayHighlight,
        });

        //CaribeDevelopers end code
      },
    });
  }

  // Function to open the Modal, we pass currentID, because it's needs to be dynamic
  function initOpenModal(currentID) {
    Modernizr.load({
      test: $.fn.modalbox,
      nope: WebApi.Config.baseUri + "assets/js/lib/jquery.modal.js",
      complete: function modalInit() {
        if ($("#modalinfo").length) {
          $("#modalinfo").modal("show");

          $("#modal-label").html("Toets informatie " + currentID);
          $opslaan_toets.find(".alert-ok").addClass("hidden");
          if ($("#id_cijfersextra").val() == undefined) {
            $("#id_cijfersextra_modal").val("0"); //Agregado por Janio por cambios en Cijfers
            $("#klas_cijfersextra_modal").val(
              $("#cijfers_klassen_lijst").val()
            ); //Agregado por Janio por cambios en Cijfers
            $("#schooljaar_cijfersextra_modal").val(""); //Agregado por Janio por cambios en Cijfers
            $("#rapnummer_cijfersextra_modal").val(
              $("#cijfers_rapporten_lijst").val()
            ); //Agregado por Janio por cambios en Cijfers
            $("#vak_cijfersextra_modal").val($(".cijfers_vakken_lijst").val()); //Agregado por Janio por cambios en Cijfers
            $("#duedatum").val("");
            $("#extra-informatie").val("");
          } else {
            $("#duedatum").val($("#dc" + currentID + "_cijfersextra").val()); //Agregado por Janio por cambios en Cijfers
            $("#extra-informatie").val(
              $("#oc" + currentID + "_cijfersextra").val()
            ); //Agregado por Janio por cambios en Cijfers
            $("#id_cijfersextra_modal").val($("#id_cijfersextra").val()); //Agregado por Janio por cambios en Cijfers
            $("#klas_cijfersextra_modal").val($("#klas_cijfersextra").val()); //Agregado por Janio por cambios en Cijfers
            $("#schooljaar_cijfersextra_modal").val(
              $("#schooljaar_cijfersextra").val()
            ); //Agregado por Janio por cambios en Cijfers
            $("#rapnummer_cijfersextra_modal").val(
              $("#rapnummer_cijfersextra").val()
            ); //Agregado por Janio por cambios en Cijfers
            $("#vak_cijfersextra_modal").val($("#vak_cijfersextra").val()); //Agregado por Janio por cambios en Cijfers
          }

          $("#index_modal").val(currentID);

          $("#opslaan-toets").on("submit", function (e) {
            e.preventDefault();
            if (
              ($("#duedatum").val() == "" ||
                $("#extra-informatie").val() == "") &&
              $("#id_cijfersextra_modal").val() == 0
            ) {
              alert("Please, fill all fields!");
              return false;
            }

            //Begin Modificaciones Janio por cambios en Cijfers
            if ($("#id_cijfersextra_modal").val() == "")
              $("#id_cijfersextra_modal").val("0");

            if ($("#cijfers_user_rights").val() === "TEACHER") {
              var $id_cijfersextra = $("#id_cijfersextra_modal").val(),
                $klas_cijfersextra = $("#list_class_teacher").val(),
                $schooljaar_cijfersextra = $(
                  "#schooljaar_cijfersextra_modal"
                ).val(),
                $rapnummer_cijfersextra = $(
                  "#rapnummer_cijfersextra_modal"
                ).val(),
                $vak_cijfersextra = $("#cijfers_vakken_list_teacher").val(),
                $index = $("#index_modal").val(),
                $extra = $("#extra-informatie").val(),
                $duedatum = $("#duedatum").val();
            } else {
              var $id_cijfersextra = $("#id_cijfersextra_modal").val(),
                $klas_cijfersextra = $("#klas_cijfersextra_modal").val(),
                $schooljaar_cijfersextra = $(
                  "#schooljaar_cijfersextra_modal"
                ).val(),
                $rapnummer_cijfersextra = $(
                  "#rapnummer_cijfersextra_modal"
                ).val(),
                $vak_cijfersextra = $("#vak_cijfersextra_modal").val(),
                $index = $("#index_modal").val(),
                $extra = $("#extra-informatie").val(),
                $duedatum = $("#duedatum").val();
            }
            $.post(
              "ajax/updatecijfersextra.php",
              {
                id_cijfersextra: $id_cijfersextra,
                klas_cijfersextra: $klas_cijfersextra,
                schooljaar_cijfersextra: $schooljaar_cijfersextra,
                rapnummer_cijfersextra: $rapnummer_cijfersextra,
                vak_cijfersextra: $vak_cijfersextra,
                index: $index,
                extra: $extra,
                duedatum: $duedatum,
              },
              function (data) {
                //Do nothing
              }
            )
              .done(function (data) {
                if (data <= 0) {
                  $opslaan_toets.find(".alert-error").removeClass("hidden");
                } else if (data == 1) {
                  if (
                    $("#modal-label").html() ==
                    "Toets informatie " + currentID
                  ) {
                    // alert('Cijfers Info Extra Added Successfully!');
                    // $opslaan_toets.find('.alert-ok').removeClass('hidden');
                    $("#dc" + currentID + "_cijfersextra").val(
                      $("#duedatum").val()
                    ); //Agregado por Janio por cambios en Cijfers
                    $("#oc" + currentID + "_cijfersextra").val(
                      $("#extra-informatie").val()
                    ); //Agregado por Janio por cambios en Cijfers
                    $opslaan_toets.find(".alert-error").addClass("hidden");
                    $("#btn_submit_cijfers").submit();
                  }
                  $("#modalinfo").modal("hide");
                  $("#loader_spn").toggleClass("hidden");
                }
              })
              .fail(function () {
                alert("Error, please contact developers.");
              });

            //End Modificaciones Janio por cambios en Cijfers
            return false;
          });
        } else if ($("#laatabsent").length) {
          $("#laatabsent").modal("show");

          $("#opslaan-absent").one("submit", function (e) {
            e.preventDefault();

            $.post("", $(this).serialize(), function (data) {
              setTimeout(function () {
                $("#laatabsent").modal("hide");
              }, 2000);
            });
            return false;
          });
        } else {
          $(".modal-suggesties").on("click", function (e) {
            console.log("testing");

            e.preventDefault();

            $("#modalSuggesties").modal("show");

            return false;
          });

          $("#modallogboek").modal("show");

          $("#opslaan-leerling-logboek").one("submit", function (e) {
            e.preventDefault();

            $.post("", $(this).serialize(), function (data) {
              setTimeout(function () {
                $("#modallogboek").modal("hide");
              }, 2000);
            });
            return false;
          });
        }
      },
    });
  }

  // Function for tabs
  function initTabs() {
    Modernizr.load({
      test: $.fn.tabs,
      nope: WebApi.Config.baseUri + "assets/js/lib/jquery.tabs.js",
      complete: function switchTabs() {
        $(".nav-tabs a").on("click", function (e) {
          e.preventDefault();
          $(this).tab("show");
        });
      },
    });
  }

  // Function for slider, slider plugin used is Flexslider
  function initSlider() {
    Modernizr.load({
      test: $.flexslider,
      nope: WebApi.Config.baseUri + "assets/js/lib/jquery.flexslider.min.js",
      complete: function initFlexslider() {
        $sliders.each(function initGalleries(i, e) {
          var $slider = $(e),
            oUserSettings = $slider.data();
          $slider.flexslider({
            selector: ".slider-canvas > .slider-item",
            animation:
              typeof oUserSettings.type == "undefined"
                ? "slide"
                : oUserSettings.type,
            easing:
              typeof oUserSettings.easing == "undefined"
                ? "swing"
                : oUserSettings.easing,
            direction:
              typeof oUserSettings.direction == "undefined"
                ? "horizontal"
                : oUserSettings.direction,
            slideshow:
              typeof oUserSettings.slideshow == "undefined"
                ? true
                : oUserSettings.slideshow,
            slideshowSpeed:
              typeof oUserSettings.slideshowinterval == "undefined"
                ? 4000
                : oUserSettings.slideshowinterval,
            animationSpeed:
              typeof oUserSettings.animationspeed == "undefined"
                ? 600
                : oUserSettings.animationspeed,
            pauseOnAction:
              typeof oUserSettings.pauseonaction == "undefined"
                ? true
                : oUserSettings.pauseonaction,
            pauseOnHover:
              typeof oUserSettings.pauseonhover == "undefined"
                ? true
                : oUserSettings.pauseonhover,
            controlNav:
              typeof oUserSettings.generatepagination == "undefined"
                ? false
                : oUserSettings.generatepagination,
            directionNav:
              typeof oUserSettings.generateslidecontrols == "undefined"
                ? false
                : oUserSettings.generateslidecontrols,
            prevText:
              typeof oUserSettings.previoustext == "undefined"
                ? "Vorige"
                : oUserSettings.previoustext,
            nextText:
              typeof oUserSettings.nexttext == "undefined"
                ? "Volgende"
                : oUserSettings.nexttext,
            keyboard:
              typeof oUserSettings.keyboardnavigationenabled == "undefined"
                ? true
                : oUserSettings.keyboardnavigationenabled,
            smoothHeight: true,
            reverse: true,
            touch: true,
            animationLoop: true,
            draggable: true,
            minItems:
              typeof oUserSettings.minitem == "undefined"
                ? true
                : oUserSettings.minitem,
            maxItems:
              typeof oUserSettings.maxitem == "undefined"
                ? true
                : oUserSettings.maxitem,
            move:
              typeof oUserSettings.move == "undefined"
                ? true
                : oUserSettings.move,
          });
        });
      },
    });
  }

  // Function for graphs
  function initGraphs() {
    Modernizr.load({
      test: $.canvasjs,
      nope: WebApi.Config.baseUri + "assets/js/jquery.canvasjs.min.js",
      complete: function drawTheGraphs() {
        var chart = new CanvasJS.Chart("chartContainer", {
          height: 300, //in pixels
          width: 850,
          axisY: {
            title: "New Customers",
            gridColor: "#cccccc",
            interlacedColor: "#ffffff",
          },
          animationEnabled: true,
          data: [
            {
              type: "stackedColumn",
              color: "#91cea2",
              toolTipContent:
                "{label}<br/><span style='\"'color: {color};'\"'><strong>{name}</strong></span>: {y} customers",
              name: "Total Customer",
              showInLegend: "true",
              dataPoints: [
                { y: 120, label: "MAY" },
                { y: 80, label: "JUN" },
                { y: 160, label: "JUL" },
                { y: 40, label: "AUG" },
                { y: 20, label: "SEPT" },
                { y: 120, label: "OKT" },
              ],
            },
            {
              type: "stackedColumn",
              color: "#989898",
              toolTipContent:
                "{label}<br/><span style='\"'color: {color};'\"'><strong>{name}</strong></span>: {y} customers",
              name: "New Customer",
              showInLegend: "true",
              dataPoints: [
                { y: 20, label: "MAY" },
                { y: 40, label: "JUN" },
                { y: 5, label: "JUL" },
                { y: 10, label: "AUG" },
                { y: 20, label: "SEPT" },
                { y: 15, label: "OKT" },
              ],
            },
          ],
          legend: {
            cursor: "pointer",
            itemclick: function (e) {
              if (
                typeof e.dataSeries.visible === "undefined" ||
                e.dataSeries.visible
              ) {
                e.dataSeries.visible = false;
              } else {
                e.dataSeries.visible = true;
              }
              chart.render();
            },
          },
        });

        chart.render();
      },
    });
  }

  function initCreateScrollBar() {
    Modernizr.load({
      test: $.slimScroll,
      nope: WebApi.Config.baseUri + "assets/js/lib/jquery.slimscroll.min.js",
      complete: function executeScrollBar() {
        var iWindowHeight = $window.outerHeight();

        $innerScrollContainer.slimScroll({
          height: iWindowHeight + "px",
          position: "right",
          size: "5px",
          railColor: "rgba(244,244,244,0.3)",
          alwaysVisible: false,
          distance: "0px",
          railVisible: true,
          // railColor       : 'rgba(255,255,255,0)',
          railOpacity: 0.3,
          wheelStep: 8,
          allowPageScroll: false,
          disableFadeOut: true,
        });
      },
    });
  }

  // Function for dropdowns
  function initDropdown() {
    Modernizr.load({
      test: $.fn.dropdown,
      nope: WebApi.Config.baseUri + "assets/js/lib/jquery.dropdown.js",
      complete: function dropdownInit() {
        $dropdown.dropdown();
      },
    });
  }

  // Function for calendar
  function initFullCalendarRender() {
    Modernizr.load({
      test: $.calendar,
      nope: [
        WebApi.Config.baseUri + "assets/css/calendar.css",
        WebApi.Config.baseUri + "assets/js/lib/jquery.tooltip.min.js",
        WebApi.Config.baseUri + "assets/js/lib/underscore.min.js",
        WebApi.Config.baseUri + "assets/js/lib/calendar.min.js",
      ],
      complete: function executeFullCalendar() {
        throttle(
          function () {
            initEqualHeights();
          },
          this,
          500
        );

        $fullCalendar.calendar({ events_source: "ajax/events.json.php" });

        var options = {
          events_source: "events.json.php",
          view: "month",
          tmpl_path: "tmpls/",
          tmpl_cache: false,
          day: "2013-03-12",
          onAfterEventsLoad: function (events) {
            if (!events) {
              return;
            }
            var list = $("#eventlist");
            list.html("");

            $.each(events, function (key, val) {
              $(document.createElement("li"))
                .html('<a href="' + val.url + '">' + val.title + "</a>")
                .appendTo(list);
            });
          },
          onAfterViewLoad: function (view) {
            $(".page-header h3").text(this.getTitle());
            $(".btn-group button").removeClass("active");
            $('button[data-calendar-view="' + view + '"]').addClass("active");
          },
          classes: {
            months: {
              general: "label",
            },
          },
        };

        var calendar = $("#calendar").calendar(options);

        /*
         ** create prev, next, year, month, day
         ** create a calendar item
         ** create an event
         ** create modal
         */

        $(".btn-group button[data-calendar-nav]").each(function () {
          var $this = $(this);
          $this.click(function () {
            calendar.navigate($this.data("calendar-nav"));
          });
        });

        $(".btn-group button[data-calendar-view]").each(function () {
          var $this = $(this);
          $this.click(function () {
            calendar.view($this.data("calendar-view"));
          });
        });

        $("#first_day").change(function () {
          var value = $(this).val();
          value = value.length ? parseInt(value) : null;
          calendar.setOptions({ first_day: value });
          calendar.view();
        });

        $("#language").change(function () {
          calendar.setLanguage($(this).val());
          calendar.view();
        });

        $("#events-in-modal").change(function () {
          var val = $(this).is(":checked") ? $(this).val() : null;
          calendar.setOptions({ modal: val });
        });

        $("#format-12-hours").change(function () {
          var val = $(this).is(":checked") ? true : false;
          calendar.setOptions({ format12: val });
          calendar.view();
        });

        $("#show_wbn").change(function () {
          var val = $(this).is(":checked") ? true : false;
          calendar.setOptions({ display_week_numbers: val });
          calendar.view();
        });

        $("#show_wb").change(function () {
          var val = $(this).is(":checked") ? true : false;
          calendar.setOptions({ weekbox: val });
          calendar.view();
        });

        $("#events-modal .modal-header, #events-modal .modal-footer").on(
          "click",
          function (e) {
            // Something here should happen...
          }
        );
      },
    });
  }

  // Matchheight
  function initEqualHeights() {
    Modernizr.load({
      test: $.fn.matchHeight,
      nope: WebApi.Config.baseUri + "assets/js/lib/jquery.matchHeight.min.js",
      complete: function initEqualHeigthsExecution() {
        $equalHeightsonDivs.matchHeight();
      },
    });
  }

  function throttle(method, context, delay) {
    clearTimeout(method._tId);
    method._tId = setTimeout(function () {
      method.call(context);
    }, delay);
  }

  // FUNCTION REPOSITION FILTER BAR BY USE CSS3 TRANSFORM
  function reposPageNav(elm, iStartOffset) {
    var iScrollPos = $(this).scrollTop(),
      iYoffset = iScrollPos - iStartOffset,
      iFatFooterHeight =
        $("#main").outerHeight() -
        ($("#complementary").outerHeight() + $("#footer").outerHeight());

    if (iScrollPos > iStartOffset) {
      elm.style.webkitTransform = "translateY(" + iYoffset + "px)";
      elm.style.msTransform = "translateY(" + iYoffset + "px)";
      elm.style.OTransform = "translateY(" + iYoffset + "px)";
      elm.style.transform = "translateY(" + iYoffset + "px)";
    } else {
      elm.style.webkitTransform = "translateY(0)";
      elm.style.msTransform = "translateY(0)";
      elm.style.OTransform = "translateY(0)";
      elm.style.transform = "translateY(0)";
    }
    if (iScrollPos > iFatFooterHeight) {
      elm.style.webkitTransform = "translateY(" + iFatFooterHeight + "px)";
      elm.style.msTransform = "translateY(" + iFatFooterHeight + "px)";
      elm.style.OTransform = "translateY(" + iFatFooterHeight + "px)";
      elm.style.transform = "translateY(" + iFatFooterHeight + "px)";
    }
  }

  // FUNCTION REPOSITION FILTER BAR IF CSS3 TRANSFORM IS NOT SUPPORTED BY THE BROWSER
  function reposPageNavNoTransforms(elm, iStartOffset) {
    var iScrollPos = $(this).scrollTop(),
      iYoffset = iScrollPos - iStartOffset,
      iFatFooterHeight =
        $("#main").outerHeight() -
        ($("#complementary").outerHeight() + $("#footer").outerHeight());

    if (iScrollPos > iStartOffset) {
      elm.style.position = "relative";
      elm.style.top = iYoffset + "px";
    } else {
      elm.style.top = "0";
    }

    if (iScrollPos > iFatFooterHeight) {
      elm.style.position = "relative";
      elm.style.top = iFatFooterHeight + "px";
    }
  }

  // DATA TABLE PLUGIN SUPPORT
  function dataTablePlugin() {
    var _$dataTable = $dataTable.find(".table");

    Modernizr.load({
      test: $.fn.dataTables,
      nope: [
        WebApi.Config.baseUri + "assets/js/lib/dataTables.min.js",
        WebApi.Config.baseUri + "assets/js/lib/dataTable.bootstrap.min.js",
      ],
      complete: function dataTableSettings() {
        var $dataTablePagination = $(".data-table").attr(
            "data-table-pagination"
          ),
          $dataTableType = $(".data-table").attr("data-table-type"),
          $dataTableSearch = $(".data-table").attr("data-table-search");

        $dataTable.each(function (index, element) {
          if ($dataTableType == "on") {
            $dataTable.find(".table").DataTable({
              retrieve: true,
              paging: JSON.parse($dataTablePagination),
              searching: JSON.parse($dataTableSearch),
              responsive: true,
            });
          }
        });
      },
    });
  }

  function initAutoCompleteStudentList() {
    Modernizr.load({
      test: $.fn.typeahead,
      nope: WebApi.Config.baseUri + "assets/js/lib/typeahead.bundle.js",
      complete: function dataTableSettings() {
        $.get(
          "ajax/typeahead_getleerling_json.php",
          function (data) {
            var substringMatcher = function (strs) {
              return function findMatches(q, cb) {
                var matches, substringRegex;

                matches = [];

                var substrRegex = new RegExp(q, "i");

                $.each(strs, function (i, str) {
                  if (substrRegex.test(str)) {
                    matches.push(str);
                  }
                });
                cb(matches);
              };
            };

            var student = data;

            $("#search").typeahead(
              {
                minLength: 3,
                highlight: true,
              },
              {
                name: "student",
                source: substringMatcher(student),
              }
            );
          },
          "json"
        );
      },
    });
  }

  function save_in_bd_cijfer_localstorage() {
    //  var ls_cijfer_object  = JSON.parse(localStorage.getItem("ls_cijfer"));
    var ls_cijfer_object2 = JSON.parse(localStorage.getItem("ls_cijfer")); // a javascript object
    // console.log(ls_cijfer_object);
    // console.log(ls_cijfer_object[i_cijfer_array]);
    // console.log(ls_cijfer_object.length);
    if (ls_cijfer_object2) {
      var i = 0;
      var x = ls_cijfer_object2.length;
      // localStorage.clear();
      //console.log('this is the arrray at beginning' + ls_cijfer_object2);
      if (ls_cijfer_object2[0][0] != null) {
        for (i = 0; i < ls_cijfer_object2.length; i++) {
          var $id_cijfer = ls_cijfer_object2[i][0];
          var $studentid = ls_cijfer_object2[i][1];
          var $cijfername = ls_cijfer_object2[i][2];
          var $cijfervalue = ls_cijfer_object2[i][3];
          var $klas = ls_cijfer_object2[i][4];
          var $rapport = ls_cijfer_object2[i][5];
          var $vak = ls_cijfer_object2[i][6];

          //ls_cijfer_object2.splice([i], 1);
          //console.log("This the array before remove index position " + ls_cijfer_object2);
          // Upload Cijfers 1
          $.ajax({
            url: "ajax/update_cijfers.php",
            data:
              "id_cijfer=" +
              $id_cijfer +
              "&studentid=" +
              $studentid +
              "&cijfername=" +
              $cijfername +
              "&cijfervalue=" +
              $cijfervalue +
              "&klas=" +
              $klas +
              "&rapport=" +
              $rapport +
              "&vak=" +
              $vak,
            type: "POST",
            dataType: "HTML",
            cache: false,
            async: false,
            success: function (data) {
              check_ls_data = data;
              //console.log("esto es lo que tiene data "+ check_ls_data);
              $.post(
                "ajax/getgemiddelde.php",
                {
                  studentid: $studentid,
                  klas: $klas,
                  rapport: $rapport,
                  vak: $vak,
                },
                function (data) {}
              ).done(function (data) {
                $("#vak").find("tr.active td:last-child").html(data);
                $("#vak").find("tr.active").removeClass("active");
              });
            },
          });
          if (check_ls_data == -2) {
            //console.log("Break, LocalStorage variable could not be saved in the database, localStorage still has unsaved data")

            break;
          }
        } //this close FOR loop
        if (i == x) {
          //console.log("The LocalStorage ws succesfully saved in the database, the localStorage will be empty");
          localStorage.clear();
        }
      }
    }

    //else{
    //  console.log('No localStorage variable in the system')
    //}
  }
  //console.log("The value for Cijfer LocalStorage timer is"+ $('#timer_cijfer_ls').val());
  setInterval(save_in_bd_cijfer_localstorage, $("#timer_cijfer_ls").val());

  function save_cijfer_localstorage(
    $id_cijfer,
    $studentNr,
    $cijferName,
    $cijfer,
    $klas,
    $rapport,
    $vak
  ) {
    // var nrow = $('#vak >tbody >tr').length;
    // var nColumn = ($("#vak tr:last td").length)-3;
    // var numberOptions=nrow*nColumn;
    var cijfer_array = [
      $id_cijfer,
      $studentNr,
      $cijferName,
      $cijfer,
      $klas,
      $rapport,
      $vak,
    ];

    if (cijfer_object.length == 0) {
      localStorage.setItem(
        "ls_cijfer",
        JSON.stringify((cijfer_object[i_cijfer_array] = cijfer_array))
      );
      //console.log(localStorage.getItem("ls_cijfer"));
      var ls_cijfer_object = JSON.parse(localStorage.getItem("ls_cijfer"));
      // console.log(ls_cijfer_object);
      i_cijfer_array = i_cijfer_array + 1; //i_cijfer_array is the count variable
    } else {
      cijfer_object[i_cijfer_array] = cijfer_array;
      localStorage.setItem("ls_cijfer", JSON.stringify(cijfer_object)); //ls_cijfer is the local storage variable with cijfers in javascript object

      var ls_cijfer_object = JSON.parse(localStorage.getItem("ls_cijfer")); // a javascript object
      // console.log(ls_cijfer_object);
      // console.log(ls_cijfer_object[i_cijfer_array]);
      // console.log(ls_cijfer_object.length);
      // console.log(ls_cijfer_object[i_cijfer_array][2]);
      i_cijfer_array = i_cijfer_array + 1;
    }
  }

  function extra_save_cijfer_sessionStorage(
    $id_cijfer,
    $studentNr,
    $cijferName,
    $cijfer,
    $klas,
    $rapport,
    $vak,
    $cell_cijfer
  ) {
    if (extra_cijfer_object.length == 0) {
      var extra_cijfer_array = [""]; //Fill the first position in array in 0
      extra_cijfer_object[0] = extra_cijfer_array;

      // the position number 1 in the extra_array_object its filled with the real cijfers data.
      extra_cijfer_array = [
        $id_cijfer,
        $studentNr,
        $cijferName,
        $cijfer,
        $klas,
        $rapport,
        $vak,
        $cell_cijfer,
      ];
      extra_cijfer_object[1] = extra_cijfer_array;

      //Set the sessionStorage with extra_cijfer_object, the firts position in array is (0), and the second contain real cijfers data.
      sessionStorage.setItem(
        "extra_ss_cijfer",
        JSON.stringify(extra_cijfer_object)
      );
      i_extra_cijfer_array = 2; // set the next position in array
    } else {
      extra_cijfer_array = [
        $id_cijfer,
        $studentNr,
        $cijferName,
        $cijfer,
        $klas,
        $rapport,
        $vak,
        $cell_cijfer,
      ];
      extra_cijfer_object[i_extra_cijfer_array] = extra_cijfer_array; // in this time, the array object take the third position (2)
      sessionStorage.setItem(
        "extra_ss_cijfer",
        JSON.stringify(extra_cijfer_object)
      );

      i_extra_cijfer_array++;
    }
  }
  function extra_save_in_bd_cijfer_sessionStorage() {
    $("#loader_spn").toggleClass("hidden");
    var extra_ss_cijfers_data = JSON.parse(
      sessionStorage.getItem("extra_ss_cijfer")
    ); // a javascript object with all cijfers data saved in sessionStorage

    if (extra_ss_cijfers_data) {
      var i = 0;
      var x = extra_ss_cijfers_data.length;
      // sessionStorage.clear();
      //console.log('this is the arrray at beginning' + extra_ss_cijfers_data);
      var cell_extra_saved = [];
      if (extra_ss_cijfers_data[0][0] != null) {
        for (i = 1; i < x; i++) {
          var $id_cijfer = extra_ss_cijfers_data[i][0];
          var $studentid = extra_ss_cijfers_data[i][1];
          var $cijfername = extra_ss_cijfers_data[i][2];
          var $cijfervalue = extra_ss_cijfers_data[i][3];
          var $klas = extra_ss_cijfers_data[i][4];
          var $rapport = extra_ss_cijfers_data[i][5];
          var $vak = extra_ss_cijfers_data[i][6];
          var $cell_cijfer = extra_ss_cijfers_data[i][7];

          cell_extra_saved.push($cell_cijfer);
          //extra_ss_cijfers_data.splice([i], 1);
          //console.log("This the array before remove index position " + extra_ss_cijfers_data);
          // Upload Cijfers 2
          $.ajax({
            url: "ajax/update_cijfers.php",
            data:
              "id_cijfer=" +
              $id_cijfer +
              "&studentid=" +
              $studentid +
              "&cijfername=" +
              $cijfername +
              "&cijfervalue=" +
              $cijfervalue +
              "&klas=" +
              $klas +
              "&rapport=" +
              $rapport +
              "&vak=" +
              $vak,
            type: "POST",
            dataType: "HTML",
            cache: false,
            async: false,
            success: function (data) {
              check_ss_data = data;
              //console.log("esto es lo que tiene data "+ check_ss_data);
              $.post(
                "ajax/getgemiddelde.php",
                {
                  studentid: $studentid,
                  klas: $klas,
                  rapport: $rapport,
                  vak: $vak,
                },
                function (data) {}
              ).done(function (data) {
                $("#vak").find("tr.active td:last-child").html(data);
                $("#vak").find("tr.active").removeClass("active");
              });
            },
          });
          if (check_ss_data == -2) {
            //console.log("Break, sessionStorage variable could not be saved in the database, sessionStorage still has unsaved data")

            break;
          }
        } //this close FOR loop
        if (i == x) {
          //console.log("The sessionStorage ws succesfully saved in the database, the sessionStorage will be empty");
          sessionStorage.clear();
          extra_cijfer_object = [];
          i_extra_cijfer_array = 0;
          extra_ss_cijfers_data = 0;
          alert(
            "You have successfully saved " +
              (x - 1) +
              " notes in the system and you have " +
              wrong_cells.length +
              " to check..."
          );
          i_btn_extra_save = 0;
          $("#btn_extra_save_cijfer").text("Ex. Save: " + i_btn_extra_save);
        }
        //fill blue changes cells
        var i_cell_extra_saved = cell_extra_saved.length;
        for (i = 0; i <= i_cell_extra_saved; i++) {
          $("[id_cell_cijfer='" + cell_extra_saved[i] + "']").css({
            "background-color": "rgba(145,206,162,0.9)",
          });
        }

        //set blanks again
        setTimeout(function () {
          var i_cell_extra_saved = cell_extra_saved.length;
          for (i = 0; i <= i_cell_extra_saved; i++) {
            $("[id_cell_cijfer='" + cell_extra_saved[i] + "']").css({
              "background-color": "",
            });
          }
        }, 2000);
      }
    }
  }
  $("#btn_extra_save_cijfer").click(function (e) {
    e.preventDefault();
    var check_ss = JSON.parse(sessionStorage.getItem("extra_ss_cijfer"));
    if (check_ss == null) {
      alert("Sorry, you do not have data to save in the System");
    } else {
      //set time out to show loader_spn, without setTimeout, this loader not work in google chrome
      setTimeout(function () {
        extra_save_in_bd_cijfer_sessionStorage();
      }, 500);
      $("#loader_spn").toggleClass("hidden");
    }
  });

  function get_number_messages() {
    $.post("ajax/get_number_unread_notifications.php", {}, function (data) {
      $("#count_unread_notifications").html(data);
    });
    $.post("ajax/get_number_unread_messages.php", {}, function (data) {
      $("#count_unread_messages").html(data);
    });
    $.post("ajax/getinboxmessages_tabel.php", {}, function (data) {
      $("#table_notfications_messages_result").html(data);
    });
    $.post("ajax/getnotifications_tabel.php", {}, function (data) {
      $("#table_notifications_result").html(data);
    });
  }

  get_number_messages(); // This will run on page load
  setInterval(function () {
    // get_number_messages() // this will run after every 5 seconds
  }, 15000);

  // Always return the WebApi
  return WebApi;
})(WebApi || {}, jQuery, Modernizr, this, this.document);
