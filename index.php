<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="user-scalable=0"/>
	<title>Memory Snake</title>

	<script src='scripts/jquery.min.js'></script>
	<script src="js/underscore-min.js"></script>


	<script src="js/sweetalert.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/sweetalert.css" />

	<style type="text/css">
		img{
	    	-khtml-user-select: none;
	    	-o-user-select: none;
	    	-moz-user-select: none;
	    	-webkit-user-select: none;
	    	user-select: none;
		}
	</style>
	<script>
       
  

</script>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<span style="float: left;"><a href="/memorySnake" style="text-decoration: none; color: black;"><b style="font-size: 20px;">MemorySnake!<b></a></span>

<span style="float:left; margin-left: 800px;"><a href="#" onclick="alert(msj+'The classic game of Snake, but if you get two times the same number on the level, the level is over.\nKeys: Arrows or wasd.\nMax level: 99.\nClick on level number to change level.\n\ncontact: robertchalean@gmail.com')" style="font-size: 20px;">Help</a></span>
<div id="debug"></div>
<canvas id="myCanvas" width="1020" height="860" style=""></canvas>
<br>
<center>
<div id="controles" style="margin-top: 50px">
	<img src="img/teclas.png" alt="Planets" usemap="#planetmap" onclick="console.log('ok');" id="teclas">
	

<map name="planetmap">
  <area shape="rect" coords="200,21,372,200" alt="up" onclick="move(38);">
  <area shape="rect" coords="207,222,373,389" alt="down" onclick="move(40);">
  <area shape="rect" coords="7,227,172,395" alt="left" onclick="move(37);">
  <area shape="rect" coords="403,225,574,395" alt="right" onclick="move(39);">
  <area shape="rect" coords="0,0,575,400" alt="up" onclick="">
 
</map>
</div>
</center>
<br>
<div style="font-size: 16px;">
randomColor <input type="checkbox" id="randomColor">
maxSnakeSize <input type="number" value="15" id="maxSnakeSize" style="width: 50px;">
maxNumbersOnScreen <input type="number" value="10" id="maxNumbers" style="width: 50px;">
repetitionRate <input type="number" value="30" id="repetitionRate" style="width: 50px;"> %
&nbsp;Level <a href="#" id="level-span" onclick="changeLevel();" style="color: black;"></a>
&nbsp;&nbsp;<div class="fb-share-button" data-href="http://competicionmental.appspot.com/memorySnake" data-layout="button_count"></div>
</div>

<script type="text/javascript">

function n(x){ return parseInt($("#"+x).val()); }

var winWidth, winHeight;

winHeight = $(window).height(); // New height
winWidth =  $(window).width();

console.log(winWidth+"x"+winHeight);

tableroWidth = 0;
tableroHeight = 0;
cuadroX=25;
timerDelay=100;

//if(winHeight>500){
	tableroHeight = parseInt((winHeight-100) / 25);
//}

msj="";

if(winWidth>=1000){
	

	tableroWidth=40;

	//$("#myCanvas").css("width",((tableroWidth)*cuadroX)+"px");
	//$("#myCanvas").css("height",((tableroHeight)*cuadroX)+"px");

}else{
	msj="Mobile experience are in developing. It can be still unsatisfactory.\n";
	cuadroX=30;	
	tableroWidth=parseInt(winWidth/cuadroX);

	cuadroX=30;	
	tableroHeight=parseInt((winHeight/2)/cuadroX);
	//console.log(tableroHeight);

	timerDelay=125;
	//console.log(tableroHeight);
	
	/*
	console.log("tableroWidth");
	console.log(tableroWidth);
	console.log("win width");
	console.log(winWidth);
	*/

}

c=document.getElementById('myCanvas');
c.height=tableroHeight * cuadroX;
c.width=tableroWidth * cuadroX;


//console.log(winWidth+"x"+winHeight);

killInterval=0, gameMatrix=[];

//var c = document.getElementById("myCanvas");
var ctx = c.getContext("2d");

var _x=0; var _y=0;

var direccion=1;
var arraySnake=[];
var auxArraySnake=[]
var arrayNumbers=[];
var arrayAllNumbers=[];
var level;
var largoSnake=3;

/*
level=parseInt(prompt(msj+"Please enter your next level", 10)); 
initGameMatrix();
initSnake();
*/



$(document).ready(function(){
	swal(
		{title: "MemorySnake!",  imageUrl : "img/snake.png", text: msj+"Please enter your next level",   type: "input",   showCancelButton: false,   closeOnConfirm: true,   animation: "slide-from-top",  inputValue: "10",  inputPlaceholder: "10" }, 
	function(inputValue){  
		//alert(inputValue);
		level=parseInt(inputValue);
		console.log("level: "+ level);
		//alert(level);
		initGameMatrix();
		initSnake();		
		//gameMatrix[0][0][0]=1;
		//dibujaMatrix();
	});
});

function initSnake(){
	clearInterval(killInterval); clearInterval(killGame);

	if (level==false)  {
		level=10;
	
	}
	if(level>99){
		level=99;
	}
	

	$("#level-span").html("<b>"+level+"</b>");

	console.log(level);

	_x=0; _y=0;
	direccion=1;
	arraySnake=[];
	auxArraySnake=[]
	arrayNumbers=[];
	largoSnake=3;
	arrayAllNumbers=[];

	for(i=0;i<100;i++){

		arraySnake[i]={};
		arraySnake[i].myX=0;
		arraySnake[i].myY=0;
		arraySnake[i].myDireccion=0;
		arraySnake[i].myActivo=0;
		arraySnake[i].myNum=0;

	}

	arraySnake[0].myX=3; arraySnake[0].myY=0; arraySnake[0].myDireccion=1; arraySnake[0].myActivo=1; arraySnake[0].myNum=0;
	arraySnake[1].myX=2; arraySnake[1].myY=0; arraySnake[1].myDireccion=1; arraySnake[1].myActivo=1; arraySnake[1].myNum=0;
	arraySnake[2].myX=1; arraySnake[2].myY=0; arraySnake[2].myDireccion=1; arraySnake[2].myActivo=1; arraySnake[2].myNum=0;
	arraySnake[3].myX=0; arraySnake[3].myY=0; arraySnake[3].myDireccion=1; arraySnake[3].myActivo=1; arraySnake[3].myNum=0;

	arrayAllNumbers=_.range(1,level+1);


	indexNumbers=0;

	gameMatrix[0][0][0]=1;
	dibujaMatrix();
	
	initNumbers();
	gameBucle();
	
}

function initNumbers(){

	killInterval=setInterval(function(){

	xxx=0;
	while (true) {
		xxx++;
		i=_.random(0,tableroHeight-1);
		j=_.random(0,tableroWidth-1);
		//right
		if(direccion==1){
			if( j<arraySnake[0].myX && gameMatrix[i][j][0]==0){
				
				break;
			}
		}
		//left
		if(direccion==0){
			if( j>arraySnake[0].myX && gameMatrix[i][j][0]==0){
				
				break;
			}
		}
		//up
		if(direccion==2){
			if(i<arraySnake[0].myY  && gameMatrix[i][j][0]==0){
				
				break;
			}
		}
		//down
		if(direccion==2){
			if(i>arraySnake[0].myY  && gameMatrix[i][j][0]==0){
				
				break;
			}
		}
		if(xxx==1000){
			
			break;
		}
   
	}
	myRnd1=_.random(1,100);
	if(indexNumbers!=0 && (myRnd1<=n("repetitionRate") || arrayAllNumbers.length==0)){
		myRnd=arrayNumbers[_.random(0,arrayNumbers.length-1)];
		console.log("repetido");
		
	}else{
			arrayDifference = _.difference(arrayAllNumbers,arrayNumbers);
			/*
			console.log("Difference");
			console.log(arrayAllNumbers);
			console.log(arrayNumbers);
			console.log(arrayDifference);
			*/
			myRnd=arrayDifference[_.random(0,arrayDifference.length-1)];


		//arrayAllNumbers = _.without(arrayAllNumbers, myRnd);
		//console.log(arrayAllNumbers);		
	}


	//Previene que no haya demaciados números

	_aUsados = [];
	z=0;
	cantidad=0;

	for(k=0;k<tableroHeight;k++){
		for(l=0;l<tableroWidth;l++){
			if(gameMatrix[k][l][1]!=0){

				_aUsados[z] = {};
				_aUsados[z].i = k;
				_aUsados[z].j = l;

				cantidad++; z++;
			} //if
		} //for l
	}//for k
	if(cantidad>n("maxNumbers")){
		rnd = _.random(0,_aUsados.length-1);
		i = _aUsados[rnd].i;
		j = _aUsados[rnd].j;
	}
	
	gameMatrix[i][j][1]=myRnd;
	
},3000);


}



var  killGame, timer=0;

function gameBucle(){
	killGame=setInterval(function(){
		timer++;
		if(timer==20){
			//clearInterval(killGame);

		}
		bucleGame();

		ctx.fillStyle = "#000000";
		//ctx.fillRect(x,y,15,15);
		ctx.rect(0,0,tableroWidth*cuadroX,tableroHeight*cuadroX);
		ctx.stroke();

	},timerDelay);
}

var prevDir=-4;
$(document).keydown(function(e) {
	prevDir=direccion;

	//console.log(e.which);
	

    switch(e.which) {

        case 37: // left
        	direccion=0;
        	
        break;

         case 65: // left
        	direccion=0;
        	
        break;

        case 38: // up
        	direccion=2;
        break;

        case 87: // up
        	direccion=2;
        break;

        case 39: // right
        	direccion=1;
        break;

        case 68: // right
        	direccion=1;
        break;

        case 40: // down
        	direccion=3;
        	
        break;

        case 83: // down
        	direccion=3;
        	
        break;

        default: return; // exit this handler for other keys


    }
	bRestart=0
	if(prevDir==0 && direccion==1)
		bRestart=1
	if(prevDir==1 && direccion==0)
		bRestart=1
	if(prevDir==3 && direccion==2)
		bRestart=1
	if(prevDir==2 && direccion==3)
		bRestart=1

	if(bRestart){
		direccion=prevDir;
		//alert("restart");
		//initGameMatrix();
		//initSnake();
		return;
	}

    e.preventDefault(); // prevent the default action (scroll / move caret)
});

var _ax, _ay, _x, _y;
var indexNumbers=0;

function bucleGame(){
	_x = arraySnake[0].myX;
	_y = arraySnake[0].myY;

	_ax=_x;
	_ay=_y;

	//if(_ax==20) return;

	if(_x-1>=-1 && direccion==0)
	    	_x-=1;
	if(_y-1>=-1 && direccion==2)
	 	_y-=1;
	
	if(_x+1<52 && direccion==1)
	    _x+=1;
	
	if(_y+1<44 && direccion==3)
	    _y+=1;
	/*

	if(_x==51){
		alert("salio del mapa");
		initGameMatrix();
		initSnake();
		
		return;
	}*/
	//if(_x==tableroWidth+1 || _x==-1 || _y==-1 || _y==tableroHeight+1){
		//alert("Out of map");
		if(_x==tableroWidth){
			_x=0;
		}
		if(_x==-1){
			_x=tableroWidth-1;
		}
		if(_y==tableroHeight){
			_y=0;
		}
		if(_y==-1){
			_y=tableroHeight-1;
		}

		/*
		initGameMatrix();
		initSnake();
		
		return;*/
	//}

	
	if(gameMatrix[_y][_x][0]==1){
		//alert("restart");

		clearInterval(killInterval); clearInterval(killGame);

		swal(
		{title: "MemorySnake!", imageUrl : "img/snake.png",  text: "Please enter your next level",   type: "input",   showCancelButton: false,   closeOnConfirm: true,   animation: "slide-from-top",  inputValue: level, inputPlaceholder: "10" }, 
			function(inputValue){  
				//alert(inputValue);
				level=parseInt(inputValue);
				//alert(level);
				initGameMatrix();
				initSnake();		
				//gameMatrix[0][0][0]=1;
				//dibujaMatrix();
		});


		/*
		initGameMatrix();
		level=parseInt(prompt("Please enter your next level", level));
		initSnake();
		*/

		return;

	}
	
    // if(_x!=_ax || _y!=_ay){
    		/*
    		whiteScreen();
    		createSquare("#FF0000",_x,_y);
    		*/
    		//
    		noAchicar = 0;
    		for(i=0;i<arraySnake.length;i++){
    			
    			/*
    			if(arraySnake[i].myActivo==0){
    				break;
    				
    			}*/
    			
	    		if(i==0){
	    			aux=[];
	    			//aux=arraySnake.slice(0);
	    			aux=JSON.parse(JSON.stringify(arraySnake));
	    			//console.log(aux[0]);

	    			arraySnake[0].myX=_x;
	    			arraySnake[0].myY=_y;

	    			//console.log(_x+"-"+_y);
	    			//console.log(aux[0]);
	    			//console.log("-----");

	    			if(gameMatrix[_y][_x][1]!=0){
	    				if(arrayNumbers.indexOf(gameMatrix[_y][_x][1])!=-1){

	    					//alert("Number repeated: "+gameMatrix[_y][_x][1]+"->"+arrayNumbers);
	    					/*
	    					level = parseInt(prompt("Number repeated: "+gameMatrix[_y][_x][1]+"->"+arrayNumbers+"\nPlease enter your next level", parseInt(level)));
	    					initGameMatrix();
							initSnake();
							*/

							clearInterval(killInterval); clearInterval(killGame);

							swal(
							{title: "MemorySnake!", imageUrl : "img/snake.png",  text: "Number repeated: "+gameMatrix[_y][_x][1]+"->"+arrayNumbers+"\nPlease enter your next level",   type: "input",   showCancelButton: false,   closeOnConfirm: true,   animation: "slide-from-top",  inputValue: level, inputPlaceholder: level }, 
								function(inputValue){  
									//alert(inputValue);
									level=parseInt(inputValue);
									//alert(level);
									initGameMatrix();
									initSnake();		
									//gameMatrix[0][0][0]=1;
									//dibujaMatrix();
							});
										
							return;

	    				}else{
	    					arrayNumbers[indexNumbers]=gameMatrix[_y][_x][1];
			    			gameMatrix[_y][_x][1]=0;
			    			indexNumbers++;
			    			//console.log(arrayNumbers);
			    			

			    			//!importante
			    			if(largoSnake<n("maxSnakeSize")){
			    				largoSnake++;
		    					noAchicar=1;

			    			}
			    			
		    				console.log(arrayNumbers);

		    				if(arrayNumbers.length==level){

		    					clearInterval(killInterval); clearInterval(killGame);

								swal(
								{title: "MemorySnake!", imageUrl : "img/snake.png",  text: "Please enter your next level",   type: "input",   showCancelButton: false,   closeOnConfirm: true,   animation: "slide-from-top",  inputValue: parseInt(level)+1, inputPlaceholder: parseInt(level)+1 }, 
									function(inputValue){  
										//alert(inputValue);
										level=parseInt(inputValue);
										//alert(level);
										initGameMatrix();
										initSnake();		
										//gameMatrix[0][0][0]=1;
										//dibujaMatrix();
								});

								/*	
		    					level = parseInt(prompt("Please enter your next level", parseInt(level)+1));
		    					initGameMatrix();

								initSnake();											
								*/
								return;
		    				}
	    				}	
	    			}//gameMatrix[_y][_x][1]!=0


	    			//return;
    			}else{ //i==0
    				if(arraySnake[i].myActivo){
    					arraySnake[i]=aux[i-1];
    					//console.log(i);
    					//console.log(arraySnake[i]);
    					//console.log(aux[i-1]);
    					
    				}else{
    					if(noAchicar){
    						arraySnake[i]=aux[i-1];
    						gameMatrix[arraySnake[i].myY][arraySnake[i].myX][0]=1;
    						break;
    					}else{
    						gameMatrix[aux[i-1].myY][aux[i-1].myX][0]=0;
    						break;


    					}
    					
    					
    				}
    			} // i==0

    			gameMatrix[arraySnake[i].myY][arraySnake[i].myX][0]=1;

    			/*
    			if(arraySnake[i].myActivo){
    				
    			}
    			*/
    			//gameMatrix[_y][_x][0]=1;
    			
    		}

    		/*
    		for(j=0;j<aux.length;j++){
    			console.log(aux);
			if(aux[j+1].myActivo==0){

				gameMatrix[aux[j].myY][aux[j].myX][0]=0;
				console.log(j);
				break;
			}

		}
		*/

    		//gameMatrix[_ay][_ax][0]=0;
    		//gameMatrix[_y][_x][0]=1;
    		/*	
    		if(gameMatrix[_y][_x][1]!=0){
    			arrayNumbers[indexNumbers]=gameMatrix[_y][_x][1];
    			gameMatrix[_y][_x][1]=0;
    			indexNumbers++;
    			console.log(arrayNumbers);
    			auxArraySnake[0]={myX: _x, myY: _y, myActivo: 1, myDireccion: direccion };
    			auxArraySnake = auxArraySnake.concat(arraySnake);
    			arraySnake = auxArraySnake.slice();
    			console.log(arraySnake);
    		}
    		*/
   // }

    dibujaMatrix();
}



function dibujaMatrix(){
	
	for(i=0;i<tableroHeight+1;i++){

		for(j=0;j<tableroWidth+1;j++){

			if(gameMatrix[i][j][1]==0){


			}else{
				//console.log(gameMatrix[i][j][1]);
				createSquare("#FFFFFF",j*cuadroX,i*cuadroX);
				text(gameMatrix[i][j][1]+"",j*cuadroX,i*cuadroX);
			}

			if(gameMatrix[i][j][0]==1)
				createSquare("#FF0000",j*cuadroX,i*cuadroX);
			else{
				if(gameMatrix[i][j][1]==0){
					createSquare("#FFFFFF",j*cuadroX,i*cuadroX);

					//text("1",j*20,i*20);
				}else{
					//createSquare("#00FF00",j*20,i*20);

				}
			}
				
		}//for j
	}//for i
}//dibujaMatrix()


function initGameMatrix(){
	for(i=0;i<tableroHeight+1;i++){

		gameMatrix[i]=[];

		for(j=0;j<tableroWidth+1;j++){
			gameMatrix[i][j]=[];
			gameMatrix[i][j][0]=0;
			gameMatrix[i][j][1]=0;
		}
	}
}

function whiteScreen(){
	ctx.fillStyle = "#FFFFFF";
	ctx.fillRect(0,0,tableroWidth*cuadroX,tableroHeight*cuadroX);
}

function createSquare(color,x,y){
	if ($('#randomColor').is(':checked')){
		if(color=="#FF0000")
			color = getRandomColor();
	}
	ctx.fillStyle = color;
	ctx.fillRect(x,y,cuadroX,cuadroX);
}


function createSquare2(color,x,y){
	ctx.fillStyle = color;
	//ctx.fillRect(x,y,15,15);
	ctx.rect(x,y,cuadroX,cuadroX);
	ctx.stroke();
}


tam_letra=15;

function text(txt,xx,yy){

	//console.log(txt,xx,yy);
	ctx.fillStyle = "#0000FF";
	if ($('#randomColor').is(':checked')){
		ctx.fillStyle = getRandomColor();
	}
	ctx.font = tam_letra+"px Arial";
	ctx.fillText(txt,xx+5,yy+12);
}


ctx.fillStyle = "#000000";
//ctx.fillRect(x,y,15,15);
ctx.rect(0,0,tableroWidth*cuadroX,tableroHeight*cuadroX);
ctx.stroke();

winWidth =  $(window).width();



if(winWidth<1000){
	//alert("The App was designed to use in Desktop Pc. It can have speed issues.\n La App fue pensada para usar en Pc de Escritorio. En celulares puede tener problemas de velocidad");
	$("#controles").show();
	tam_letra="22";

	
}else{

	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 		$("#controles").show();
 		$("#controles").css("margin-left","100px");
 		$("#teclas").css("zoom","120%");

 		//$("#myCanvas").css("width",((tableroWidth)*cuadroX)+"px");
	  //$("#myCanvas").css("height",( ( (tableroHeight) *cuadroX) - 100 )+"px");
	 
	}else{
		$("#controles").hide();
		$("#controles").css("zoom","");

	}
}

function move(e) {
	prevDir=direccion;
	

    switch(e) {

        case 37: // left

        	direccion=0;
        	
        break;

        case 38: // up
        	direccion=2;

        break;

        case 39: // right
        	direccion=1;
        	
        break;

        case 40: // down
        	direccion=3;
        	
        break;

        default: return; // exit this handler for other keys


    }
	bRestart=0
	if(prevDir==0 && direccion==1)
		bRestart=1
	if(prevDir==1 && direccion==0)
		bRestart=1
	if(prevDir==3 && direccion==2)
		bRestart=1
	if(prevDir==2 && direccion==3)
		bRestart=1

	if(bRestart){
		direccion=prevDir;
		//alert("restart");
		//initGameMatrix();
		//initSnake();
		return;
	}

  // prevent the default action (scroll / move caret)
}

function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function changeLevel(){
	clearInterval(killInterval); clearInterval(killGame);

	swal(
	{title: "MemorySnake!", imageUrl : "img/snake.png",  text: "Please enter your next level",   type: "input",   showCancelButton: false,   closeOnConfirm: true,   animation: "slide-from-top",  inputValue: level, inputPlaceholder: level }, 
		function(inputValue){  
			//alert(inputValue);
			level=parseInt(inputValue);
			//alert(level);
			initGameMatrix();
			initSnake();		
			//gameMatrix[0][0][0]=1;
			//dibujaMatrix();
	});
}

</script>
</body>
</html>