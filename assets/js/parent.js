var selected_schooljaar = "2022-2023";
function getParam(key) {
  // Find the key and everything up to the ampersand delimiter
  var value = RegExp("" + key + "[^&]+").exec(window.location.search);

  // Return the unescaped value minus everything starting from the equals sign or an empty string
  return unescape(!!value ? value.toString().replace(/^[^=]+./, "") : "");
}

var studentid = getParam("id");
var familyid = getParam("id_family");

$.post(
  "ajax/get_number_unread_notifications_parent.php?",
  {
    id_student: studentid,
  },
  function (data) {
    $("#unread").html(data);
  }
);

$(".message_unread").click(function (e) {
  e.preventDefault();
  window.location.replace(
    "parent_notifications.php?id=" + studentid + "&id_family=" + familyid
  );
});
$("#student_link").click(function (e) {
  e.preventDefault();
  window.location.replace(
    "home_parent.php?id=" + studentid + "&id_family=" + familyid
  );
});

$.get(
  "ajax/getleerlingdetail_tabel_mobile_parent.php?id=" + studentid,
  {},
  function (data) {
    $("#dataRequest-student_detail").html(data);
  }
);
$.get(
  "ajax/getleerlingdetail_Last_cijfers_tabel_mobil.php?id=" +
    studentid +
    "&schoolJaar=2023-2024",
  {},
  function (data) {
    $("#dataRequest-last_cijfers_detail").html(data);
    anchoVentana = window.innerWidth;
    if (anchoVentana < 601) {
      contador = 1;
      $("#c tbody tr").each(function () {
        if (contador > 5) {
          $(this).css("display", "none");
        }
        contador = contador + 1;
      });
    }
    $("#mostrar").click(function (e) {
      e.preventDefault();
      $("#c tbody [style*='display: none']").show();
      $("#mostrar").css("display", "none");
      $("#ocultar").show();
    });
    $("#ocultar").click(function (e) {
      e.preventDefault();
      contador = 1;
      $("#c tbody tr").each(function () {
        if (contador > 5) {
          $(this).css("display", "none");
        }
        contador = contador + 1;
      });
      $("#mostrar").show();
      $("#ocultar").css("display", "none");
    });
  }
);

//
/*
$.get("ajax/get_documents_by_studentid.php?id=" + studentid , {}, function (data) {
  $("#dataRequest-documents_detail").append(data);
});
*/
$(document).ready(function () {
  $("#dataRequest-cijfers_detail").empty();
  $("#_2023_2024").click();
});
function seeadd(vak, rapp, klas, oc) {
  $.get(
    "ajax/get_oc_coments.php?vak=" + vak + "&rapp=" + rapp + "&klas=" + klas,
    {},
    function (data) {
      var obj = jQuery.parseJSON(data);
      var coment;
      var tex;
      var datum;

      if (oc === "oc1") {
        tex = 5;
        datum = 25;
      }

      if (oc === "oc2") {
        tex = 6;
        datum = 26;
      }

      if (oc === "oc3") {
        tex = 7;
        datum = 27;
      }

      if (oc === "oc4") {
        tex = 8;
        datum = 28;
      }

      if (oc === "oc5") {
        tex = 9;
        datum = 29;
      }

      if (oc === "oc6") {
        tex = 10;
        datum = 30;
      }

      if (oc === "oc7") {
        tex = 11;
        datum = 31;
      }

      if (oc === "oc8") {
        tex = 12;
        datum = 32;
      }

      if (oc === "oc9") {
        tex = 13;
        datum = 33;
      }

      if (oc === "oc10") {
        tex = 14;
        datum = 34;
      }

      if (oc === "oc11") {
        tex = 15;
        datum = 35;
      }

      if (oc === "oc12") {
        tex = 16;
        datum = 36;
      }

      if (oc === "oc13") {
        tex = 17;
        datum = 37;
      }

      if (oc === "oc14") {
        tex = 18;
        datum = 38;
      }

      if (oc === "oc15") {
        tex = 19;
        datum = 39;
      }

      if (oc === "oc16") {
        tex = 20;
        datum = 40;
      }

      if (oc === "oc17") {
        tex = 21;
        datum = 41;
      }
      if (oc === "oc18") {
        tex = 22;
        datum = 42;
      }
      if (oc === "oc19") {
        tex = 23;
        datum = 43;
      }

      if (oc === "oc20") {
        tex = 24;
        datum = 44;
      }

      var dateObj = new Date(obj[datum]);
      var newDate = "";
      if (dateObj != "Invalid Date")
        newDate =
          dateObj.getDay() +
          "/" +
          dateObj.getMonth() +
          "/" +
          dateObj.getFullYear();

      if (tex != undefined && datum != undefined) {
        if (obj[tex] != undefined) {
          coment = obj[tex] + " Datum: " + newDate;

          $("#idpsee").text(coment);
          $("#modalsee").modal("toggle");
        }
      }
    }
  );
}

function GetDocuments() {
  $.get(
    "ajax/get_documents_by_studentid.php?id=" + studentid,
    {},
    function (data) {
      $("#dataRequest-documents_detail").append(data);
    }
  );
}
function GetHouding() {
  $.get(
    "ajax/getleerlingdetail_houding_tabel_mobile_by_year.php?id=" +
      studentid +
      "&schoolJaar=" +
      selected_schooljaar,
    {},
    function (data) {
      $("#dataRequest-houding_detail").empty();
      $("#dataRequest-houding_detail").html(data);
    }
  );
}
function GetVerzuim() {
  $.get(
    "ajax/getleerlingdetail_verzuim_tabel_mobile_by_year_parent.php?id=" +
      studentid +
      "&schoolJaar=" +
      selected_schooljaar,
    {},
    function (data) {
      $("#dataRequest-verzuim_detail").empty();
      $("#dataRequest-verzuim_detail").html(data);
    }
  );
}
function GetAvi() {
  $.get(
    "ajax/getleerlingdetail_avi_tabel_mobile_by_year.php?id=" +
      studentid +
      "&schoolJaar=" +
      selected_schooljaar,
    {},
    function (data) {
      $("#dataRequest-avi_detail").empty();
      $("#dataRequest-avi_detail").html(data);
    }
  );
}

function GetContacts() {
  // Contact
  $.get(
    "ajax/getleerlingdetail_contact_tabel_mobile.php?id=" +
      studentid +
      "&id_family=" +
      familyid,
    {},
    function (data) {
      $("#dataRequest-contact_detail").html(data);
    }
  );
}
function openTab(evt, Tab) {
  if (Tab === "Documents") {
    GetDocuments();
  }

  if (Tab === "Contact") {
    GetContacts();
  }

  if (Tab === "Verzuim") {
    GetVerzuim();
  }

  if (Tab === "Houding") {
    GetHouding();
  }

  if (Tab === "Avi") {
    GetAvi();
  }
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(Tab).style.display = "block";
  evt.currentTarget.className += " active";
}

$("#_2017_2018").on("click", function () {
  if ($("#2017_2018").prop("checked", true)) {
    selected_schooljaar = "2017_2018";
    get_info_by_schooljaar("2017-2018");
    $("#_2017_2018").val(1);
    $("#_2019_2020").val(0);
    $("#_2018_2019").val(0);
    $("#_2016_2017").val(0);
    $("#_2015_2016").val(0);
    $("#_2020_2021").val(0);
    $("#_2021_2022").val(0);
    $("#_2022_2023").val(0);
    $("#_2023_2024").val(0);
  }
});
$("#_2016_2017").on("click", function () {
  if ($("#2016_2017").prop("checked", true)) {
    selected_schooljaar = "2016-2017";
    get_info_by_schooljaar("2016-2017");
    $("#_2017_2018").val(0);
    $("#_2016_2017").val(1);
    $("#_2015_2016").val(0);
    $("#_2018_2019").val(0);
    $("#_2019_2020").val(0);
    $("#_2020_2021").val(0);
    $("#_2021_2022").val(0);
    $("#_2022_2023").val(0);
    $("#_2023_2024").val(0);
  }
});
$("#_2018_2019").on("click", function () {
  if ($("#2018_2019").prop("checked", true)) {
    selected_schooljaar = "2018-2019";
    get_info_by_schooljaar("2018-2019");
    $("#_2017_2018").val(0);
    $("#_2016_2017").val(0);
    $("#_2015_2016").val(0);
    $("#_2019_2020").val(0);
    $("#_2018_2019").val(1);
    $("#_2020_2021").val(0);
    $("#_2021_2022").val(0);
    $("#_2022_2023").val(0);
    $("#_2023_2024").val(0);
  }
});
$("#_2019_2020").on("click", function () {
  if ($("#2019_2020").prop("checked", true)) {
    selected_schooljaar = "2019-2020";
    get_info_by_schooljaar("2019-2020");
    $("#_2017_2018").val(0);
    $("#_2016_2017").val(0);
    $("#_2015_2016").val(0);
    $("#_2019_2020").val(1);
    $("#_2018_2019").val(0);
    $("#_2020_2021").val(0);
    $("#_2021_2022").val(0);
    $("#_2022_2023").val(0);
    $("#_2023_2024").val(0);
  }
});

$("#_2020_2021").on("click", function () {
  if ($("#2020_2021").prop("checked", true)) {
    selected_schooljaar = "2020-2021";
    get_info_by_schooljaar("2020-2021");
    $("#_2017_2018").val(0);
    $("#_2016_2017").val(0);
    $("#_2015_2016").val(0);
    $("#_2019_2020").val(0);
    $("#_2018_2019").val(0);
    $("#_2020_2021").val(1);
    $("#_2021_2022").val(0);
    $("#_2022_2023").val(0);
    $("#_2023_2024").val(0);
  }
});

$("#_2021_2022").on("click", function () {
  if ($("#2021_2022").prop("checked", true)) {
    selected_schooljaar = "2021-2022";
    get_info_by_schooljaar("2021-2022");
    $("#_2017_2018").val(0);
    $("#_2016_2017").val(0);
    $("#_2015_2016").val(0);
    $("#_2019_2020").val(0);
    $("#_2018_2019").val(0);
    $("#_2020_2021").val(0);
    $("#_2021_2022").val(1);
    $("#_2022_2023").val(0);
    $("#_2023_2024").val(0);
  }
});

$("#_2022_2023").on("click", function () {
  if ($("#2022_2023").prop("checked", true)) {
    selected_schooljaar = "2022-2023";
    get_info_by_schooljaar("2022-2023");
    $("#_2017_2018").val(0);
    $("#_2016_2017").val(0);
    $("#_2015_2016").val(0);
    $("#_2019_2020").val(0);
    $("#_2018_2019").val(0);
    $("#_2020_2021").val(0);
    $("#_2021_2022").val(0);
    $("#_2022_2023").val(1);
    $("#_2023_2024").val(0);
  }
});

$("#_2023_2024").on("click", function () {
  if ($("#2023_2024").prop("checked", true)) {
    selected_schooljaar = "2023-2024";
    get_info_by_schooljaar("2023-2024");
    $("#_2017_2018").val(0);
    $("#_2016_2017").val(0);
    $("#_2015_2016").val(0);
    $("#_2019_2020").val(0);
    $("#_2018_2019").val(0);
    $("#_2020_2021").val(0);
    $("#_2021_2022").val(0);
    $("#_2022_2023").val(0);
    $("#_2023_2024").val(1);
  }
});

$("input[type=radio]").on("change", function () {
  $("input[type=radio]").not(this).prop("checked", false);
});

function get_info_by_schooljaar(schooljaar) {
  // Cijfers

  $.get(
    "ajax/getleerlingdetail_cijfers_tabel_mobile_by_year.php?id=" +
      studentid +
      "&schoolJaar=" +
      schooljaar,
    {},
    function (data) {
      $("#dataRequest-cijfers_detail").empty();
      $("#dataRequest-cijfers_detail").html(data);
      $("#dataRequest-cijfers_detail").css("display", "block");
      if (anchoVentana < 601) {
        $(".mobile").css("display", "none");
        $("input:radio[name=rapport]").click(function (e) {
          if ($("input:radio[name=rapport]:checked").val() == "1") {
            $(".1").show();
            $(".2").css("display", "none");
            $(".3").css("display", "none");
            console.log("1");
          } else if ($("input:radio[name=rapport]:checked").val() == "2") {
            $(".1").css("display", "none");
            $(".2").show();
            $(".3").css("display", "none");
            console.log("2");
          } else if ($("input:radio[name=rapport]:checked").val() == "3") {
            $(".1").css("display", "none");
            $(".2").css("display", "none");
            $(".3").show();
            console.log("3");
          }
        });
        contador = 1;
        /* $("#tbl_cijfers_by_student tr").each(function () {
          if (contador > 6) {
            $(this).css("display", "none");
          }
          contador = contador + 1;
        });
        $("#mostrar_cijfer").click(function (e) {
          e.preventDefault();
          $("#tbl_cijfers_by_student tbody [style*='display: none']").show();
          $("#mostrar_cijfer").css("display", "none");
          $("#ocultar_cijfer").show();
        });
        $("#ocultar_cijfer").click(function (e) {
          e.preventDefault();
          contador = 1;
          $("#tbl_cijfers_by_student tbody tr").each(function () {
            if (contador > 6) {
              $(this).css("display", "none");
            }
            contador = contador + 1;
          });
          $("#mostrar_cijfer").show();
          $("#ocultar_cijfer").css("display", "none");
        }); */
      }
    }
  );
}

$("#btn_change_secure_pin").click(function (e) {
  e.preventDefault();
  if ($("#old_secure_pin").val().length > 0) {
    if ($("#new_secure_pin").val() === $("#confirm_new_secure_pin").val()) {
      $("#studentid").val(studentid);

      $.ajax({
        url: "ajax/change_secure_pin.php",
        data: $("#frm_change_secure_pin").serialize(),
        type: "POST",
        dataType: "text",
        success: function (text) {
          if (text != 0 && text != -2) {
            $("#message_modal").text("The SecurePIN was successfully changed");
            $("#message_modal").removeClass("old-secure-pin");
            $("#message_modal").addClass("success-secure-pin");
            $("#frm_change_secure_pin").addClass("hidden");

            setTimeout(function () {
              $("#close_modal_securepin").click();
            }, 3000);
          } else if (text == -2) {
            $("#message_modal").text("The old SecurePIN do no match...");
            $("#message_modal").addClass("old-secure-pin");
          }
        },
        error: function (xhr, status, errorThrown) {},
        complete: function (xhr, status) {},
      });
    } else {
      alert("the new pin and confirm do no matchn");
    }
  } else {
    alert("the old secure pin");
  }
});

$("#request_open_modal_secure_pin").click(function (e) {
  if ($("#have_email").val() == 1) {
    $("#btn_open_modal_secure_pin").click();
  } else {
    $("#btn_question_add_contact_modal").click();
  }
});

function goContactParent() {
  window.location.href =
    "contact_parent.php?id=" + studentid + "&id_family=" + familyid;
}
function open_document(id) {
  var request = new XMLHttpRequest();
  request.open("GET", "ajax/open_document.php?id_calendar=" + id, false);
  request.send();
  if (request.responseText != "0") {
    window.open(request.responseText);
  } else {
    $("#btn_no_documents_modal").click();
  }
}
