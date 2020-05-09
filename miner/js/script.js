let elements = document.querySelectorAll(".cell");
showImageCell(elements);
let counterBombCell = 0;
for (var i = 0; i < elements.length; i++) {
	elements[i].onclick = function() {
		leftClickCell(this);
	}
	elements[i].oncontextmenu = function() {
		rightClickCell(this);
		return false;
	}
	if (
		elements[i].className == CLASS_BOMB ||
		elements[i].className == CLASS_EXPLODED_BOMB
	) counterBombCell++;
}
document.forms["formRemainingBombs"].bombs.value = 
	+document.forms["formRemainingBombs"].bombs.value - counterBombCell;