<?php 
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->request->baseUrl. '/js/jquery.scrollbox.js' , CClientScript::POS_END);
?>
<style>
body {	/*overflow: hidden;*/}

canvas{position:absolute;top:0px;left:0px;}

.appMenuContainer{background-color:rgba(59, 120, 163, 0.7);width:100%;height:55px;position:absolute;top:61px;left:0px;z-index:1000;}
.appMenu{position:absolute;top:5px;right:30px;z-index:1051;}
.appMenu li{padding:5px;margin:5px;border:2px solid #666;display:inline;float:left;background-color:#F5E414;}
.appMenu a{color:#324553;font-weight:bold;}

#appPanel{float:right;border:2px solid #000;background-color:#FFF;width:500px;margin-right:100px;padding:5px;
  height: 6em;
  overflow: hidden;
}
#appPanel ul{list-style:none}

.appContent{position:absolute;top:120px;left:120px;z-index:1000;width:90%;display:none}
.appContent h1{margin-left:0px;text-decoration:underline;font-family: "Homestead";color: #fff;}
.appContent ul.people li{position:relative;width:190px;height:100px;padding:5px;margin:5px;display:block;float:left;background-color:#FFF;-webkit-border-radius: 5px;-moz-border-radius: 5px;-o-border-radius: 5px;-ms-border-radius: 5px;border-radius: 5px;}
.appContent ul.people li.me{background-color:#F5E414;}
.appContent ul.people li.me img{cursor:pointer}
.appContent ul.people li.descL {height:200px; }
.appContent li.participant{border:2px solid yellow;background-url:#fff url('<?php echo Yii::app()->createUrl('images/PHOTO_ANONYMOUS.png')?>') no-repeat bottom left;}
.appContent li.projet{border:2px solid orange;}
.appContent li.coach{border:2px solid purple;}
.appContent li.jury{border:2px solid red;}
.appContent li.organisateur{border:2px solid blue;}
.sponsor {list-style:none}
.sponsor img{width:100px;margin-bottom:20px;}
.appContent div.infos{word-wrap:break-word;text-align:right}
.appContent div.type {display:block;float:right;font-size:x-small;}
.appContent div.name {font-family: "Homestead";color: #324553;font-size:medium; margin-left:10px;display:block;float:right; }
.appContent div.desc {position:absolute;width:100%;bottom:0px; margin:5px;text-align:left;word-wrap: break-word;width:180px;padding-right:5px;}
.appContent div.desc span.txt{font-size:small;}
.appContent div.desc a.btn-ph{display:inline-block;float:left;margin-right:5px;}
.appContent div.thumb{height:40px;width:40px;float:left;}
.appContent .metier{width:20px;height:20px;background-color:red;position:relative; top:0px; right:0px;-webkit-border-radius: 20px;-moz-border-radius: 20px;-o-border-radius: 20px;-ms-border-radius: 20px;border-radius: 20px;border:1px solid #000;}

.appFooter{position:fixed;bottom:0px;right:0px;width:100px;z-index:2000;margin:15px;}

.bgRed{background-color:red;}
.cRed{color:red;}
.coachRequestedColor{border:5px solid red;}
</style>

<div class="appMenuContainer">
    <ul class="appMenu">
    	<?php if( in_array( Yii::app()->session["userEmail"], $event["adminEmail"]) ){ ?>
    		<li><a href="<?php echo Yii::app()->createUrl('index.php/evenement/sweadmin/id/'.$key)?>"><i class="icon-wrench"></i> Admin</a></li>
    	<?php } ?>
    	<?php /*?><li><a href="#sweInscription" id="mesInfos" role="button" data-toggle="modal"><i class="icon-user"></i> Mes Infos</a></li>*/?>
    	<li><a href="#coaching" role="button" data-toggle="modal"><i class="icon-bell"></i> APPEL UN COACH !! </a><a href="#coaching" role="button" data-toggle="modal"><span id="coachingCount" class="badge bgRed" ></span></a></li>
        <li><a href="javascript:filterType('participant')">Inscrits <span class="badge"><?php echo count($event["participants"])?></span></a></li>
        <li><a href="javascript:filterType('projet')">Projets <span class="badge"><?php echo count($event["projects"])?></span></a></li>
        <li><a href="javascript:filterType('coach')">Coachs <span class="badge"><?php echo count($event["coaches"])?></span></a></li>
        <li><a href="javascript:filterType('jury')">Jurys <span class="badge"><?php echo count($event["jurys"])?></span></a></li>
        <li><a href="javascript:filterType('organisateur')">Organisateurs <span class="badge"><?php echo count($event["organisateurs"])?></span></a></li>
    </ul>
</div>

<div class="appContent">

    <div id="appPanel" class="hidden">
    	<ul id="appPanelList"></ul>
    </div>
    
	<h1><?php echo $event["name"]?><br/><span class="appTitle">  </span></h1>
	
	<br/>
	
	<ul class="people">
    	<?php
    	
    	$row = 1;
    	$coaches = array();
    	$myproject = '';
    	$projects = array();
    	$meType = '';
        foreach ($sweThings as $line) 
        {
            if(in_array($event['_id'],$line['events'])){
                $name = (isset($line["name"])) ? $line["name"]: null;
                $type = (isset($line["type"])) ? $line["type"] : null;
                $email = (isset($line["email"])) ? $line["email"]:null;
                $desc = (isset($line["desc"])) ? $line["desc"]:null;
                $project = "";
                if( $key == "StartupWeekEnd2012" && isset($line["projet"])) 
                    $project = str_replace(' ', '', $line["projet"]);
                else if( $key == "StartupWeekEnd2013" && isset($line["projet13"])) 
                    $project = str_replace(' ', '', $line["projet13"]);
                    
                $img = (isset($line["image"]))? $line["image"]:"";
                
                //some panels will have more information than others
                $classDesc = (isset($type) && in_array($type, array('jury','coach','projet'))) ? 'descL' : '';
                //only show people panels on load 
                $classHide = (isset($type) && in_array($type, array('participant'))) ? 'hide' : '';
                //connected users panel will be different
                $classMe = (Yii::app()->session["userEmail"] == $email && $type!='projet') ? 'me' : '';
                if(!empty($classMe))$meType = $type;
                if(!empty($classMe) && !empty($project) )
                    $myproject = $project;
    
                //desc content
                
                $xtra = '<div class="xtra clear"></div><div class="desc">';
                if( isset( $desc) && $desc == strip_tags($desc) )
                    $xtra .= '<span class="txt">';
                $xtra .= (isset($desc)) ? $desc : '';
                if( isset( $desc) && $desc == strip_tags($desc) )
                    $xtra .= '</span><div class="clear"></div>';
                    
                $classProjet = ''; 
                if(!empty($project))
                {
                    $classProjet = $project;
                    //adds show team on project panel
                    if(isset($type) && ( $type=='projet' || $type=='participant')) 
                    {
                        $xtra .= "<a  class='btn-ph' href='#' onclick='filterType(\"".$project."\")' title='Project Team'><span class='entypo-users'></span></a>";
                    }
                } /*else if ( isset($type) && $type=='participant' && in_array( Yii::app()->session["userEmail"], $event["adminEmail"]) )
                    $xtra .= "<a  class='btn' href='#' onclick='filterType(\"projet\")' title='Rejoindre un projet'><span class='entypo-share'></span></a>";*/
                    
                
                //join Btn on project panel
                if(isset($type) && $type=='projet'){
                    /*if(in_array( Yii::app()->session["userEmail"], $event["adminEmail"]))
                        $xtra .= "<a class='btn-ph' href='javascript:userJoinProject(\"".$project."\")' title='Rejoindre ce projet'><span class='entypo-share'></span></a>";*/
                    array_push($projects, $project );
                }else if(isset($type) && $type=='coach'){
                    $coachRequestBadge =  '<span id="'.(str_replace(' ', '', $name)).'RequestBadge" class="badge bgRed coachBadges" ></span>';
                    $xtra .= "<a class='btn-ph' href='#coaching' onclick='$(\"#coachRequested\").select2(\"val\",\"".(str_replace(' ', '', $name))."\")'  role='button' data-toggle='modal' title='Appeler ce coach'><span class='entypo-megaphone'></span></a>".$coachRequestBadge;
                }
                $xtra .= '</div>';
                
                $img = (!empty($img) ) ? Yii::app()->createUrl('upload/swe/'.$img) : Yii::app()->createUrl('images/PHOTO_ANONYMOUS.png'); 
                
                if(!empty($name) && isset($type))
                {
                    $names = explode(" ", $name);
                    $strNames = "";
                    if( count($names) > 2 )
                        $strNames = $names[0]."<br/>".str_replace($names[0], '', $name);
                    else
                        $strNames = str_replace( ' ', '<br/>', $name );
    
                    $coachClass = "";
                    if(isset($type) && $type=='coach'){
                        $coaches[str_replace(' ', '', $name)] = $name;
                        $coachClass = str_replace(' ', '', $name);
                    }     
                    echo '<li class="'.$type.' '.$classDesc.' hide '.$classProjet.' '.$classMe.' '.$coachClass.'">'.
                    		'<div class="thumb">
                    			<img src="'.$img.'"/>
                    		</div>
                    		<div class="infos">
                    			<div class="type">'.((isset($type)) ?$type:"coco").'</div>
                    			<br/>
                    			<div class="name">'.$strNames.'</div>'.
                                $xtra.'
                    		</div>
                    	 </li>';
                    
            }
            }
        }?>
	</ul>
	
</div>

<?php $this->renderPartial('application.views.evenement.swe.sweSponsor');?>

<canvas id="canvas"></canvas>

<?php $this->renderPartial('application.views.evenement.swe.sweModals',array('coaches'=>$coaches,
    																		 'myproject'=>$myproject,
                                                                             'projects'=>$projects,
                                                                             'event'=>$event,
                                                                             'meType'=>$meType
                                                                            ));?>


<script type="text/javascript">
var previousDataCoach = {"count":0};
function getCoachCount(){
    $.getJSON(baseUrl+"/index.php/evenement/sweNotifications/id/<?php echo $event["_id"]?>", function(data) {
        // console.log(previousDataCoach);
        // console.log(data);
		if( previousDataCoach.count != data.count  )
		{	
			console.log("entered");
            if(data.count > 0){
            	$("#appPanel").show();
            	$("#coachingCount").html(data.count);
            }
            else {
            	$("#appPanel").hide();
            	$("#coachingCount").html('');
            }
        	var coaches = data.coaches;
        	var projects= data.projects;
        	var ids= data.ids;
        	$(".coachRequestedColor").removeClass("coachRequestedColor");
        	$(".coachBadges").html("");
        	$("#appPanelList").html("");
        	$(".coach").css("border","2px solid purple");
        	for(var ix = 0; ix < coaches.length; ix++){
            	/*console.log(coaches[ix]);
            	console.log(projects[ix]);*/
    			$("."+coaches[ix]).addClass("coachRequestedColor");//marche pas 
    			$("."+coaches[ix]).css("border","5px solid red");
    			if( $("#"+coaches[ix]+"RequestBadge").html() != "" )
    	  			$("#"+coaches[ix]+"RequestBadge").html( parseInt( $("#"+coaches[ix]+"RequestBadge").html() ) + 1 );
        	    else	  
        			$("#"+coaches[ix]+"RequestBadge").html("1");
    			var remove = "";
    			<?php if(in_array($user["type"], array('coach','organisateur')) || in_array(Yii::app()->session["userEmail"], $event["adminEmail"])) { ?>
    				remove = "<span class='entypo-squarred-cross cRed'><a href='#' onclick='sweCoachingDone(\""+ids[ix]+"\")'>&#10062;</a></span>";
    			<?php }?>
    			$("#appPanelList").append("<li><span class='entypo-megaphone cRed'></span>"+coaches[ix]+" : "+projects[ix]+" "+remove+" </li>");
        	}
		}
		previousDataCoach = data;
        setTimeout(getCoachCount,5000);
    });
}
function sweCoachingDone(id){
	NProgress.start();
	$.ajax({
	  type: "POST",
	  url: baseUrl+"/index.php/evenement/sweCoachingDone",
	  data: {"id":id},
	  success: function(data){
		  	  $("#flashInfo .modal-body").html(data.msg);
			  $("#flashInfo").modal('show');
			  NProgress.done();
	  },
	  dataType: "json"
	});
	console.log("sweCoachingDone "+id);
}
function userJoinProject(project){
	$("li.projet.me").removeClass('me');
	NProgress.start();
	$.ajax({
	  type: "POST",
	  url: baseUrl+"/index.php/evenement/sweRejoindreProjet",
	  data: {"projet":project},
	  success: function(data){
		  	  $("li.projet."+project).addClass('me');
			  $("#flashInfo .modal-body").html(data.msg);
			  $("#flashInfo").modal('show');
			  NProgress.done();
	  },
	  dataType: "json"
	});
}
function filterType(type){
	$(".appContent ul.people li").slideUp();
	$(".appContent ul.people li."+type).slideDown();
	if(type=="projet")
		$(".appTitle").html("Les projets");
	else if(type=="jury")
		$(".appTitle").html("Le jury");
	else if(type=="coach")
		$(".appTitle").html("Les Coachs");
	else if(type=="organisateur")
		$(".appTitle").html("Les organisateurs");
	else if(type=="me")
		$(".appTitle").html("Mon compte");
	else
		$(".appTitle").html("Les inscrits");
	
}

initT['sweGraphInit'] = function(){
	<?php if($myproject){?>
	$("li.projet.<?php echo $myproject?>").addClass('me');
	<?php }?>
	filterType("participant");
	//appear after loading
	$(".appContent").slideDown();
	$('#appPanel').scrollbox();
	$('#mesInfos').click(function(){filterType('me')});
	getCoachCount();
	//Code by: Kushagra Agarwal
	//http://cssdeck.com/item/602/html5-canvas-particles-web-matrix
	// RequestAnimFrame: a browser API for getting smooth animations
	window.requestAnimFrame = (function(){
	  return  window.requestAnimationFrame       || 
			  window.webkitRequestAnimationFrame || 
			  window.mozRequestAnimationFrame    || 
			  window.oRequestAnimationFrame      || 
			  window.msRequestAnimationFrame     ||  
			  function( callback ){
				window.setTimeout(callback, 1000 / 60);
			  };
	})();

	// Initializing the canvas
	// I am using native JS here, but you can use jQuery, 
	// Mootools or anything you want
	var canvas = document.getElementById("canvas");

	// Initialize the context of the canvas
	var ctx = canvas.getContext("2d");

	// Set the canvas width and height to occupy full window
	var W = window.innerWidth, H = window.innerHeight*5;
	canvas.width = W;
	canvas.height = H;

	// Some variables for later use
	var particleCount = 200,
		particles = [],
		minDist = 70,
		dist;

	// Function to paint the canvas black
	function paintCanvas() {
		// Set the fill color to black
		ctx.fillStyle = "rgba(51,153,225,1)";
		
		// This will create a rectangle of white color from the 
		// top left (0,0) to the bottom right corner (W,H)
		ctx.fillRect(0,0,W,H);
	}

	// Now the idea is to create some particles that will attract
	// each other when they come close. We will set a minimum
	// distance for it and also draw a line when they come
	// close to each other.

	// The attraction can be done by increasing their velocity as 
	// they reach closer to each other

	// Let's make a function that will act as a class for
	// our particles.

	function Particle() {
		// Position them randomly on the canvas
		// Math.random() generates a random value between 0
		// and 1 so we will need to multiply that with the
		// canvas width and height.
		this.x = Math.random() * W;
		this.y = Math.random() * H;
		
		
		// We would also need some velocity for the particles
		// so that they can move freely across the space
		this.vx = -1 + Math.random() * 2;
		this.vy = -1 + Math.random() * 2;

		// Now the radius of the particles. I want all of 
		// them to be equal in size so no Math.random() here..
		this.radius = 5;
		
		// This is the method that will draw the Particle on the
		// canvas. It is using the basic fillStyle, then we start
		// the path and after we use the `arc` function to 
		// draw our circle. The `arc` function accepts four
		// parameters in which first two depicts the position
		// of the center point of our arc as x and y coordinates.
		// The third value is for radius, then start angle, 
		// end angle and finally a boolean value which decides
		// whether the arc is to be drawn in counter clockwise or 
		// in a clockwise direction. False for clockwise.
		this.draw = function() {
			ctx.fillStyle = "#324553";
			ctx.beginPath();
			ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
			
			// Fill the color to the arc that we just created
			ctx.fill();
		}
	}

	// Time to push the particles into an array
	for(var i = 0; i < particleCount; i++) {
		particles.push(new Particle());
	}

	// Function to draw everything on the canvas that we'll use when 
	// animating the whole scene.
	function draw() {
		
		// Call the paintCanvas function here so that our canvas
		// will get re-painted in each next frame
		paintCanvas();
		
		// Call the function that will draw the balls using a loop
		for (var i = 0; i < particles.length; i++) {
			p = particles[i];
			p.draw();
		}
		
		//Finally call the update function
		update();
	}

	// Give every particle some life
	function update() {
		
		// In this function, we are first going to update every
		// particle's position according to their velocities
		for (var i = 0; i < particles.length; i++) {
			p = particles[i];
			
			// Change the velocities
			p.x += p.vx;
			p.y += p.vy
				
			// We don't want to make the particles leave the
			// area, so just change their position when they
			// touch the walls of the window
			if(p.x + p.radius > W) 
				p.x = p.radius;
			
			else if(p.x - p.radius < 0) {
				p.x = W - p.radius;
			}
			
			if(p.y + p.radius > H) 
				p.y = p.radius;
			
			else if(p.y - p.radius < 0) {
				p.y = H - p.radius;
			}
			
			// Now we need to make them attract each other
			// so first, we'll check the distance between
			// them and compare it to the minDist we have
			// already set
			
			// We will need another loop so that each
			// particle can be compared to every other particle
			// except itself
			for(var j = i + 1; j < particles.length; j++) {
				p2 = particles[j];
				distance(p, p2);
			}
		
		}
	}

	// Distance calculator between two particles
	function distance(p1, p2) {
		var dist,
			dx = p1.x - p2.x;
			dy = p1.y - p2.y;
		
		dist = Math.sqrt(dx*dx + dy*dy);
				
		// Draw the line when distance is smaller
		// then the minimum distance
		if(dist <= minDist) {
			
			// Draw the line
			ctx.beginPath();
			ctx.strokeStyle = "rgba(255,255,255,"+ (1.2-dist/minDist) +")";
			ctx.moveTo(p.x, p.y);
			ctx.lineTo(p2.x, p2.y);
			ctx.stroke();
			
			// Some acceleration for the partcles 
			// depending upon their distance
			var ax = dx/2000,
				ay = dy/2000;
			
			// Apply the acceleration on the particles
			p1.vx -= ax;
			p1.vy -= ay;
			
			p2.vx += ax;
			p2.vy += ay;
		}
	}

	// Start the main animation loop using requestAnimFrame
	function animloop() {
		draw();
		requestAnimFrame(animloop);
	}

	animloop();
};

</script>	

