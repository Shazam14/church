
	$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			//defaultDate: '2016-01-12',
			editable: true,
			businessHours: true,
			eventLimit: true, // allow "more" link when too many events
			events: {
				url: '../php/getEvents.php',
				error: function() {
					$('#script-warning').show();
				}
			},
			eventAfterRender: function (event, element, view) 
			{
				var now = new Date();	
				var NewDate = moment(now).format("YYYY-MM-DD HH:mm");
				var dateEnd = moment(event.end).format("YYYY-MM-DD HH:mm");
				var dateStart = moment(event.start).format("YYYY-MM-DD HH:mm");				
				/*document.write(NewDate + '<br/>');
				document.write(event.title + '<br/>');
				document.write(dateStart + '<br/>');
				document.write(dateEnd + '<br/>');*/
				if(dateStart < NewDate && dateEnd > NewDate) {            
					overlap: false,
					element.css('background-color', '#77dd77');//Present or In progress 	
									
				}else if (dateStart < NewDate && dateEnd < NewDate) {
					
					element.css('background-color', '#7777dd');//Past or Completed 
					
				}else if (dateStart > NewDate && dateEnd > NewDate) {
												
					element.css('background-color', '#dd77dd');//Future or not Started 
					
				}					
			},
			eventClick: function(event) {	
				$(".event-card").show();
				$(".event-title").text(event.title);
    			$(".event-start").text(event.start);
    			$(".event-end").text(event.end);
			},
			loading: function(bool) {
				$('#loading').toggle(bool);
			}
		});
