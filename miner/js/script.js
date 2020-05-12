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
	cellView = localStorage.getItem(elements[i].id);
	if (
		elements[i].className == CLASS_BOMB ||
		elements[i].className == CLASS_EXPLODED_BOMB ||
		(cellView != null && cellView != DEFAULT)
	) counterBombCell++;
}
idCounterBomb.innerHTML = formatNumber(+idCounterBomb.innerHTML - counterBombCell, 3);
idCounterBomb.style = "opacity: 1";
idTimer.innerHTML = formatNumber(idTimer.innerHTML, 3);
idTimer.style = "opacity: 1";
setInterval(incTimer, 1000);