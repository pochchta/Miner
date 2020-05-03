const DEFAULT = 0;
const FLAG = 1;
const QUESTION = 2;
const CLASS_NOT_VISIBLE = "cell notVisible";

function showImageCell(elements)
{
	for (var i = 0; i < elements.length; i++) {
		item = elements[i];
		cellView = localStorage.getItem(item.id);
		if (item.className != CLASS_NOT_VISIBLE) {
			if (cellView != null && cellView != DEFAULT) {
				localStorage.setItem(item.id, DEFAULT);
			}
		}
		cellView = localStorage.getItem(item.id);
		if (cellView == null || cellView == DEFAULT) {
			item.style = "";
		} else if (cellView == FLAG) {
			item.style = "background-image: url('/miner/images/flagTransp128.gif'),";
			item.style .= " url('/miner/images/notVisible128.gif')";
		}
	}
}
function leftClickCell(item)
{
	if (item.className == CLASS_NOT_VISIBLE) {
		cellView = localStorage.getItem(item.id);
		if (cellView == null || cellView == DEFAULT) {
			document.forms["formClickField"].coord.value = item.id;
			document.forms["formClickField"].submit();
		}
	}		
}
function rightClickCell(item)
{
	if (item.className == CLASS_NOT_VISIBLE) {
		cellView = localStorage.getItem(item.id);
		if (cellView == null || cellView == DEFAULT) {
			localStorage.setItem(item.id, FLAG);
		} else if (cellView == FLAG) {
			localStorage.setItem(item.id, DEFAULT);
		}
		showImageCell([item]);
	}
}
function clearImageField()
{
	localStorage.clear();
}
function saveSettings()
{
	let arr = new Map([
		['width', document.forms["formSettings"].width.value],
		['height', document.forms["formSettings"].height.value],
		['numberBombs', document.forms["formSettings"].numberBombs.value]
	]);

	localStorage.setItem(array, JSON.stringify(arr));
	// array = JSON.parse(localStorage.getItem("array"));
}