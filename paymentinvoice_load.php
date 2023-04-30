<?php include 'document_start.php'; ?>



	<?php include 'sub_nav.php'; ?>



	<div class="push-content-220">

		<main id="main" role="main">

			<?php include 'header.php'; ?>

			<section>

				<div class="container container-fs">

					<div class="row">

						<div class="default-secondary-bg-color col-md-12 inset brd-bottom">

							<h1 class="primary-color">Financial Module</h1>

							<?php include 'breadcrumb.php'; ?>

						</div>

					</div>

					<div class="row">

						<div class="col-md-2 full-inset"></div>

						<div class="col-md-8 full-inset">

							<div class="primary-bg-color brd-full">

								<div class="box">

									<div class="box-title full-inset brd-bottom">

										<h3>New invoice or payment</h3>

									</div>

									<div class="sixth-bg-color box content full-inset">

										<form class="form-horizontal align-left" role="form" name="form-add-invoicepayment" id="form-addInvoicepayment">

											<div role="tabpanel" class="tab-pane active" id="tab1">

												<div class="alert alert-danger hidden">

													<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>

												</div>

												<fieldset>

													<div class="form-group">

														<label class="col-md-4 control-label" for="">Type</label>

														<div class="col-md-8">

															<select id="invoicepaymenttype" name="invoicepaymenttype" class="form-control">

																<option selected value="Invoice">Invoice</option>

																<option value="Payment">Payment</option>

															</select>

														</div>

													</div>

													<!-- School -->

													<div id="lblschool" class="form-group">

														<label class="col-md-4 control-label" for="">School</label>

														<div class="dataSchoolOnLoad" data-ajax-href="ajax/getlistschool.php" data-display="data-school-display"></div>

														<div class="table-responsive">

															<div id="data_school" class="data-school-display col-md-12"></div>

														</div>

													</div>

													<!-- Class -->

													<div id="lblClass" class="form-group">

														<label class="col-md-4 control-label" for="">Class</label>

														<div class="dataClassOnLoad" data-ajax-href="ajax/getlistclass.php" data-display="data-class-display"></div>

														<div class="table-responsive">

															<div id="data_class"class="data-class-display col-md-12"></div>

														</div>

													</div>

													<!-- Student  -->

													<div id="student" class="form-group">

														<label class="col-md-4 control-label" for="">Student</label>

														<div class="dataStudentOnLoad" data-ajax-href="ajax/getliststudent.php" data-display="data-student-display"></div>

														<div class="table-responsive">

															<div id="data_student" class="data-student-display col-md-12"></div>

														</div>

													</div>

													<!-- Invoice -->

													<div id="invoice" class="form-group">

														<label class="col-md-4 control-label" for="">Invoice #</label>

														<div class="dataStudentInvoiceOnLoad" data-ajax-href="ajax/getlistpaymentstudent.php" data-display="data-student-invoice-display"></div>

															<div class="table-responsive">

																<div id="data_student_invoice"class="data-student-invoice-display col-md-12"></div>

															</div>

													</div>

													<div class="form-group">

														<label class="col-md-4 control-label">Number #</label>

														<div class="col-md-8">

															<input id="invoicepaymentnumber" class="form-control" type="text" name="invoicepaymentnumber"/>

														</div>

													</div>

													<!-- Ammount -->

													<div class="form-group">

														<label class="col-md-4 control-label">Ammount</label>

														<div class="col-md-8">

															<input id="invoicepaymentammount" class="form-control" type="text" name="invoicepaymentammount"/>

														</div>

													</div>

													<!-- Date -->

													<!-- datepicker -->

													<div class="form-group">

														<label class="col-md-4 control-label">Date</label>

															<div class="col-md-8">

																<div class="input-group date">

																		<input id="invoicepaymentdate" type="text" value="" placeholder="" id="invoicepaymentdate" class="form-control calendar" name="invoicepaymentdate">

																		<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>

																</div>

															</div>

													</div>

													<!-- Due date -->

													<!-- datepicker -->

													<div id="lbldudate" class="form-group">

														<label class="col-md-4 control-label">Due date</label>

															<div class="col-md-8">

																<div class="input-group date">

																		<input id="invoicepaymentduedate" type="text" value="" placeholder="" id="invoicepaymentduedate" class="form-control calendar" name="invoicepaymentduedate">

																		<span class="input-group-addon"><i class="fa fa-calendar default-primary-color"></i></span>

																</div>

															</div>

													</div>

													<!-- Memo -->

													<div class="form-group">

														<label class="col-md-4 control-label">Memo</label>

														<div class="col-md-8">

																<input id="invoicepaymentmemo" class="form-control" type="text" name="invoicepaymentmemo" />

														</div>

													</div>

													<!-- Status -->

													<div class="form-group">

														<label class="col-md-4 control-label">Status</label>

														<div class="col-md-8">

															<select id="invoicepaymentstatus" name="invoicepaymentstatus" class="form-control">

																<option value="For pay">For pay</option>

																<option value="Pay">Pay</option>

															</select>

														</div>

													</div>

													<div class="form-group full-inset">

														<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-invoicepayments">Include</button>

														<button type="button" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-clear">Clear</button>

													</div>

												</fieldset>

											</div>

										</form>

									</div>

								</div>

							</div>

						</div>

						<div class="col-md-2 full-inset"></div>

					</div>

				</div>

			</section>

		</main>

		<?php include 'footer.php'; ?>

	</div>



	<?php include 'document_end.php'; ?>





	<script type="text/javascript">









		// Funcion para ocultar los controles

		$(function()

		{

            //function clean all input of form control

			$("#btn-clear").click(function(){



				$("#invoicepaymentnumber,#invoicepaymentammount").val("");

				$("#invoicepaymentdate,#invoicepaymentduedate").val("");

				$("#invoicepaymentnumber,#invoicepaymentammount").val("");

				$("#invoicepaymentmemo").val("");



                $("#invoicepaymentschool,#invoicepaymenttype,#invoicepaymentinvoiceid").prop('selectedIndex',0);

                $("#invoicepaymentclass,#invoicepaymentstudent,#invoicepaymentstatus").prop('selectedIndex',0);





			});



			$("#invoice").hide();



			$("#invoicepaymenttype").change(

				function ()

				{

					var valInvoice = $("#invoicepaymenttype option:selected").val();



					if(valInvoice == 'Payment')

					{

						$("#invoice_id,#invoice").show("slow");

						/*$("#lblschool").hide("slow");

						$("#lblClass").hide("slow");

						$("#lbldudate").hide("slow");*/

						//$("#invoicepaymentstudent option[value='0']").remove();

						// TODO: Ejecutar la busqueda de facturas por estudiante



                        $.post("ajax/getlistschool.php",

                            {

                                //idSchool : $("#invoicepaymentschool option:selected").val()

                            },

                            function(data){

                                $("#data_school").html(data);



                                /*class change*/



                                $.post("ajax/getlistclass.php",

                                    {

                                        idSchool : $("#invoicepaymentschool option:selected").val()

                                    },

                                    function(data1){

                                        $("#data_class").html(data1);

                                        /*Student change*/

                                        $.post("ajax/getliststudent.php",

                                            {

                                                idSchool :  $("#invoicepaymentschool option:selected").val(),

                                                idClass : $("#invoicepaymentclass option:selected").val()

                                            },

                                            function(data2){

                                                $("#data_student").html(data2);





                                                varCboType = $("#invoicepaymenttype option:selected").text();





                                                if(varCboType == "Payment"){



                                                    $.post("ajax/getlistpaymentstudent.php",

                                                        {

                                                            idStudent : $("#invoicepaymentstudent option:selected").val()

                                                        },

                                                        function(data1){

                                                            $("#data_student_invoice").html(data1);

                                                        });



                                                }











                                            });

                                    });



                            });







                    }

					else

					{

						$("#invoice_id,#invoice").hide("slow");

						/*$("#lblschool").show("slow");

						$("#lblClass").show("slow");

						$("#lbldudate").show("slow");

						$("#invoicepaymentstudent").append('<option selected value="0">None</option>');*/



                        $.post("ajax/getlistschool.php",

                            {

                               // idSchool : $("#invoicepaymentschool option:selected").val()

                            },

                            function(data){

                                $("#data_school").html(data);



                                /*class change*/



                                $.post("ajax/getlistclass.php",

                                    {

                                        idSchool : $("#invoicepaymentschool option:selected").val()

                                    },

                                    function(data1){

                                        $("#data_class").html(data1);

                                        /*Student change*/

                                        $.post("ajax/getliststudent.php",

                                            {

                                                idSchool :  $("#invoicepaymentschool option:selected").val(),

                                                idClass : $("#invoicepaymentclass option:selected").val()

                                            },

                                            function(data2){

                                                $("#data_student").html(data2);

                                            });

                                    });



                            });















					}

				}

			);











		}

	);

	</script>



	<!--  Script para cargar las escuelas -->

	<script type="text/javascript">

		// Funcion para cargar las escuelas

		$(function()

		{

			var $subNavigation	= $('#sub-nav'),

					hrefLink        = $('.dataSchoolOnLoad').attr('data-ajax-href'),

					replaceDiv      = $('.dataSchoolOnLoad').attr('data-display');



    	$subNavigation.css

			(

				{

	    		'min-height' : $('#tabpanel').innerHeight()

	    	}

			);



        if($('.dataSchoolOnLoad').length)

				{

            $.get(hrefLink,

							function( data )

							{

                $('.' + replaceDiv + '').html( data );

							}

						)

						.done(

							function()

							{

                if($('#school_id').length)

								{

									// Validar porque no funciona

                   //initDropdown();

                }

							}

							)

							.fail(function()

							{

                alert( "error" );

							}

						);

        }

			}

		);

		//});

	</script>



	<!--  Script para cargar las clases -->

	<script type="text/javascript">

		// Funcion para cargar las clases

		$(function()

		{

			var $subNavigation	= $('#sub-nav'),

					hrefLink        = $('.dataClassOnLoad').attr('data-ajax-href'),

					replaceDiv      = $('.dataClassOnLoad').attr('data-display');



    	$subNavigation.css

			(

				{

	    		'min-height' : $('#tabpanel').innerHeight()

	    	}

			);



        if($('.dataClassOnLoad').length)

				{

            $.get(hrefLink,

							function( data )

							{

                $('.' + replaceDiv + '').html( data );

							}

						)

						.done(

							function()

							{

                if($('#class').length)

								{

									// Validar porque no funciona

                 //  initDropdown();

                }

							}

							)

							.fail(function()

							{

                alert( "error" );

							}

						);

        }

			}

		);

		//});

	</script>



	<!--  Script para cargar los estudiantes -->

	<script type="text/javascript">

		// Funcion para cargar los estudiantes

		$(function()

		{

			var $subNavigation	= $('#sub-nav'),

					hrefLink        = $('.dataStudentOnLoad').attr('data-ajax-href'),

					replaceDiv      = $('.dataStudentOnLoad').attr('data-display');



    	$subNavigation.css

			(

				{

	    		'min-height' : $('#tabpanel').innerHeight()

	    	}

			);



        if($('.dataStudentOnLoad').length)

				{

            $.get(hrefLink,

							function( data )

							{

                $('.' + replaceDiv + '').html( data );

							}

						)

						.done(

							function()

							{

                if($('#student').length)

								{

									// Validar porque no funciona

                 //  initDropdown();

                }

							}

							)

							.fail(function()

							{

                alert( "error" );

							}

						);

        }

			}

		);

		//});

	</script>



	<!--  Script para cargar las facturas de los estudiantes -->

	<script type="text/javascript">

		// Funcion para cargar las facturas de los estudiantes

		$(function()

		{

			var $subNavigation	= $('#sub-nav'),

					hrefLink        = $('.dataStudentInvoiceOnLoad').attr('data-ajax-href'),

					replaceDiv      = $('.dataStudentInvoiceOnLoad').attr('data-display');



    	$subNavigation.css

			(

				{

	    		'min-height' : $('#tabpanel').innerHeight()

	    	}

			);



        if($('.dataStudentInvoiceOnLoad').length)

				{

            $.get(hrefLink,

							function( data )

							{

                $('.' + replaceDiv + '').html( data );

							}

						)

						.done(

							function()

							{

                if($('#invoice').length)

								{

									// Validar porque no funciona

                  // initDropdown();

                }

							}

							)

							.fail(function()

							{

                alert( "error" );

							}

						);

        }

			}

		);

		//});

	</script>



	<!--  Script para almacenar una factura o un pago -->

	<script type="text/javascript">





    $('#form-addInvoicepayment').on("submit",function(e)



    {

			// Funcion para cargar los estudiantes

      /* prevent refresh */

      e.preventDefault();



      if ($("#invoicepaymenttype").val() === "Invoice")

      {

        // Invoice

				if ($("#invoicepaymentschool option:selected").val() != "0"  || $("#invoicepaymentclass option:selected").val() != "0" || $("#invoicepaymentstudent option:selected").val() != "0")

				{

	        if($("#invoicepaymentnumber").val().length === 0 || $("#invoicepaymentammount").val().length === 0 || $("#invoicepaymentdate").val().length === 0 || $("#invoicepaymentduedate").val().length === 0 || $("#invoicepaymentmemo").val().length === 0)

					{

	          alert("Sorry , complete missing data");

	        }

	        else

	        {

	            /* prevent refresh */

	            e.preventDefault();



	            /* begin post */

	            $.ajax(

	              {

	                url: "ajax/addinvoice.php",

	                data: $("#form-addInvoicepayment").serialize(),

	                type: "POST",

	                dataType: "text",

	                success: function(text)

	                {

	                    console.log(text);

	                    if(text != "1")

	                    {

	                        alert("DEBUG :: Could not add invoice");

	                    }

	                    else if(text == "1")

	                    {

	                        alert("DEBUG :: invoice Added Successfully");

													location.reload();

	                    }

	                },

	                error: function(xhr, status, errorThrown)

	                {

	                    console.log("error");

	                },

	                complete: function(xhr,status)

	                {

	                    //console.log("complete");

	                }

	              }

	            );

	        }

				}

				else {

					alert("Sorry , you must select at least one value");

				}

			}

      else

      {

        // Payment

				if ($("#invoicepaymentinvoiceid").val() != "0" &&  $("#invoicepaymentinvoiceid").val() != "0")

				{

	        if($("#invoicepaymentnumber").val().length === 0 || $("#invoicepaymentammount").val().length === 0 || $("#invoicepaymentdate").val().length === 0  || $("#invoicepaymentmemo").val().length === 0)

					{

	          alert("Sorry , complete missing data");

	        }

	        else

	        {

	            /* prevent refresh */

	            e.preventDefault();



	            /* begin post */

	            $.ajax(

	              {

	                url: "ajax/addpayment.php",

	                data: $("#form-addInvoicepayment").serialize(),

	                type: "POST",

	                dataType: "text",

	                success: function(text)

	                {

	                    console.log(text);

	                    if(text != "1")

	                    {

	                        alert("DEBUG :: Could not add payment");
	                        console.log("results: " + text);

	                    }

	                    else if(text == "1")

	                    {

	                        alert("DEBUG :: Payment Added Successfully");

													location.reload();

	                    }

	                },

	                error: function(xhr, status, errorThrown)

	                {

	                    console.log("error");

	                },

	                complete: function(xhr,status)

	                {

	                    //console.log("complete");

	                }

	              }

	            );

	        }

				}

				else {

					alert("Sorry , you must select at least one value");

				}



      }

    }

    );



	</script>

