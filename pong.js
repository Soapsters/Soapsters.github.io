const canvas = document.getElementById("pong");
const ctx = canvas.getContext('2d');

// draw a rectangle, will be used to draw paddles
function drawRect(x, y, w, h, color){
    ctx.fillStyle = color;
    ctx.fillRect(x, y, w, h);
}

// draw circle, will be used to draw the ball
function drawArc(x, y, r, color){
    ctx.fillStyle = color;
    ctx.beginPath();
    ctx.arc(x,y,r,0,Math.PI*2,true);
    ctx.stroke();
    ctx.fill();
}

// put ball in the middle of the canvas
const ball = {
    x : canvas.width/2,
    y : canvas.height/2,
    radius : 10,
    velocityX : 5,
    velocityY : 5,
    speed : 7,
    color : "WHITE",
}

// User Paddle
const user = {
    x : 0, 
    y : (canvas.height - 100)/2, 
    width : 10,
    height : 100,
    score : 0,
    color : "YELLOW"
}

// COM Paddle
const com = {
    x : canvas.width - 10,
    y : (canvas.height - 100)/2, 
    width : 10,
    height : 100,
    score : 0,
    color : "CYAN"
}

// NET
const net = {
    x : (canvas.width - 2)/2,
    y : 0,
    height : 20,
    width : 2,
    color : "WHITE"
}

// listening to the mouse
canvas.addEventListener("mousemove", getPosition);
function getPosition(evt){
    let rect = canvas.getBoundingClientRect();
    user.y = evt.clientY - rect.top - user.height/2;
}

function resetBall(){
    ball.x = canvas.width/2;
    ball.y = canvas.height/2;
    ball.velocityX = -ball.velocityX;
    ball.speed = 7;
}

// draw the net
function drawNet(){
    for(let i = 0; i <= canvas.height; i+=15){
        drawRect(net.x, net.y + i, net.width, net.height, net.color);
    }
}

// draw text
function drawText(text,x,y){
    ctx.fillStyle = "#FFF";
    ctx.font = "75px VT323";
    ctx.fillText(text, x, y);
}

// collision detection
function collision(b,p){
    p.top = p.y;
    p.bottom = p.y + p.height;
    p.left = p.x;
    p.right = p.x + p.width;
    
    b.top = b.y - b.radius;
    b.bottom = b.y + b.radius;
    b.left = b.x - b.radius;
    b.right = b.x + b.radius;
    
    return p.left < b.right && p.top < b.bottom && p.right > b.left && p.bottom > b.top;
}

// score
function update(){
    if( ball.x - ball.radius < 0 ){
        com.score++;
        resetBall();
    }else if( ball.x + ball.radius > canvas.width){
        user.score++;
        resetBall();
    }

    //give the ball a velocity
    ball.x += ball.velocityX;
    ball.y += ball.velocityY;
    
    // paddle follow ball
    com.y += ((ball.y - (com.y + com.height/2)))*0.1;
    
    // inverse y
    if(ball.y - ball.radius < 0 || ball.y + ball.radius > canvas.height){
        ball.velocityY = -ball.velocityY;
    }
    
    // check paddle hit
    let player = (ball.x + ball.radius < canvas.width/2) ? user : com;
    
    if(collision(ball,player)){
        // we check where the ball hits the paddle
        let collidePoint = (ball.y - (player.y + player.height/2));

        collidePoint = collidePoint / (player.height/2);
        
        // degrees of the ball based off of the collide point
        let angleRad = (Math.PI/4) * collidePoint;
        
        // to change the X and the Y direction
        let direction = (ball.x + ball.radius < canvas.width/2) ? 1 : -1;
        ball.velocityX = direction * ball.speed * Math.cos(angleRad);
        ball.velocityY = ball.speed * Math.sin(angleRad);
        
        // speed up the ball everytime a paddle hits it.
        ball.speed += 0.1;
    }
}

//draw the paddles, ball, and user's scores for animation
function drawEverything(){
    drawRect(0, 0, canvas.width, canvas.height, "#000");
    drawText(user.score,canvas.width/4,canvas.height/5, "75px VT323");
    drawText(com.score,3*canvas.width/4,canvas.height/5, "75px VT323");
    drawNet();
    drawRect(user.x, user.y, user.width, user.height, user.color);
    drawRect(com.x, com.y, com.width, com.height, com.color);
    drawArc(ball.x, ball.y, ball.radius, ball.color);
}

drawText()

function game(){
    drawEverything();
    update();
}

document.body.onkeyup = function(e){
    if(e.keyCode == 32){
        let frames = 50;
        let loop = setInterval(game,1000/frames);
    }
}

