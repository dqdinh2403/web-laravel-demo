@extends('layout.index2')

@section('title')
	{{"Tạo hợp đồng"}}
@endsection

@section('css')
	<!-- page specific plugin styles -->
	<link rel="stylesheet" href="backend/css/jquery-ui.custom.min.css" />
	<link rel="stylesheet" href="backend/css/chosen.min.css" />
	<link rel="stylesheet" href="backend/css/bootstrap-datepicker3.min.css" />
	<link rel="stylesheet" href="backend/css/bootstrap-timepicker.min.css" />
	<link rel="stylesheet" href="backend/css/daterangepicker.min.css" />
	<link rel="stylesheet" href="backend/css/bootstrap-datetimepicker.min.css" />
	<link rel="stylesheet" href="backend/css/bootstrap-colorpicker.min.css" />

	<link rel="stylesheet" href="backend/css/jquery-ui.custom.min.css" />
@endsection

@section('javascript1')
	<!-- page specific plugin scripts -->
	<script src="backend/js/jquery-ui.custom.min.js"></script>
	<script src="backend/js/jquery.ui.touch-punch.min.js"></script>
	<script src="backend/js/chosen.jquery.min.js"></script>
	<script src="backend/js/spinbox.min.js"></script>
	<script src="backend/js/bootstrap-datepicker.min.js"></script>
	<script src="backend/js/bootstrap-timepicker.min.js"></script>
	<script src="backend/js/moment.min.js"></script>
	<script src="backend/js/daterangepicker.min.js"></script>
	<script src="backend/js/bootstrap-datetimepicker.min.js"></script>
	<script src="backend/js/bootstrap-colorpicker.min.js"></script>
	<script src="backend/js/jquery.knob.min.js"></script>
	<script src="backend/js/autosize.min.js"></script>
	<script src="backend/js/jquery.inputlimiter.min.js"></script>
	<script src="backend/js/jquery.maskedinput.min.js"></script>
	<script src="backend/js/bootstrap-tag.min.js"></script>

	<script src="backend/js/markdown.min.js"></script>
	<script src="backend/js/bootstrap-markdown.min.js"></script>
	<script src="backend/js/jquery.hotkeys.index.min.js"></script>
	<script src="backend/js/bootstrap-wysiwyg.min.js"></script>
	<script src="backend/js/bootbox.js"></script>

	<!-- Bảng modal box -->
    <script src="backend/js/jquery.dataTables.min.js"></script>
	<script src="backend/js/jquery.dataTables.bootstrap.min.js"></script>
	<script src="backend/js/dataTables.buttons.min.js"></script>
	<script src="backend/js/buttons.flash.min.js"></script>
	<script src="backend/js/buttons.html5.min.js"></script>
	<script src="backend/js/buttons.print.min.js"></script>
	<script src="backend/js/buttons.colVis.min.js"></script>
	<script src="backend/js/dataTables.select.min.js"></script>
@endsection

@section('javascript2')
	<!-- inline scripts related to this page -->
	<script type="text/javascript">
		jQuery(function($) {
			$('#id-disable-check').on('click', function() {
				var inp = $('#form-input-readonly').get(0);
				if(inp.hasAttribute('disabled')) {
					inp.setAttribute('readonly' , 'true');
					inp.removeAttribute('disabled');
					inp.value="This text field is readonly!";
				}
				else {
					inp.setAttribute('disabled' , 'disabled');
					inp.removeAttribute('readonly');
					inp.value="This text field is disabled!";
				}
			});
		
			if(!ace.vars['touch']) {
				$('.chosen-select').chosen({allow_single_deselect:true}); 
				//resize the chosen on window resize
		
				$(window)
				.off('resize.chosen')
				.on('resize.chosen', function() {
					$('.chosen-select').each(function() {
						 var $this = $(this);
						 $this.next().css({'width': $this.parent().width()});
					})
				}).trigger('resize.chosen');
				//resize chosen on sidebar collapse/expand
				$(document).on('settings.ace.chosen', function(e, event_name, event_val) {
					if(event_name != 'sidebar_collapsed') return;
					$('.chosen-select').each(function() {
						 var $this = $(this);
						 $this.next().css({'width': $this.parent().width()});
					})
				});
		
				$('#chosen-multiple-style .btn').on('click', function(e){
					var target = $(this).find('input[type=radio]');
					var which = parseInt(target.val());
					if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
					 else $('#form-field-select-4').removeClass('tag-input-style');
				});
			}
		
			$('[data-rel=tooltip]').tooltip({container:'body'});
			$('[data-rel=popover]').popover({container:'body'});
		
			autosize($('textarea[class*=autosize]'));
			
			$('textarea.limited').inputlimiter({
				remText: '%n character%s remaining...',
				limitText: 'max allowed : %n.'
			});
		
			$.mask.definitions['~']='[+-]';
			$('.input-mask-date').mask('99/99/9999');
			$('.input-mask-phone').mask('(999) 999-9999');
			$('.input-mask-eyescript').mask('~9.99 ~9.99 999');
			$(".input-mask-product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("You typed the following: "+this.val());}});
		
			$( "#input-size-slider" ).css('width','200px').slider({
				value:1,
				range: "min",
				min: 1,
				max: 8,
				step: 1,
				slide: function( event, ui ) {
					var sizing = ['', 'input-sm', 'input-lg', 'input-mini', 'input-small', 'input-medium', 'input-large', 'input-xlarge', 'input-xxlarge'];
					var val = parseInt(ui.value);
					$('#form-field-4').attr('class', sizing[val]).attr('placeholder', '.'+sizing[val]);
				}
			});
		
			$( "#input-span-slider" ).slider({
				value:1,
				range: "min",
				min: 1,
				max: 12,
				step: 1,
				slide: function( event, ui ) {
					var val = parseInt(ui.value);
					$('#form-field-5').attr('class', 'col-xs-'+val).val('.col-xs-'+val);
				}
			});
		
			//"jQuery UI Slider"
			//range slider tooltip example
			$( "#slider-range" ).css('height','200px').slider({
				orientation: "vertical",
				range: true,
				min: 0,
				max: 100,
				values: [ 17, 67 ],
				slide: function( event, ui ) {
					var val = ui.values[$(ui.handle).index()-1] + "";
		
					if( !ui.handle.firstChild ) {
						$("<div class='tooltip right in' style='display:none;left:16px;top:-6px;'><div class='tooltip-arrow'></div><div class='tooltip-inner'></div></div>")
						.prependTo(ui.handle);
					}
					$(ui.handle.firstChild).show().children().eq(1).text(val);
				}
			}).find('span.ui-slider-handle').on('blur', function(){
				$(this.firstChild).hide();
			});
			
			$( "#slider-range-max" ).slider({
				range: "max",
				min: 1,
				max: 10,
				value: 2
			});
			
			$( "#slider-eq > span" ).css({width:'90%', 'float':'left', margin:'15px'}).each(function() {
				// read initial values from markup and remove that
				var value = parseInt( $( this ).text(), 10 );
				$( this ).empty().slider({
					value: value,
					range: "min",
					animate: true
					
				});
			});
			
			$("#slider-eq > span.ui-slider-purple").slider('disable');//disable third item
		
			$('#id-input-file-1 , #id-input-file-2').ace_file_input({
				no_file:'No File ...',
				btn_choose:'Choose',
				btn_change:'Change',
				droppable:false,
				onchange:null,
				thumbnail:false //| true | large
				//whitelist:'gif|png|jpg|jpeg'
				//blacklist:'exe|php'
				//onchange:''
				//
			});
			//pre-show a file name, for example a previously selected file
			//$('#id-input-file-1').ace_file_input('show_file_list', ['myfile.txt'])
		
			$('#id-input-file-3').ace_file_input({
				style: 'well',
				btn_choose: 'Drop files here or click to choose',
				btn_change: null,
				no_icon: 'ace-icon fa fa-cloud-upload',
				droppable: true,
				thumbnail: 'small'//large | fit
				//,icon_remove:null//set null, to hide remove/reset button
				/**,before_change:function(files, dropped) {
					//Check an example below
					//or examples/file-upload.html
					return true;
				}*/
				/**,before_remove : function() {
					return true;
				}*/
				,
				preview_error : function(filename, error_code) {
					//name of the file that failed
					//error_code values
					//1 = 'FILE_LOAD_FAILED',
					//2 = 'IMAGE_LOAD_FAILED',
					//3 = 'THUMBNAIL_FAILED'
					//alert(error_code);
				}
		
			}).on('change', function(){
				//console.log($(this).data('ace_input_files'));
				//console.log($(this).data('ace_input_method'));
			});
			
			//$('#id-input-file-3')
			//.ace_file_input('show_file_list', [
				//{type: 'image', name: 'name of image', path: 'http://path/to/image/for/preview'},
				//{type: 'file', name: 'hello.txt'}
			//]);
		
			//dynamically change allowed formats by changing allowExt && allowMime function
			$('#id-file-format').removeAttr('checked').on('change', function() {
				var whitelist_ext, whitelist_mime;
				var btn_choose
				var no_icon
				if(this.checked) {
					btn_choose = "Drop images here or click to choose";
					no_icon = "ace-icon fa fa-picture-o";
		
					whitelist_ext = ["jpeg", "jpg", "png", "gif" , "bmp"];
					whitelist_mime = ["image/jpg", "image/jpeg", "image/png", "image/gif", "image/bmp"];
				}
				else {
					btn_choose = "Drop files here or click to choose";
					no_icon = "ace-icon fa fa-cloud-upload";
					
					whitelist_ext = null;//all extensions are acceptable
					whitelist_mime = null;//all mimes are acceptable
				}
				var file_input = $('#id-input-file-3');
				file_input
				.ace_file_input('update_settings',
				{
					'btn_choose': btn_choose,
					'no_icon': no_icon,
					'allowExt': whitelist_ext,
					'allowMime': whitelist_mime
				})
				file_input.ace_file_input('reset_input');
				
				file_input
				.off('file.error.ace')
				.on('file.error.ace', function(e, info) {
					//console.log(info.file_count);//number of selected files
					//console.log(info.invalid_count);//number of invalid files
					//console.log(info.error_list);//a list of errors in the following format
					
					//info.error_count['ext']
					//info.error_count['mime']
					//info.error_count['size']
					
					//info.error_list['ext']  = [list of file names with invalid extension]
					//info.error_list['mime'] = [list of file names with invalid mimetype]
					//info.error_list['size'] = [list of file names with invalid size]
					
					
					/**
					if( !info.dropped ) {
						//perhapse reset file field if files have been selected, and there are invalid files among them
						//when files are dropped, only valid files will be added to our file array
						e.preventDefault();//it will rest input
					}
					*/
					
					
					//if files have been selected (not dropped), you can choose to reset input
					//because browser keeps all selected files anyway and this cannot be changed
					//we can only reset file field to become empty again
					//on any case you still should check files with your server side script
					//because any arbitrary file can be uploaded by user and it's not safe to rely on browser-side measures
				});
				
				
				/**
				file_input
				.off('file.preview.ace')
				.on('file.preview.ace', function(e, info) {
					console.log(info.file.width);
					console.log(info.file.height);
					e.preventDefault();//to prevent preview
				});
				*/
			
			});
		
			$('#spinner1').ace_spinner({value:0,min:0,max:200,step:10, btn_up_class:'btn-info' , btn_down_class:'btn-info'})
			.closest('.ace-spinner')
			.on('changed.fu.spinbox', function(){
				//console.log($('#spinner1').val())
			}); 
			$('#spinner2').ace_spinner({value:0,min:0,max:10000,step:100, touch_spinner: true, icon_up:'ace-icon fa fa-caret-up bigger-110', icon_down:'ace-icon fa fa-caret-down bigger-110'});
			$('#spinner3').ace_spinner({value:0,min:-100,max:100,step:10, on_sides: true, icon_up:'ace-icon fa fa-plus bigger-110', icon_down:'ace-icon fa fa-minus bigger-110', btn_up_class:'btn-success' , btn_down_class:'btn-danger'});
			$('#spinner4').ace_spinner({value:0,min:-100,max:100,step:10, on_sides: true, icon_up:'ace-icon fa fa-plus', icon_down:'ace-icon fa fa-minus', btn_up_class:'btn-purple' , btn_down_class:'btn-purple'});
		
			//$('#spinner1').ace_spinner('disable').ace_spinner('value', 11);
			//or
			//$('#spinner1').closest('.ace-spinner').spinner('disable').spinner('enable').spinner('value', 11);//disable, enable or change value
			//$('#spinner1').closest('.ace-spinner').spinner('value', 0);//reset to 0
		
			//datepicker plugin
			//link
			$('.date-picker').datepicker({
				autoclose: true,
				todayHighlight: true
			})
			//show datepicker when clicking on the icon
			.next().on(ace.click_event, function(){
				$(this).prev().focus();
			});
		
			//or change it into a date range picker
			$('.input-daterange').datepicker({autoclose:true});
		
			//to translate the daterange picker, please copy the "examples/daterange-fr.js" contents here before initialization
			$('input[name=date-range-picker]').daterangepicker({
				'applyClass' : 'btn-sm btn-success',
				'cancelClass' : 'btn-sm btn-default',
				locale: {
					applyLabel: 'Apply',
					cancelLabel: 'Cancel',
				}
			})
			.prev().on(ace.click_event, function(){
				$(this).next().focus();
			});
		
			$('#timepicker1').timepicker({
				minuteStep: 1,
				showSeconds: true,
				showMeridian: false,
				disableFocus: true,
				icons: {
					up: 'fa fa-chevron-up',
					down: 'fa fa-chevron-down'
				}
			}).on('focus', function() {
				$('#timepicker1').timepicker('showWidget');
			}).next().on(ace.click_event, function(){
				$(this).prev().focus();
			});
			
			if(!ace.vars['old_ie']) $('#date-timepicker1').datetimepicker({
			 //format: 'MM/DD/YYYY h:mm:ss A',//use this option to display seconds
			 icons: {
				time: 'fa fa-clock-o',
				date: 'fa fa-calendar',
				up: 'fa fa-chevron-up',
				down: 'fa fa-chevron-down',
				previous: 'fa fa-chevron-left',
				next: 'fa fa-chevron-right',
				today: 'fa fa-arrows ',
				clear: 'fa fa-trash',
				close: 'fa fa-times'
			 }
			}).next().on(ace.click_event, function(){
				$(this).prev().focus();
			});
			
			$('#colorpicker1').colorpicker();
			//$('.colorpicker').last().css('z-index', 2000);//if colorpicker is inside a modal, its z-index should be higher than modal'safe
		
			$('#simple-colorpicker-1').ace_colorpicker();
			//$('#simple-colorpicker-1').ace_colorpicker('pick', 2);//select 2nd color
			//$('#simple-colorpicker-1').ace_colorpicker('pick', '#fbe983');//select #fbe983 color
			//var picker = $('#simple-colorpicker-1').data('ace_colorpicker')
			//picker.pick('red', true);//insert the color if it doesn't exist
		
			$(".knob").knob();
			
			var tag_input = $('#form-field-tags');
			try{
				tag_input.tag(
				  {
					placeholder:tag_input.attr('placeholder'),
					//enable typeahead by specifying the source array
					source: ace.vars['US_STATES'],//defined in ace.js >> ace.enable_search_ahead
					/**
					//or fetch data from database, fetch those that match "query"
					source: function(query, process) {
					  $.ajax({url: 'remote_source.php?q='+encodeURIComponent(query)})
					  .done(function(result_items){
						process(result_items);
					  });
					}
					*/
				  }
				)
		
				//programmatically add/remove a tag
				var $tag_obj = $('#form-field-tags').data('tag');
				$tag_obj.add('Programmatically Added');
				
				var index = $tag_obj.inValues('some tag');
				$tag_obj.remove(index);
			}
			catch(e) {
				//display a textarea for old IE, because it doesn't support this plugin or another one I tried!
				tag_input.after('<textarea id="'+tag_input.attr('id')+'" name="'+tag_input.attr('name')+'" rows="3">'+tag_input.val()+'</textarea>').remove();
				//autosize($('#form-field-tags'));
			}
			
			/////////
			$('#modal-form input[type=file]').ace_file_input({
				style:'well',
				btn_choose:'Drop files here or click to choose',
				btn_change:null,
				no_icon:'ace-icon fa fa-cloud-upload',
				droppable:true,
				thumbnail:'large'
			})
			
			//chosen plugin inside a modal will have a zero width because the select element is originally hidden
			//and its width cannot be determined.
			//so we set the width after modal is show
			$('#modal-form').on('shown.bs.modal', function () {
				if(!ace.vars['touch']) {
					$(this).find('.chosen-container').each(function(){
						$(this).find('a:first-child').css('width' , '210px');
						$(this).find('.chosen-drop').css('width' , '210px');
						$(this).find('.chosen-search input').css('width' , '200px');
					});
				}
			})
			/**
			//or you can activate the chosen plugin after modal is shown
			//this way select element becomes visible with dimensions and chosen works as expected
			$('#modal-form').on('shown', function () {
				$(this).find('.modal-chosen').chosen();
			})
			*/
		
			$(document).one('ajaxloadstart.page', function(e) {
				autosize.destroy('textarea[class*=autosize]')
				
				$('.limiterBox,.autosizejs').remove();
				$('.daterangepicker.dropdown-menu,.colorpicker.dropdown-menu,.bootstrap-datetimepicker-widget.dropdown-menu').remove();
			});
		});
	</script>


	<!-- inline scripts related to this page -->
	<script type="text/javascript">
		jQuery(function($){

			$('textarea[data-provide="markdown"]').each(function(){
		        var $this = $(this);

				if ($this.data('markdown')) {
				  $this.data('markdown').showEditor();
				}
				else $this.markdown()
				
				$this.parent().find('.btn').addClass('btn-white');
		    })
			
			function showErrorAlert (reason, detail) {
				var msg='';
				if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
				else {
					//console.log("error uploading file", reason, detail);
				}
				$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
				 '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
			}

			//$('#editor1').ace_wysiwyg();//this will create the default editor will all buttons

			//but we want to change a few buttons colors for the third style
			$('#editor1').ace_wysiwyg({
				toolbar:
				[
					'font',
					null,
					'fontSize',
					null,
					{name:'bold', className:'btn-info'},
					{name:'italic', className:'btn-info'},
					{name:'strikethrough', className:'btn-info'},
					{name:'underline', className:'btn-info'},
					null,
					{name:'insertunorderedlist', className:'btn-success'},
					{name:'insertorderedlist', className:'btn-success'},
					{name:'outdent', className:'btn-purple'},
					{name:'indent', className:'btn-purple'},
					null,
					{name:'justifyleft', className:'btn-primary'},
					{name:'justifycenter', className:'btn-primary'},
					{name:'justifyright', className:'btn-primary'},
					{name:'justifyfull', className:'btn-inverse'},
					null,
					{name:'createLink', className:'btn-pink'},
					{name:'unlink', className:'btn-pink'},
					null,
					{name:'insertImage', className:'btn-success'},
					null,
					'foreColor',
					null,
					{name:'undo', className:'btn-grey'},
					{name:'redo', className:'btn-grey'}
				],
				'wysiwyg': {
					fileUploadError: showErrorAlert
				}
			}).prev().addClass('wysiwyg-style2');

			/**
			//make the editor have all the available height
			$(window).on('resize.editor', function() {
				var offset = $('#editor1').parent().offset();
				var winHeight =  $(this).height();
				
				$('#editor1').css({'height':winHeight - offset.top - 10, 'max-height': 'none'});
			}).triggerHandler('resize.editor');
			*/
			
			$('#editor2').css({'height':'200px'}).ace_wysiwyg({
				toolbar_place: function(toolbar) {
					return $(this).closest('.widget-box')
					       .find('.widget-header').prepend(toolbar)
						   .find('.wysiwyg-toolbar').addClass('inline');
				},
				toolbar:
				[
					'bold',
					{name:'italic' , title:'Change Title!', icon: 'ace-icon fa fa-leaf'},
					'strikethrough',
					null,
					'insertunorderedlist',
					'insertorderedlist',
					null,
					'justifyleft',
					'justifycenter',
					'justifyright'
				],
				speech_button: false
			});
			
			$('[data-toggle="buttons"] .btn').on('click', function(e){
				var target = $(this).find('input[type=radio]');
				var which = parseInt(target.val());
				var toolbar = $('#editor1').prev().get(0);
				if(which >= 1 && which <= 4) {
					toolbar.className = toolbar.className.replace(/wysiwyg\-style(1|2)/g , '');
					if(which == 1) $(toolbar).addClass('wysiwyg-style1');
					else if(which == 2) $(toolbar).addClass('wysiwyg-style2');
					if(which == 4) {
						$(toolbar).find('.btn-group > .btn').addClass('btn-white btn-round');
					} else $(toolbar).find('.btn-group > .btn-white').removeClass('btn-white btn-round');
				}
			});

			//RESIZE IMAGE
			
			//Add Image Resize Functionality to Chrome and Safari
			//webkit browsers don't have image resize functionality when content is editable
			//so let's add something using jQuery UI resizable
			//another option would be opening a dialog for user to enter dimensions.
			if ( typeof jQuery.ui !== 'undefined' && ace.vars['webkit'] ) {
				
				var lastResizableImg = null;
				function destroyResizable() {
					if(lastResizableImg == null) return;
					lastResizableImg.resizable( "destroy" );
					lastResizableImg.removeData('resizable');
					lastResizableImg = null;
				}

				var enableImageResize = function() {
					$('.wysiwyg-editor')
					.on('mousedown', function(e) {
						var target = $(e.target);
						if( e.target instanceof HTMLImageElement ) {
							if( !target.data('resizable') ) {
								target.resizable({
									aspectRatio: e.target.width / e.target.height,
								});
								target.data('resizable', true);
								
								if( lastResizableImg != null ) {
									//disable previous resizable image
									lastResizableImg.resizable( "destroy" );
									lastResizableImg.removeData('resizable');
								}
								lastResizableImg = target;
							}
						}
					})
					.on('click', function(e) {
						if( lastResizableImg != null && !(e.target instanceof HTMLImageElement) ) {
							destroyResizable();
						}
					})
					.on('keydown', function() {
						destroyResizable();
					});
			    }

				enableImageResize();

				/**
				//or we can load the jQuery UI dynamically only if needed
				if (typeof jQuery.ui !== 'undefined') enableImageResize();
				else {//load jQuery UI if not loaded
					//in Ace demo ./components will be replaced by correct components path
					$.getScript("backend/js/jquery-ui.custom.min.js", function(data, textStatus, jqxhr) {
						enableImageResize()
					});
				}
				*/
			}

			$('#btnTaoMoi').click(function(){
				var data = $('#editor1').html();
				$('#txtNoiDungHopDong').val(data);
			});
		});
	</script>

	<!-- Bảng modal box -->
    <script type="text/javascript">
		jQuery(function($) {
			//initiate dataTables plugin
			var myTable = 
			$('#dynamic-table')
			//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
			.DataTable( {
				bAutoWidth: false,
				"aoColumns": [
				  null, null, null, null, null
				],
				"aaSorting": [],
				
				
				//"bProcessing": true,
		        //"bServerSide": true,
		        //"sAjaxSource": "http://127.0.0.1/table.php"	,
		
				//,
				//"sScrollY": "200px",
				//"bPaginate": false,
		
				//"sScrollX": "100%",
				//"sScrollXInner": "120%",
				//"bScrollCollapse": true,
				//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
				//you may want to wrap the table inside a "div.dataTables_borderWrap" element
		
				//"iDisplayLength": 50
		
		
				select: {
					style: 'multi'
				}
		    } );
		
			
			
			$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
			
			new $.fn.dataTable.Buttons( myTable, {
				buttons: [
				  {
					"extend": "colvis",
					"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
					"className": "btn btn-white btn-primary btn-bold",
					columns: ':not(:first):not(:last)'
				  },
				  {
					"extend": "copy",
					"text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
					"className": "btn btn-white btn-primary btn-bold"
				  },
				  {
					"extend": "csv",
					"text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
					"className": "btn btn-white btn-primary btn-bold"
				  },
				  {
					"extend": "excel",
					"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
					"className": "btn btn-white btn-primary btn-bold"
				  },
				  {
					"extend": "pdf",
					"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
					"className": "btn btn-white btn-primary btn-bold"
				  },
				  {
					"extend": "print",
					"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
					"className": "btn btn-white btn-primary btn-bold",
					autoPrint: false,
					message: 'This print was produced using the Print button for DataTables'
				  }		  
				]
			} );
			myTable.buttons().container().appendTo( $('.tableTools-container') );
			
			//style the message box
			var defaultCopyAction = myTable.button(1).action();
			myTable.button(1).action(function (e, dt, button, config) {
				defaultCopyAction(e, dt, button, config);
				$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
			});
			
			
			var defaultColvisAction = myTable.button(0).action();
			myTable.button(0).action(function (e, dt, button, config) {
				
				defaultColvisAction(e, dt, button, config);
				
				
				if($('.dt-button-collection > .dropdown-menu').length == 0) {
					$('.dt-button-collection')
					.wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
					.find('a').attr('href', '#').wrap("<li />")
				}
				$('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
			});
		
			////
		
			setTimeout(function() {
				$($('.tableTools-container')).find('a.dt-button').each(function() {
					var div = $(this).find(' > div').first();
					if(div.length == 1) div.tooltip({container: 'body', title: div.parent().text()});
					else $(this).tooltip({container: 'body', title: $(this).text()});
				});
			}, 500);
			
			
			
			
			
			myTable.on( 'select', function ( e, dt, type, index ) {
				if ( type === 'row' ) {
					$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
				}
			} );
			myTable.on( 'deselect', function ( e, dt, type, index ) {
				if ( type === 'row' ) {
					$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
				}
			} );
		
		
		
		
			/////////////////////////////////
			//table checkboxes
			$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
			
			//select/deselect all rows according to table header checkbox
			$('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
				var th_checked = this.checked;//checkbox inside "TH" table header
				
				$('#dynamic-table').find('tbody > tr').each(function(){
					var row = this;
					if(th_checked) myTable.row(row).select();
					else  myTable.row(row).deselect();
				});
			});
			
			//select/deselect a row when the checkbox is checked/unchecked
			$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
				var row = $(this).closest('tr').get(0);
				if(this.checked) myTable.row(row).deselect();
				else myTable.row(row).select();
			});
		
		
		
			$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
				e.stopImmediatePropagation();
				e.stopPropagation();
				e.preventDefault();
			});
			
			
			
			//And for the first simple table, which doesn't have TableTools or dataTables
			//select/deselect all rows according to table header checkbox
			var active_class = 'active';
			$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
				var th_checked = this.checked;//checkbox inside "TH" table header
				
				$(this).closest('table').find('tbody > tr').each(function(){
					var row = this;
					if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
					else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
				});
			});
			
			//select/deselect a row when the checkbox is checked/unchecked
			$('#simple-table').on('click', 'td input[type=checkbox]' , function(){
				var $row = $(this).closest('tr');
				if($row.is('.detail-row ')) return;
				if(this.checked) $row.addClass(active_class);
				else $row.removeClass(active_class);
			});
		
			
		
			/********************************/
			//add tooltip for small view action buttons in dropdown menu
			$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
			
			//tooltip placement on right or left
			function tooltip_placement(context, source) {
				var $source = $(source);
				var $parent = $source.closest('table')
				var off1 = $parent.offset();
				var w1 = $parent.width();
		
				var off2 = $source.offset();
				//var w2 = $source.width();
		
				if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
				return 'left';
			}
			
			
			
			
			/***************/
			$('.show-details-btn').on('click', function(e) {
				e.preventDefault();
				$(this).closest('tr').next().toggleClass('open');
				$(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
			});
			/***************/
			
			
			
			
			
			/**
			//add horizontal scrollbars to a simple table
			$('#simple-table').css({'width':'2000px', 'max-width': 'none'}).wrap('<div style="width: 1000px;" />').parent().ace_scroll(
			  {
				horizontal: true,
				styleClass: 'scroll-top scroll-dark scroll-visible',//show the scrollbars on top(default is bottom)
				size: 2000,
				mouseWheelLock: true
			  }
			).css('padding-top', '12px');
			*/
		
		
		})
	</script>
@endsection

@section('breadcrumb')
	<li class="active">Quản lý sự kiện</li>
	<li class="active">Tạo hợp đồng</li>
@endsection

@section('content')
	<div class="page-content">
		<div class="page-header">
			<h1>Tạo hợp đồng sự kiện "{{$sukien->sk_ten}}":</h1>
		</div><!-- /.page-header -->

		<div class="row">
			<div class="col-xs-12">
				<!-- PAGE CONTENT BEGINS -->

	            <!-- Phần hiển thị thông báo -->
	            @if( count($errors)>0 )
	              <div class="alert alert-danger">
	                @foreach($errors->all() as $er)
	                  {{$er}} <br>
	                @endforeach
	              </div>
	            @endif

	            @if( session('thongbao') )
	              <div class="alert alert-success">
	                {{session('thongbao')}}
	              </div>
	            @endif
	            @if( session('loi') )
	              <div class="alert alert-danger">
	                {{session('loi')}}
	              </div>
	            @endif

				<form id="formTaoHopDong" name="formTaoHopDong" method="POST" action="admin/sukien/taohopdong/{{$sukien->sk_ma}}" class="form-horizontal" role="form"> 
				    {{csrf_field()}}

					<h4 class="pink">
						<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
						<a href="#modal-table" role="button" class="green" data-toggle="modal"> Nội dung sự kiện </a>
					</h4>

					<div id="modal-table" class="modal fade" tabindex="-1">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header no-padding">
									<div class="table-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
											<span class="white">&times;</span>
										</button>
										Nội dung chi tiết sự kiện
									</div>
								</div>

								<div class="modal-body no-padding">

									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="txtLoaiSuKien"> Loại sự kiện(*): </label>
										<div class="col-sm-8">
											<input type="text" name="txtLoaiSuKien" id="txtLoaiSuKien" class="col-xs-10 col-sm-10" placeholder="Loại sự kiện" value="{{$loaisukien->lsk_ten}}" readonly="" />
										</div>
									</div>

								    <div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="txtTenSuKien"> Tên sự kiện(*): </label>
										<div class="col-sm-8">
											<input type="text" name="txtTenSuKien" id="txtTenSuKien" class="col-xs-10 col-sm-10" placeholder="Tên sự kiện" value="{{$sukien->sk_ten}}" readonly="" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="txtDiaDiem"> Địa điểm(*): </label>
										<div class="col-sm-8">
											<input type="text" name="txtDiaDiem" id="txtDiaDiem" class="col-xs-10 col-sm-10" placeholder="Địa điểm" value="{{$sukien->sk_diadiem}}" readonly="" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="txtThoiGian"> Thời gian tổ chức(*): </label>
										<div class="col-sm-8">
								            <input type="time" name="txtTime" id="txtTime" class="col-xs-10 col-sm-4" value="{{$sukien->sk_thoigianbatdaut}}" readonly="" />
								        	<input type="date" name="txtDate" id="txtDate" class="col-xs-10 col-sm-6" value="{{$sukien->sk_thoigianbatdaud}}" readonly="" />
								        </div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="txtThoiLuong"> Thời lượng(*): </label>
										<div class="col-sm-8">
											<input type="number" name="txtThoiLuong" id="txtThoiLuong" class="col-xs-10 col-sm-10" placeholder="Thời lượng" value="{{$sukien->sk_thoiluong}}" readonly="" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="txtKinhPhi"> Kinh phí(*): </label>
										<div class="col-sm-8">
											<input type="number" name="txtKinhPhi" id="txtKinhPhi" class="col-xs-10 col-sm-10" placeholder="Kinh phí" value="{{$sukien->sk_kinhphi}}" readonly="" />
										</div>
									</div>

									<div class="form-group">   
					                    <label class="col-sm-4 control-label no-padding-right"> Nội dung sự kiện: </label>
					                    <div class="col-sm-8">
					                    	<p>{!!$sukien->sk_noidungsukien!!}</p>
					                    </div>
					                </div>

								</div>

								<div class="modal-footer no-margin-top">
									<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
										<i class="ace-icon fa fa-times"></i>
										Đóng
									</button>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="txtSoHD"> Số hợp đồng(*): </label>
						<div class="col-sm-10">
							<input type="text" name="txtSoHD" id="txtSoHD" class="col-xs-10 col-sm-5" placeholder="Số hợp đồng" value="{{$hopdong->hdtcsk_sohopdong}}" disabled="" />
						</div>
					</div>

					<input type="hidden" name="txtSoHopDong" id="txtSoHopDong" value="{{$hopdong->hdtcsk_sohopdong}}">

					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="txtKhachHang"> Khách hàng(*): </label>
						<div class="col-sm-10">
							<input type="text" name="txtKhachHang" id="txtKhachHang" class="col-xs-10 col-sm-5" placeholder="Khách hàng" value="{{$khachhang->kh_tencongty}}" disabled="" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="txtGiaTri"> Giá trị hợp đồng(*): </label>
						<div class="col-sm-10">
							<input type="number" name="txtGiaTri" id="txtGiaTri" class="col-xs-10 col-sm-5" placeholder="Giá trị hợp đồng" value="{{$sukien->sk_kinhphi}}" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="txtTamUng"> Số tiền tạm ứng: </label>
						<div class="col-sm-10">
							<input type="number" name="txtTamUng" id="txtTamUng" class="col-xs-10 col-sm-5" placeholder="Số tiền tạm ứng" value="{{old('txtTamUng')}}" />
						</div>
					</div>

					<div class="form-group">   
	                    <label class="col-sm-2 control-label no-padding-right"> Nội dung hợp đồng: </label>
	                    <div class="col-sm-8">
	                    	<div class="wysiwyg-editor" id="editor1"></div>
	                    </div>
	                </div>

	                <input type="hidden" name="txtNoiDungHopDong" id="txtNoiDungHopDong" value="">

					<div class="col-md-offset-2 col-md-10">
						<button type="submit" class="btn btn-info" name="btnTaoMoi" id="btnTaoMoi" >
							<i class="ace-icon fa fa-check bigger-110"></i>
							Tạo mới
						</button>

						&nbsp; &nbsp; &nbsp;
						<button type="reset" class="btn" name="btnHuy" id="btnHuy" onClick="window.location='admin/sukien/dssknohd'" >
							<i class="ace-icon fa fa-undo bigger-110"></i>
							Hủy
						</button>
					</div>
					
				</form>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.page-content -->
@endsection
