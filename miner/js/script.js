let elements = document.querySelectorAll(".cell");
showImageCell(elements);
let counterBombCell = 0, 
counterVisibleCell = 0,
counterNotVisibleCell = 0;
for (var i = 0; i < elements.length; i++) {
	elements[i].onclick = function() {
		leftClickCell(this);
	}
	elements[i].oncontextmenu = function() {
		rightClickCell(this);
		return false;
	}
	cellView = localStorage.getItem(elements[i].id);
	if (
		elements[i].className == CLASS_BOMB ||
		elements[i].className == CLASS_EXPLODED_BOMB ||
		(cellView != null && cellView != DEFAULT)
	) {
		counterBombCell++;
	}
	if (elements[i].className == CLASS_NOT_VISIBLE) {
		counterNotVisibleCell++;
	} else {
		counterVisibleCell++;
	}
}
if ((counterVisibleCell && counterNotVisibleCell) != false) {
	setInterval(incTimer, 1000);
}
idCounterBomb.innerHTML = formatNumber(+idCounterBomb.innerHTML - counterBombCell, 3);
idTimer.innerHTML = formatNumber(idTimer.innerHTML, 3);
// digitalBlock.style = "opacity: 1";

let newGame = document.querySelectorAll(".newGame");
newGame[0].onclick = function() {
	document.forms["formClickField"].newGame.value = 'newGame';
	document.forms["formClickField"].submit();
	clearImageField();
}

if (level != 0) setLevel(level);
setButtonView(level);


document.body.style = "opacity: 1";
