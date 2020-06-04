const DEFAULT = 0;
const FLAG = 1;
const QUESTION = 2;
const CLASS_NOT_VISIBLE = "cell notVisible";
const CLASS_BOMB = "cell bomb";
const CLASS_EXPLODED_BOMB = "cell explodedBomb";

function sendAsyncCommand(request, funcProcessing)
{
	fetch('/', {
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
		dataStateProcessing(data);
		for (var i = 0; i < data.length; i++) {
			if (elemCell = document.getElementById(data[i]['id'])){
				elemCell.innerHTML = data[i].value;
				elemCell.className = data[i].class;
				showImageCell([elemCell]);
			}
		}
	}
}

function dataStateProcessing(data)
{
	if (Array.isArray(data)) {
		if (startTimeGlob != data[0]['startTime']) {
			startTimeGlob = data[0]['startTime'];
			setTimer();
		}
		if (endTimeGlob != data[0]['endTime']) {
			endTimeGlob = data[0]['endTime'];
			setTimer();
		}		
		if (data[0]['startGame'] === true && data[0]['endGame'] === false) {
			if (typeof intervalIdGlob == 'undefined') {
				intervalIdGlob = setInterval(setTimer, 1000);
			}
		} else {
			clearInterval(intervalIdGlob);
			intervalIdGlob = undefined;
		}
		if (countRemainingBombGlob != data[0]['countRemainingBomb']) {
			countRemainingBombGlob = data[0]['countRemainingBomb'];
			setCountBomb();
		}
		if (data[0]['startGame'] === false) {
			clearImageField();
			setCountBomb();
		}
		if (data[0]['endGame'] === true) {
			sendAsyncCommand('record=get', tableRecordProcessing);
		}
	}
}

function tableRecordProcessing(data)
{
	const MAX_ROWS = 11;
	if (typeof data == 'object') {
		if (typeof data['name'] != 'undefined') {
			elemStat = document.getElementById('statistic');
			cells = elemStat.querySelectorAll('.row');
			dataTable = [];
			colNumber = Object.keys(data).length + 1;					// кол-во стобцов (+1 на нумерацию)
			rowNumber = cells.length / colNumber;						// кол-во строк
			for (let i = 0; i < cells.length; i++) {			// преобразование данных таблицы в массив
				c = Math.floor(i / rowNumber);
				r = i % rowNumber;
				if (Array.isArray(dataTable[c]) == false) {
					dataTable[c] = [];
				}
				dataTable[c][r] = cells[i].innerHTML;
			}

			flagUpdateTable = false;		// флаг вставки строки
			flagInsertInTable = false;		// флаг обновления строки
			if (rowNumber < MAX_ROWS) {
				numNewRecord = rowNumber - 1;
				flagInsertInTable = true;
			}
			if (rowNumber == 1) {	// в таблице только шапка
				numNewRecord = 0;
			} else {
				c1 = Object.keys(data).indexOf('counterHelp');
				c2 = Object.keys(data).indexOf('time')
				for (let i = 0; i < rowNumber; i++) {				// нахождение места для новой строки
					if (
						dataTable[c1+1][i+1] > data[Object.keys(data)[c1]] ||
						(
							dataTable[c1+1][i+1] == data[Object.keys(data)[c1]] &&
							dataTable[c2+1][i+1] > data[Object.keys(data)[c2]]
						)
					) {
						numNewRecord = i;
						flagUpdateTable = true;
						break;
					}
				}
			}

			if (flagInsertInTable) {				// вставка новой строки
				for (let i = 0; i < colNumber; i++) {
					cell = cells[i * rowNumber];
					newCell = cell.cloneNode(true);
					if (i > 0) {
						newCell.innerHTML = data[Object.keys(data)[i-1]];
					}
					newCell.style = 'background-color: rgba(39, 255, 0, 0.07);';
					insertAfter(newCell, cells[i * rowNumber + numNewRecord]);
				}
				cellsN = cells[0].parentElement.children;	// клетки столбца номеров
				for (let i = 1; i < cellsN.length; i++) {
					cellsN[i].innerHTML = i;
				}
			} else if (flagUpdateTable) {		// обновление данных в таблице
				numNewRecord++;		// пропуск шапки таблицы
				for (let i = 1; i < dataTable.length; i++) {	// вставка новой строки в массив
					dataTable[i].splice(numNewRecord, 0, data[Object.keys(data)[i-1]]);
					dataTable[i].pop();
				}
				for (let i = 0; i < dataTable.length; i++) {	// вставка новых значений в таблицу
					for (let j = numNewRecord; j < dataTable[0].length; j++) {
						cells[i * rowNumber + j].innerHTML = dataTable[i][j];
					}
					cells[i * rowNumber + numNewRecord].style = 'background-color: rgba(39, 255, 0, 0.07);';
				}
			}
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
				setCounterMarkCell(getCounterMarkCell() - 1);
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
	setCountBomb();
}

function leftClickCell(item)
{
	if (item.className == CLASS_NOT_VISIBLE) {
		cellView = localStorage.getItem(item.id);
		if (cellView == null || cellView == DEFAULT) {
			sendAsyncCommand("coord=test" + item.id, dataFieldProcessing);
		} else if (cellView == QUESTION) {
			sendAsyncCommand("coord=help" + item.id, dataFieldProcessing);
		}
	}		
}

function rightClickCell(item)
{
	if (item.className == CLASS_NOT_VISIBLE) {
		cellView = localStorage.getItem(item.id);
		if (cellView == null || cellView == DEFAULT) {
			localStorage.setItem(item.id, FLAG);
			setCounterMarkCell(getCounterMarkCell() + 1);
			setCountBomb();
		} else if (cellView == FLAG) {
			localStorage.setItem(item.id, QUESTION);
		} else if (cellView == QUESTION) {
			localStorage.setItem(item.id, DEFAULT);
			setCounterMarkCell(getCounterMarkCell() - 1);
			setCountBomb();
		}
		showImageCell([item]);
	}
}

function newGameClick()
{
	sendAsyncCommand("newGame=get", dataFieldProcessing);
}

function clearImageField()
{
	setCounterMarkCell(0);
	localStorage.clear();
}

function formatNumber(number, numberDigits)
{
	number = Math.round(number);
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

function setTimer()
{
	elemTimer = document.getElementById('idTimer');
	if (endTimeGlob < startTimeGlob) {
		time = (new Date().getTime()/1000) - startTimeGlob;
	} else {
		time = endTimeGlob - startTimeGlob;
	}
	if (time < 0) {
		elemTimer.innerHTML = formatNumber(0, 3);
	} else if(time > 999) {
		elemTimer.innerHTML = formatNumber(999, 3);
	} else {
		elemTimer.innerHTML = formatNumber(time, 3);
	}
}

function setCountBomb()
{
	elemCounterBomb = document.getElementById('idCounterBomb');
	elemCounterBomb.innerHTML = formatNumber(+countRemainingBombGlob - getCounterMarkCell(), 3);
}

function getCounterMarkCell()
{
	if (typeof localStorage.getItem('counterMarkCell') == 'undefined') {
		localStorage.getItem('counterMarkCell') = 0;
	}
	return Number(localStorage.getItem('counterMarkCell'));
}

function setCounterMarkCell(value)
{
	localStorage.setItem('counterMarkCell', Number(value));
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

function insertAfter(newNode, node)
{
	parent = node.parentElement;
	allChildren = parent.children;
	for (i = 0; allChildren[i] != node && i < allChildren.length; i++);
	if (i == allChildren.length - 1) {
		parent.appendChild(newNode);
	} else {
		parent.insertBefore(newNode, allChildren[i+1]);
	}
}