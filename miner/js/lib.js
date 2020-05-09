const DEFAULT = 0;
const FLAG = 1;
const QUESTION = 2;
const CLASS_NOT_VISIBLE = "cell notVisible";
const CLASS_BOMB = "cell bomb";
const CLASS_EXPLODED_BOMB = "cell explodedBomb";

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
		defaultImageUrl = "url('/miner/images/notVisible128.gif')";
		if (cellView == null || cellView == DEFAULT) {
			item.style = "";
		} else if (cellView == FLAG) {
			item.style = "background-image: url('/miner/images/flagTransp128.gif')," + defaultImageUrl;
		} else if (cellView == QUESTION) {
			item.style = "background-image: url('/miner/images/questionTransp128.gif')," + defaultImageUrl;
		}
	}
}
function leftClickCell(item)
{
	if (item.className == CLASS_NOT_VISIBLE) {
		cellView = localStorage.getItem(item.id);
		if (cellView == null || cellView == DEFAULT) {
			document.forms["formClickField"].coord.value = "test" + item.id;
			document.forms["formClickField"].submit();
		} else if (cellView == QUESTION) {
			document.forms["formClickField"].coord.value = "help" + item.id;
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
			document.forms["formRemainingBombs"].bombs.value = 
				+document.forms["formRemainingBombs"].bombs.value - 1;			
		} else if (cellView == FLAG) {
			localStorage.setItem(item.id, QUESTION);
		} else if (cellView == QUESTION) {
			localStorage.setItem(item.id, DEFAULT);
			document.forms["formRemainingBombs"].bombs.value = 
				+document.forms["formRemainingBombs"].bombs.value + 1;			
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
	let settings = {
		width: document.forms["formSettings"].width.value,
		height: document.forms["formSettings"].height.value,
		numberBombs: document.forms["formSettings"].numberBombs.value
	}
	if (
		settings.width !== "" &&
		settings.height !== "" &&
		settings.numberBombs !== ""
	) {	
		localStorage.setItem("settings", JSON.stringify(settings));
	}
}
function loadSettings()
{
	settings = JSON.parse(localStorage.getItem("settings"));
	if (
		settings !== null &&
		settings.hasOwnProperty("width") &&
		settings.hasOwnProperty("height") &&
		settings.hasOwnProperty("numberBombs")
	) {
		document.forms["formSettings"].width.value = settings.width;
		document.forms["formSettings"].height.value = settings.height;
		document.forms["formSettings"].numberBombs.value = settings.numberBombs;
		return settings;
	}
}