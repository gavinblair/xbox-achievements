var ax;
jQuery(document).ready(function($){
	$('#gamertag a').click(function(){
		getgames($('#tag').val());
	});
	$('#gamertag input').keydown(function(e){
		if(e.keyCode == 13){
			getgames($('#tag').val());
		}
	});
	$('#back').click(function(){
		$('.page').removeClass('current');
		$('#games').addClass('current');	
	});
	$('#startover').click(function(){
		//reload the page
		location.reload();
	});
});

function getgames(gamertag){
	if(gamertag.length == 0) {
		gamertag = 'SHHHM3LLIT';
	}
	$('#games h1').text(gamertag);
	$('.page').removeClass('current');
	$('#games').addClass('current');
	console.log('ajaxing...');
	if(ax) ax.abort();
	ax = $.ajax({
		type: 'get',
		data: 'gamertag='+gamertag,
		url: 'games.php',
		datType: 'json',
		success: function(msg){
			$('#games .loading').remove();
			for(var i in msg.games){
				if(!msg.games[i].isapp) {
					var html = "<div data-gamename='"+msg.games[i].title+"' data-gameid='"+msg.games[i].id+"' class='game'><img src='"+msg.games[i].artwork.small+"' /><span>"+msg.games[i].title+"</span></div>";
					$('#games').append(html);
				}
			}
			$('.game').on('click', function(){
				getresults($(this).attr('data-gameid'),$('#games h1').text(),$(this).attr('data-gamename'));
			});
		}
	});
}

function getresults(gameid, gamertag, gamename){
	$('.page').removeClass('current');
	$('#results').addClass('current');
	$('#results h1').html("<small>"+gamertag+":</small><br /><em>"+gamename+"</em>");
	$('#results .loading').show();
	$('#wishyouhad').remove();
	console.log('ajaxing...');
	if(ax) ax.abort();
	ax = $.ajax({
		type: 'get',
		data: 'gamertag='+gamertag+'&gameid='+gameid,
		url: 'wishyouhad.php',
		datType: 'html',
		success: function(msg){
			$('#results .loading').hide();
			$('#results').append(msg);
		},
		error: function(){
			$('#results .loading').hide();
			$('#results').append('<div id="wishyouhad">Something bad happened. It\'s probably you\'re fault.</div>');
		}
	});
}
