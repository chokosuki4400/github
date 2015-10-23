/* **********************************
	
	KASHIDASH
	common.js
	2012.10
	
********************************** */

$(function () {

/* entry date set
--------------------------------------------------------- */	
	function entryDateSet(){
		
	
	var	eDate = new Date(),
		eYear = eDate.getFullYear(),
		eMonth = eDate.getMonth()+1,
		eDay = eDate.getDate(),
		eHour = eDate.getHours(),
		eMinute = eDate.getMinutes();
	
	var	yearList = $('#lendingYear > option'),
		monthList = $('#lendingMonth > option'),
		dayList = $('#lendingDay > option'),
		hourList = $('#lendingHour > option'),
		minutesList = $('#lendingMinute > option');
	
	
	for(var i=0; i<yearList.length; i++){
		if(yearList.eq(i).val() == eYear){
			yearList.eq(i).attr('selected','selected');
		}
	}
	for(var j=0; j<monthList.length; j++){
		if(monthList.eq(j).val() == eMonth){
			monthList.eq(j).attr('selected','selected');
		}
	}
	for(var k=0; k<dayList.length; k++){
		if(dayList.eq(k).val() == eDay){
			dayList.eq(k).attr('selected','selected');
		}
	}
	
	if(eMinute >=0 && eMinute < 15){
		eMinute = 0;
	}else if(eMinute >= 15 && eMinute < 30){
		eMinute = 15;
	}else if(eMinute >= 30 && eMinute < 45){
		eMinute = 30
	}else{
		eMinute = 45
	}
	
	for(var l=0; l<hourList.length; l++){
		if(hourList.eq(l).val() == eHour){
			hourList.eq(l).attr('selected','selected');
		}
	}
	for(var m=0; m<minutesList.length; m++){
		if(minutesList.eq(m).val() == eMinute){
			minutesList.eq(m).attr('selected','selected');
		}
	}
	
	}
	/* POPUP
	--------------------------------------------------*/
	$(".no-touch a[rel^='prettyPopin']").prettyPopin({
			width: 650,
			height: 400,
			loader_path: 'common/images/prettyPopin/loader.gif',
			callback: function(){
				entryDateSet();
			}
			});
	
	/* auto height
	--------------------------------------------------*/
	function heightSet(){
		$('.panel .panel-heading').autoHeight({
			column:3,
			clear:1,
			height:'height',
			reset : 'reset'
		});
		$('.panel .panel-footer .row').autoHeight({
			column:3,
			clear:1,
			height:'height',
			reset : 'reset'
		});
	}
	
	
	var resizeTimer = false;
	$(window).resize(function() {
	    if (resizeTimer !== false) {
	        clearTimeout(resizeTimer);
	    }
	    resizeTimer = setTimeout(function() {
	        console.log('resized');
	        //heightSet();
	    }, 100);
	});


	//heightSet();
});
