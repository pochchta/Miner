const DEFAULT = 0;
const FLAG = 1;
const QUESTION = 2;
const CLASS_NOT_VISIBLE = "cell notVisible";
const CLASS_BOMB = "cell bomb";
const CLASS_EXPLODED_BOMB = "cell explodedBomb";

function sendCellCommand(request, funcProcessing)
{
	fetch('http://miner/', {
		method: 'post',
		headers: {
			"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		credentials: 'same-origin',
		body: request
	})
	.then(
		function(response) {
			if (response.status !== 200) {
				console.log('Looks like there was a problem. Status Code: ' + response.status);
				return;
			}
			response.json().then(funcProcessing);
		})
	.catch(function(err) {
		console.log('Fetch Error :-S', err);
	});
}

function dataFieldProcessing(data)
{
	if (Array.isArray(data)) {
		dataTimeProcessing(data);
		for (var i = 0; i < data.length; i++) {
			if (elemCell = document.getElementById(data[i]['id'])){
				elemCell.innerHTML = data[i].value;
				elemCell.className = data[i].class;
				showImageCell([elemCell]);
			}
		}
	}
}

function dataTimeProcessing(data)
{
	if (Array.isArray(data)) {
		elemTimer = document.getElementById('idTimer');
		elemTimer.innerHTML = formatNumber(data[0]['timeGame']);
		if (data[0]['startGame'] === true && data[0]['endGame'] === false) {
			if (typeof intervalIdGlob == 'undefined') {
				intervalIdGlob = setInterval(incTimer, 1000);
			}
		} else {
			clearInterval(intervalIdGlob);
		}
	}
}

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
			sendCellCommand("coord=test" + item.id, dataFieldProcessing);
		} else if (cellView == QUESTION) {
			sendCellCommand("coord=help" + item.id, dataFieldProcessing);
		}
	}		
}
function rightClickCell(item)
{
	if (item.className == CLASS_NOT_VISIBLE) {
		cellView = localStorage.getItem(item.id);
		if (cellView == null || cellView == DEFAULT) {
			localStorage.setItem(item.id, FLAG);
			idCounterBomb.innerHTML = formatNumber(+idCounterBomb.innerHTML - 1, 3);
		} else if (cellView == FLAG) {
			localStorage.setItem(item.id, QUESTION);
		} else if (cellView == QUESTION) {
			localStorage.setItem(item.id, DEFAULT);
			idCounterBomb.innerHTML = formatNumber(+idCounterBomb.innerHTML + 1, 3);
		}
		showImageCell([item]);
	}
}
function clearImageField()
{
	localStorage.clear();
}
function formatNumber(number, numberDigits)
{
	sign = '';
	if (number < 0) {
		sign = '-';
		numberDigits--;
	}
	number = String(Math.abs(number));
	while (number.length < numberDigits) {
		number = '0' + number;
	}
	return sign + number;
}
function incTimer()
{
	elemTimer = document.getElementById('idTimer');
	if (elemTimer.innerHTML < 999) {
		elemTimer.innerHTML = formatNumber(+elemTimer.innerHTML + 1, 3);
	}
}
function setLevel(level)
{
	setButtonView(level);
	switch (level) {
		case 1:
		setSettings(10, 10, 10, true);
		break;
		case 2:
		setSettings(16, 16, 40, true);
		break;	
		case 3:
		setSettings(16, 30, 100, true);
		break;
		default:
		setSettings(10, 10, 10, false);
	}
}
function setSettings(h, w, b, ro)
{
	document.forms["formSettings"].height.value = h;
	document.forms["formSettings"].width.value = w;
	document.forms["formSettings"].numberBombs.value = b;
	if (ro == true) {
		document.forms["formSettings"].height.readOnly = true;
		document.forms["formSettings"].width.readOnly = true;
		document.forms["formSettings"].numberBombs.readOnly = true;
		document.forms["formSettings"].height.style = "opacity: 0.6";
		document.forms["formSettings"].width.style = "opacity: 0.6";
		document.forms["formSettings"].numberBombs.style = "opacity: 0.6";
	} else {
		document.forms["formSettings"].height.readOnly = false;
		document.forms["formSettings"].width.readOnly = false;
		document.forms["formSettings"].numberBombs.readOnly = false;
		document.forms["formSettings"].height.style = "opacity: 1";
		document.forms["formSettings"].width.style = "opacity: 1";
		document.forms["formSettings"].numberBombs.style = "opacity: 1";
	}
}
function setButtonView(level)
{
	numberLevel0.style = 'opacity: 0.8';
	numberLevel1.style = 'opacity: 0.8';
	numberLevel2.style = 'opacity: 0.8';
	numberLevel3.style = 'opacity: 0.8';
	buttonLevel = document.getElementById('numberLevel' + level);
	buttonLevel.style = 'opacity: 1';
}