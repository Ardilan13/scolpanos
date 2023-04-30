<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>
<div class="push-content-220">
  <main id="main" role="main">
    <?php include 'header.php'; ?>
    <?php $UserRights= $_SESSION['UserRights'];
    if ($UserRights != "BEHEER" && $UserRights != "ADMINISTRATIE"){
      include 'redirect.php';} else{?>
      <?php
      require_once("classes/spn_leerling.php");
      $t = new spn_leerling();
      $school_id = $_SESSION["SchoolID"];

    $target=  $t->list_target_class($school_id, false);
    $from = $t->list_from_class($school_id, false);
    ?>
    <!-- <link rel="stylesheet" href="assets/css/pruebaa.css"> -->
    <section>
      <div class="container container-fs">
        <div class="row">
          <div class="default-secondary-bg-color col-md-12 inset brd-bottom">
            <h1 class="primary-color">Klas samenstellen</h1>
            <?php include 'breadcrumb.php'; ?>
          </div>
        </div>
        <div class="row mrg-bottom">
          <div class="col-md-6 full-inset pull-left" id="div_from_class2">
            <div class="primary-bg-color brd-full">
              <div class="box">
                <div class="box-title full-inset brd-bottom">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="col-md-12">
                        <h2 class="">From Class</h2>
                      </div>
                      <br>
                      <div class="col-md-2">
                        <ul class="col-md-1 list list-toggle">
                          <li>
                            <a href="#" id="select_all_leerling_from" onclick="select_all()">
                              <i class="fa fa-th-large"></i>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="form-group col-md-6">
                      <?php print $from; ?>
                    </div>
                  </div>
                </div>
                <div class="box-content full-inset default-secondary-bg-color hidden" id="from_message">
                  <div class="row">
                    <div class="col-md-7 col-md-offset-3">
                      <div  class="alert alert-danger">
                        <p><i class="fa fa-warning"></i>"Class From" can not be the same as "Target class"...</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-content full-inset default-secondary-bg-color" id="from_pics">
                  <div class="row" id="draggable">
                    <div class="dataRequest-student_pic" id="drag_from"></div><!--This section its for the profiles pictures.-->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 full-inset pull-left">
            <div class="primary-bg-color brd-full">
              <div class="box">
                <div class="box-title full-inset brd-bottom">
                  <div class="row">
                    <h2 class="col-md-6">Target Class</h2>
                    <div class="form-group col-md-6">
                      <?php print $target; ?>
                    </div>
                  </div>
                </div>
                <div class="box-content full-inset default-secondary-bg-color hidden" id="target_message">
                  <div class="row">
                    <div class="col-md-7 col-md-offset-3">
                      <div  class="alert alert-danger">
                        <p><i class="fa fa-warning"></i>"Class Target" can not be the same as "From class"...</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-content full-inset default-secondary-bg-color" id="target_pics" >
                  <div class="row" id="droppable">
                    <div class="dataRequest-student_pic_target" id="drop_target"></div><!--This section its for the profiles pictures.-->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</main>
<?php include 'footer.php'; ?>
<?php include 'document_end.php';}?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->
<script>
$('html, body').animate({scrollTop:0}, 'fast');
var selectedClass = 'tertiary-bg-color';
var unSelectedClass ='primary-bg-color';
var leerling_object=[];
var i_leerling_object=0;
var leerling_unclick=0;
var selectoneclass='Select One Class';
var control_select = 0;

function select_all() {

  if (control_select===0){
    leerling_object=[];

    var $drag_from = $('#drag_from');
    //Get the number of students who were listed
    i_leerling_object = parseInt($drag_from.get(0).childElementCount);
    //select the child element of the DOM
    var child = document.getElementById("drag_from").children;

    //filling simple array and full Array called leerling_object
    for(var z = 0; z < i_leerling_object; z++) {
      var numberchild =child[z].id;
      $('#'+ numberchild + ' > div').addClass(selectedClass);
      leerling_array=[numberchild];
      leerling_object[z]=leerling_array;
      //console.log(leerling_array);
      //console.log(leerling_object);
    }
    control_select=1;
  }
  else {
    control_select=0;
    leerling_object=[];
    var $drag_from = $('#drag_from');
    //Get the number of students who were listed
    i_leerling_object = parseInt($drag_from.get(0).childElementCount);
    //select the child element of the DOM
    var child = document.getElementById("drag_from").children;

    //Remove class selected and clean array
    for(var z = 0; z < i_leerling_object; z++) {
      var numberchild =child[z].id;
      $('#'+ numberchild + ' > div').removeClass(selectedClass);
      $('#'+ numberchild + ' > div').addClass(unSelectedClass);
      // console.log(leerling_array);
      // console.log(leerling_object);
    }

  }
}
function leerlingSelected(x) {
  var studentid = x.attributes.id.nodeValue;
  //var nam = x.firstChild.attributes.name.nodeValue
  //  $('.' + studentid).toggleClass(unSelectedClass);
  $('.' + studentid).toggleClass(selectedClass);


  var leerling_click = parseInt(studentid);
  var leerling_array = [leerling_click];
  // console.log(leerling_array);
  // console.log(leerling_object);

  if ( $('.' + studentid).hasClass(selectedClass)){
    leerling_object[i_leerling_object]=leerling_array;
   //console.log(leerling_object)

    var newArr = [];
    // remove 'falsey' items by creating new array of true-y stuff
    for (var index in leerling_object) {
      if( leerling_object[index] ) {
        newArr.push( leerling_object[index] );
      }
    }
    //replace old arr
    leerling_object = newArr;
   //console.log('that its de primary array after clean')
   //console.log(leerling_object)
    i_leerling_object=leerling_object.length;


  } else {
    var c=[];
    for(var z = 0; z < leerling_object.length; z++) {
      c=[leerling_object[z]]  ;
      if(c[0]  == leerling_click) {

        leerling_unclick=z;
        leerling_object.splice(leerling_unclick, 1);
        //console.log('despues de quitarle una posicion con el clic');
        //console.log(leerling_object);

      }
    }
  }
}

function allowDrop(ev) {
  ev.preventDefault();
}
function drag(ev){
  ev.dataTransfer.setData("text", ev.target.id);
  ev.dataTransfer.setData("start_drag", ev.target.parentNode.attributes.id.nodeValue);
}

function drop(ev) {
  ev.preventDefault();
  var end_drop = ev.currentTarget.firstElementChild.id;


  var start_drag = ev.dataTransfer.getData("start_drag");
  var data = ev.dataTransfer.getData("text");

  if (start_drag!=end_drop){
    // alert('si el origien y el destino es es distinto');


    //in this "if" checks where the drag began, if I began to "from_class" or from "target_class"
    if (start_drag === "drop_target"){
      var $from_class = $("#class_target_list option:selected").val();
      var $to_class = $("#list_from_class option:selected").val();
      var $comments = '';
      // console.log(leerling_object);

    }
    else {
      var $from_class = $("#list_from_class option:selected").val();
      var $to_class = $("#class_target_list option:selected").val();
      var $comments = '';
    }


    if (leerling_object.length==0){
      var $studentid   = data;

      $.ajax({
        url:"ajax/update_lerrling_class.php",
        data: "id_student="+$studentid+"&from_class="+$from_class+"&to_class="+$to_class+"&comments="+$comments,
        type  : 'POST',
        dataType: "HTML",
        cache: false,
        async :true,
        success: function(data){
          var $cto=$("#class_target_list option:selected").val();
          var $cfrom=$("#list_from_class option:selected").val();

          $("#drop_target").html(function(){
            $.post("ajax/getleerling_pics.php", {class:$cto}, function(data) {
              $(".dataRequest-student_pic_target").html(data);
            });
          })

          $("#drag_from").html(function(){
            $.post("ajax/getleerling_pics.php", {class:$cfrom}, function(data) {
              $(".dataRequest-student_pic").html(data);
            });
          })
          // console.log('limpie todo');
        }
      })
      leerling_object=[];
    }
    else {

      for (i = 0; i < leerling_object.length ; i++) {
        var $studentid   = leerling_object[i][0];

        $.ajax({
          url:"ajax/update_lerrling_class.php",
          data: "id_student="+$studentid+"&from_class="+$from_class+"&to_class="+$to_class+"&comments="+$comments,
          type  : 'POST',
          dataType: "HTML",
          cache: false,
          async :true,
          success: function(data){
            var $cto=$("#class_target_list option:selected").val();
            var $cfrom=$("#list_from_class option:selected").val();

            $("#drop_target").html(function(){
              $.post("ajax/getleerling_pics.php", {class:$cto}, function(data) {
                $(".dataRequest-student_pic_target").html(data);
              });
            })

            $("#drag_from").html(function(){
              $.post("ajax/getleerling_pics.php", {class:$cfrom}, function(data) {
                $(".dataRequest-student_pic").html(data);
              });
            })
            // console.log(leerling_object);
          }
        });
      }
      // console.log('voy a limpiar el objeto');
      leerling_object=[];
      // console.log(leerling_object);
    }
  }
}

$(function(){
  $("#list_from_class").change(function () {

    var class_from=$("#list_from_class option:selected").val();
    var class_target = $("#class_target_list option:selected").val();

    if ( class_from===class_target){
      //  alert('Alert:: "Class From" can not be the same as "Target class"...');
      $('#drag_from').empty();
      $("#from_pics").addClass('hidden');
      $('#from_message').removeClass('hidden');
    }

    else {
      if (class_from===selectoneclass){

        $('#draggable').removeAttr("ondrop");
        $('#draggable').removeAttr("ondragover");
      }
      else {
        $('#draggable').attr('ondrop', 'drop(event)');
        $('#draggable').attr('ondragover', 'allowDrop(event)');
      }
      //alert(varClassfrom);
      $("#from_pics").removeClass('hidden');
      $('#from_message').addClass('hidden');

      $('#drag_from').css({"overflow-y": "auto", "height": "450px"});


      $.post("ajax/getleerling_pics.php", {class: class_from}, function(data) {
        $(".dataRequest-student_pic").html(data);
      });
    }
  })
})
$(function(){
  $("#class_target_list").change(function () {

    var class_from=$("#list_from_class option:selected").val();
    var class_target = $("#class_target_list option:selected").val();

    if ( class_from===class_target){
      //alert('Alert:: "Class From" can not be the same as "Target class" ... ');
      $('#drop_target').empty();
      $("#target_pics").addClass('hidden');
      $('#target_message').removeClass('hidden');
    }

    else {
      if (class_target===selectoneclass){

        $('#droppable').removeAttr("ondrop");
        $('#droppable').removeAttr("ondragover");
      }
      else {
        $('#droppable').attr('ondrop', 'drop(event)');
        $('#droppable').attr('ondragover', 'allowDrop(event)');
      }
      $("#target_pics").removeClass('hidden');
      $('#target_message').addClass('hidden');
      $('#drop_target').css({"overflow-y": "auto", "height": "450px"});

      $.post("ajax/getleerling_pics.php", {class:class_target}, function(data) {
        $(".dataRequest-student_pic_target").html(data);
      });
    }
  })
})
</script>
