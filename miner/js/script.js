let 
intervalIdGlob,
startTimeGlob,
endTimeGlob,
countRemainingBombGlob;

dataStateProcessing(JSON.parse(jsonStateGame));

let elements = document.querySelectorAll(".cell");
showImageCell(elements);

for (var i = 0; i < elements.length; i++) {
	elements[i].onclick = function() {
		leftClickCell(this);
	}
	elements[i].oncontextmenu = function() {
		rightClickCell(this);
		return false;
	}
}

let newGame = document.querySelectorAll(".newGame");
newGame[0].onclick = function() {
	document.forms["formClickField"].newGame.value = 'newGame';
	document.forms["formClickField"].submit();
	clearImageField();
}

if (level != 0) setLevel(level);
setButtonView(level);

document.body.style = "opacity: 1";
