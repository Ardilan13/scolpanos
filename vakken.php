<?php include 'document_start.php'; ?>

<?php include 'sub_nav.php'; ?>

<div class="push-content-220">
	<main id="main" role="main">
		<?php include 'header.php'; ?>
		<?php $UserRights= $_SESSION['UserRights'];
		if ($UserRights != "BEHEER"){
			include 'redirect.php';} else{?>
		<section>
			<div class="container container-fs">
				<div class="row">
					<div class="default-secondary-bg-color col-md-12 inset brd-bottom">
						<h1 class="primary-color">Le Vakken</h1>
						<?php include 'breadcrumb.php'; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8 full-inset">
						<div class="primary-bg-color brd-full">
							<div class="box">
								<div class="box-title full-inset brd-bottom">
									<div class="row">
										<h2 class="col-md-8">Vakken</h2>
									</div>
								</div>
								<div class="box-content full-inset sixth-bg-color">
									<div id="tbl_le_vakken" class="dataRetrieverOnLoad" data-ajax-href="ajax/get_le_vakken_tabel.php"></div>
									<div class="table-responsive data-table" data-table-type="on" data-table-search="true" data-table-pagination="true">
										<div id="tbl_list_le_vakken" name ="tbl_list_le_vakken"></div>
									</div>
									<input type="hidden" name="selected_id_user_row" id="selected_id_user_row" value="" />
									<input type="hidden" name="selected_color_row" id="selected_color_row" value="" />
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-4 full-inset">
						<div class="primary-bg-color brd-full">
							<div class="box">
								<div class="box-title full-inset brd-bottom">
									<h3>New Vakken</h3>
								</div>
								<div class="sixth-bg-color box content full-inset">
									<form class="form-horizontal align-left" role="form" name="frm_le_vakken" id="frm_le_vakken">
										<div class="alert alert-danger hidden">
											<p><i class="fa fa-warning"></i> Er zijn lege velden die ingevuld moet worden!</p>
										</div>
										<div class="alert alert-info hidden">
											<p><i class="fa fa-check"></i> The Class User has been register!</p>
										</div>
										<div class="alert alert-info hidden" id="updated_suscessfully">
											<p><i class="fa fa-check"></i> The Class has been Updated</p>
										</div>
										<div class="alert alert-info hidden" id="created_suscessfully">
											<p><i class="fa fa-check"></i> The Class Has Been Created</p>
										</div>
										<div class="alert alert-info hidden" id="deleted_suscessfully">
											<p><i class="fa fa-check"></i> The Class Has Been Deleted</p>
										</div>
										<fieldset>
											<div class="form-group">
												<label class="col-md-4 control-label">School</label>
												<div class="col-md-8">
													<select  id="le_vakken_schools" name="le_vakken_schools" class="form-control" ></select>
												</div>
												<!-- <input id="user_class" name="user_class" type="text" value="" hidden> -->
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label">Class</label>
												<div class="col-md-8">
													<select id="le_vakken_class" name="le_vakken_class" class="form-control" >
														<option selected value="-1">Select One Class</option>
													</select>
												</div>
												<!-- <input id="user_class" name="user_class" type="text" value="" hidden> -->
											</div>

											<div class="form-group">
												<input id="volledigenaamvak_id" type="hidden" name="volledigenaamvak_id" />
												<label class="col-md-4 control-label">Volledigenaamvak</label>
												<div class="col-md-8">
													<input id="volledigenaamvak_name" class="form-control" name="volledigenaamvak_name" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label">Volgorde</label>
												<div class="col-md-8">
													<select id="le_vakken_volgorde" name="le_vakken_volgorde" class="form-control">
														<option value =-1> Select one Volgorde</option>
														<option value =1>1</option>
														<option value =2>2</option>
														<option value =3>3</option>
														<option value =4>4</option>
														<option value =5>5</option>
														<option value =6>6</option>
														<option value =7>7</option>
														<option value =8>8</option>
														<option value =9>9</option>
														<option value =10>10</option>
														<option value =11>11</option>
														<option value =12>12</option>
														<option value =13>13</option>
														<option value =14>14</option>
														<option value =15>15</option>
														<option value =16>16</option>
														<option value =17>17</option>
														<option value =18>18</option>
														<option value =19>19</option>
														<option value =20>20</option>
														<option value =21>21</option>
														<option value =22>22</option>
														<option value =23>23</option>
														<option value =24>24</option>
														<option value =25>25</option>
														<option value =26>26</option>
														<option value =27>27</option>
														<option value =28>28</option>
														<option value =29>29</option>
														<option value =30>30</option>
														<option value =31>31</option>
														<option value =32>32</option>
														<option value =33>33</option>
														<option value =34>34</option>
														<option value =35>35</option>
														<option value =36>36</option>
														<option value =37>37</option>
														<option value =38>38</option>
														<option value =39>39</option>
														<option value =40>40</option>
														<option value =41>41</option>
														<option value =42>42</option>
														<option value =43>43</option>
														<option value =44>44</option>
														<option value =45>45</option>
														<option value =46>46</option>
														<option value =47>47</option>
														<option value =48>48</option>
														<option value =49>49</option>
														<option value =50>50</option>
														<option value =51>51</option>
														<option value =52>52</option>
														<option value =53>53</option>
														<option value =54>54</option>
														<option value =55>55</option>
														<option value =56>56</option>
														<option value =57>57</option>
														<option value =58>58</option>
														<option value =59>59</option>
														<option value =60>60</option>
														<option value =61>61</option>
														<option value =62>62</option>
														<option value =63>63</option>
														<option value =64>64</option>
														<option value =65>65</option>
														<option value =66>66</option>
														<option value =67>67</option>
														<option value =68>68</option>
														<option value =69>69</option>
														<option value =70>70</option>
														<option value =71>71</option>
														<option value =72>72</option>
														<option value =73>73</option>
														<option value =74>74</option>
														<option value =75>75</option>
														<option value =76>76</option>
														<option value =77>77</option>
														<option value =78>78</option>
														<option value =79>79</option>
														<option value =80>80</option>
														<option value =81>81</option>
														<option value =82>82</option>
														<option value =83>83</option>
														<option value =84>84</option>
														<option value =85>85</option>
														<option value =86>86</option>
														<option value =87>87</option>
														<option value =88>88</option>
														<option value =89>89</option>
														<option value =90>90</option>
														<option value =91>91</option>
														<option value =92>92</option>
														<option value =93>93</option>
														<option value =94>94</option>
														<option value =95>95</option>
														<option value =96>96</option>
														<option value =97>97</option>
														<option value =98>98</option>
														<option value =99>99</option>
														<option value =100>100</option>
													</select>
												</div>
												<!-- <input id="user_class" name="user_class" type="text" value="" hidden> -->
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label">Index</label>
												<div class="col-md-8">
													<select id="le_vakken_xindex" name="le_vakken_xindex" class="form-control">
														<option value =-1> Select one Index</option>
														<option value =0>0</option>
														<option value =1>1</option>
														<option value =2>2</option>
														<option value =3>3</option>
														<option value =4>4</option>
														<option value =5>5</option>
														<option value =6>6</option>
														<option value =7>7</option>
														<option value =8>8</option>
														<option value =9>9</option>
														<option value =10>10</option>
														<option value =11>11</option>
														<option value =12>12</option>
														<option value =13>13</option>
														<option value =14>14</option>
														<option value =15>15</option>
														<option value =16>16</option>
														<option value =17>17</option>
														<option value =18>18</option>
														<option value =19>19</option>
														<option value =20>20</option>
														<option value =21>21</option>
														<option value =22>22</option>
														<option value =23>23</option>
														<option value =24>24</option>
														<option value =25>25</option>
														<option value =26>26</option>
														<option value =27>27</option>
														<option value =28>28</option>
														<option value =29>29</option>
														<option value =30>30</option>
														<option value =31>31</option>
														<option value =32>32</option>
														<option value =33>33</option>
														<option value =34>34</option>
														<option value =35>35</option>
														<option value =36>36</option>
														<option value =37>37</option>
														<option value =38>38</option>
														<option value =39>39</option>
														<option value =40>40</option>
														<option value =41>41</option>
														<option value =42>42</option>
														<option value =43>43</option>
														<option value =44>44</option>
														<option value =45>45</option>
														<option value =46>46</option>
														<option value =47>47</option>
														<option value =48>48</option>
														<option value =49>49</option>
														<option value =50>50</option>
														<option value =51>51</option>
														<option value =52>52</option>
														<option value =53>53</option>
														<option value =54>54</option>
														<option value =55>55</option>
														<option value =56>56</option>
														<option value =57>57</option>
														<option value =58>58</option>
														<option value =59>59</option>
														<option value =60>60</option>
														<option value =61>61</option>
														<option value =62>62</option>
														<option value =63>63</option>
														<option value =64>64</option>
														<option value =65>65</option>
														<option value =66>66</option>
														<option value =67>67</option>
														<option value =68>68</option>
														<option value =69>69</option>
														<option value =70>70</option>
														<option value =71>71</option>
														<option value =72>72</option>
														<option value =73>73</option>
														<option value =74>74</option>
														<option value =75>75</option>
														<option value =76>76</option>
														<option value =77>77</option>
														<option value =78>78</option>
														<option value =79>79</option>
														<option value =80>80</option>
														<option value =81>81</option>
														<option value =82>82</option>
														<option value =83>83</option>
														<option value =84>84</option>
														<option value =85>85</option>
														<option value =86>86</option>
														<option value =87>87</option>
														<option value =88>88</option>
														<option value =89>89</option>
														<option value =90>90</option>
														<option value =91>91</option>
														<option value =92>92</option>
														<option value =93>93</option>
														<option value =94>94</option>
														<option value =95>95</option>
														<option value =96>96</option>
														<option value =97>97</option>
														<option value =98>98</option>
														<option value =99>99</option>
														<option value =100>100</option>
													</select>
												</div>
												<!-- <input id="user_class" name="user_class" type="text" value="" hidden> -->
											</div>
											<!-- Buttons -->
											<div class="form-group full-inset">
												<button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn_create_le_vakken">SAVE</button>
												<button class="btn btn-primary btn-m-w pull-right mrg-left hidden" id="btn_update_le_vakken">UPDATE</button>
												<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn_clear_le_vakken">CLEAR</button>
												<button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left hidden" id="btn_delete_le_vakken">DELETE</button>
											</div>
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
	<?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php';}?> <!-- PLEASE CAREFULLY WIH THIS "}" THAT CLOSE SECURITY  ELSE IF -->


<script type="text/javascript">


$.ajax({
	url:"ajax/get_list_all_schools.php",
	type  : 'POST',
	dataType: "HTML",
	cache: false,
	async :false,
	success: function(data){
		$("#le_vakken_schools").html(data);
	}
})

$("#le_vakken_schools").change(function () {
	var school_id = $("#le_vakken_schools option:selected").val();
	$.ajax({
		url: "ajax/get_list_class_by_school.php?school_id="+school_id,
		type: "POST",
		dataType: "HTML",
		async: false,
		success: function(data) {
			$("#le_vakken_class").html(data);
		}
	});
});

var $frm_le_vakken  = $('#frm_le_vakken');
//@ljbello Begin Code form Le VakkenCaribe Developer
$frm_le_vakken.on("submit", function(e)
{        /* prevent refresh */
	e.preventDefault();

	if($("#le_vakken_class").val().length === 0 ||
	$("#volledigenaamvak_name").val().length === 0 ||
	$("#le_vakken_volgorde").val().length === 0 ||
	$("#le_vakken_xindex").val().length === 0)
	{
		$frm_le_vakken.find('.alert-error').removeClass('hidden');
	}
	else {
		/* prevent refresh */
		// e.preventDefault();
		// Add a User Account

		/* begin post */
		$.ajax({
			url: "ajax/add_le_vakken.php",
			data: $frm_le_vakken.serialize(),
			type: "POST",
			dataType: "text",
			success: function(text) {
				if(text != "0") {
					alert('ERROR');
					$frm_le_vakken.find('.alert-error').removeClass('hidden');
				} else if(text == "0")
				{
					$frm_le_vakken.find('#created_suscessfully').removeClass('hidden');
					location.reload();
					// $.get("ajax/get_le_vakken_tabel.php", function(data) {
					// 	$("#tbl_list_le_vakken").html(data);
					// });
					// $('#btn_clear_user').text("CLEAR");
					// // Clear all object of form
					// $('#le_vakken_schools').val('-1');
					// $('#le_vakken_class').val('-1');
					// $('#volledigenaamvak_name').val('');
					// $('#le_vakken_volgorde').val('-1');
					// $('#le_vakken_xindex').val('-1');

				}
			},
			error: function(xhr, status, errorThrown) {
				alert('ERROR');
				console.log("error");
			},
			complete: function(xhr,status) {
				$('html, body').animate({scrollTop:0}, 'fast');
				setTimeout(function() {

					$('#created_suscessfully').fadeOut(1500);
				},3000);
			}
		});
	}
});
$("#btn_update_le_vakken").on("click", function(e){
	e.preventDefault();
	// UPDATE a user_account

	/* begin post */

	$.ajax({
		url: "ajax/update_le_vakken.php",
		data: $frm_le_vakken.serialize(),
		type: "POST",
		dataType: "text",
		success: function(text) {
			if(text != 1) {
				$frm_le_vakken.find('.alert-error').removeClass('hidden');
			} else if(text == 1) {
				// alert('user_account update successfully!');
				$frm_le_vakken.find('#updated_suscessfully').removeClass('hidden');
				location.reload();
				// $.get("ajax/get_le_vakken_tabel.php", function(data) {
				// 	$('#tbl_list_le_vakken').empty();
				// 	$("#tbl_list_le_vakken").append(data);
				// });
				// $('#btn_delete_le_vakken').addClass("hidden");
				// $('#btn_clear_le_vakken').removeClass("hidden");
				//
				// // Clear all object of form
				// $('#le_vakken_schools').val('-1');
				// $('#le_vakken_class').val('-1');
				// $('#volledigenaamvak_name').val('');
				// $('#le_vakken_volgorde').val('-1');
				// $('#le_vakken_xindex').val('-1');

			}
		},
		error: function(xhr, status, errorThrown) {
			console.log("error");
		},
		complete: function(xhr,status) {
			$('html, body').animate({scrollTop:0}, 'fast');

			setTimeout(function() {
				$(".alert-info").fadeOut(1500);
				$(".alert-error").fadeOut(1500);
				$(".alert-warning").fadeOut(1500);
				$('#updated_suscessfully').fadeOut(1500);
			},3000);
		}
	});
});
$("#btn_delete_le_vakken").on("click", function(e){
	e.preventDefault();
	// DELETE a TEST
	var r =    confirm("Delete Vakken?");
	var $volledigenaamvak_id = $('#volledigenaamvak_id').val();
	if (r)

	{
		/* begin post */
		$.ajax({
			url: "ajax/delete_le_vakken.php?volledigenaamvak_id="+$volledigenaamvak_id,
			type: "POST",
			dataType: "text",
			success: function(text)
			{
				if(text != "1")
				{
					$frm_le_vakken.find('.alert-error').removeClass('hidden');
					//		$fromuser_accountRegistration.find('.alert-info').addClass('hidden');
					//		$fromuser_accountRegistration.find('.alert-warning').addClass('hidden');
				}
				else if(text == "1")
				{
					// alert('user_account deleted successfully!');
					$frm_le_vakken.find('#deleted_suscessfully').removeClass('hidden');
					location.reload();
					// $.get("ajax/get_le_vakken_tabel.php", function(data) {
					// 	$('#tbl_list_le_vakken').empty();
					// 	$("#tbl_list_le_vakken").append(data);
					// });
					// $('#btn_delete_le_vakken').addClass("hidden");
					// $('#btn_clear_le_vakken').removeClass("hidden");
					//
					// // Clear all object of form
					// $('#le_vakken_schools').val('-1');
					// $('#le_vakken_class').val('-1');
					// $('#volledigenaamvak_name').val('');
					// $('#le_vakken_volgorde').val('-1');
					// $('#le_vakken_xindex').val('-1');

				}
			},
			error: function(xhr, status, errorThrown)
			{
				console.log("error");
			},
			complete: function(xhr,status)
			{
				$('html, body').animate({scrollTop:0}, 'fast');
				$('#id_remedial').val("");
				$('#btn_clear_le_vakken').text("CLEAR");
				setTimeout(function() {
					$(".alert-info").fadeOut(1500);
					$(".alert-error").fadeOut(1500);
					$(".alert-warning").fadeOut(1500);
					$('#deleted_suscessfully').fadeOut(1500);
				},3000);
			}
		});
	}
	else
	{
		$('#btn_clear_le_vakken').text("CLEAR");
	}
});

$("#btn_clear_le_vakken").on("click", function(e){

	$('#le_vakken_schools').val('-1');
	$('#le_vakken_class').val('-1');
	$('#volledigenaamvak_name').val('');
	$('#le_vakken_volgorde').val('-1');
	$('#le_vakken_xindex').val('-1');

});

</script>
