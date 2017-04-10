/* 
!done M01 (characteristicsM01) Физические характеристики
M02 Параметры работы
!done (roomM03) M03 Помещения
!done (actuatorsMO4) М04 Актуаторы
М05 Внешние датчики
М06 Внутренние датчики
М07 Зависимости от внешней среды
М08 Зависимости от актуаторов
M09 Суточное расписание
M10 Суточный прогноз внешней среды

!done (stepControlA00) A00 Текущий такт управления 
А01 Оптимальный план управления
A02 Прогноз внешней среды
А03 Прогноз внутренней среды
A04 Расписание управления
А05 Кодирование команд управления
А06 Коррекция команд управления
А08 Коррекция прогноза внутренней среды
А09 План управления

R01 Мониторинг прогноза внешней среды
R02 Мониторинг прогноза внутренней среды
R03 Мониторинг актуаторов
R04 Мониторинг энергопотребления

С00 Цикл управления
С01 Управление актуаторами
С02 Данные внешних датчиков
С03 Данные внутренних датчиков
C04 Подключение и обмен данными

S00 Мощности актуаторов
S01 Кодирование мощностей актуаторов
S02 Управление актуаторами имитатора
S03 Актуаторы имитатора
S04 Зависимости от актуаторов имитат
*/

/*
Что не закончено отмечено ******************
ИК - инфракрасное излучение
*/
// function getRandom(min, max){
// 	return Math.floor(Math.random() * (max - min + 1)) + min;
// }

function characteristicsM01() { // формат 1	люксы	освещенность
	var characteristics = ["температура", "влажность", "инфракрасное излучение", "уровень громкости звука", "освещенность"];
	var description = ["градусы цельсия", "проценты", "частота", "децибел", "люксы",];

	var char = characteristics[getRandom(0,characteristics.length-1)]
	return characteristics.indexOf(char) + '; ' + description[characteristics.indexOf(char)] + '; ' + char;
}

function MO2() {
	//????
}

function roomM03() { // формат: 2 Спальня	г.Петушки, ул.Первая, д.1
	var rooms = ["прихожая", "гостиная", "ванная", "туалет", "кухня", "столовая", "спальня", 
				"гостевая", "детская", "игровая", "бильярдная", "библиотека", "читальная", 
				"кабинет", "офис", "мастерская", "студия", "прачечная","тренажерный зал", 
				"гараж", "котельная", "гардеробная", "оранжерея", "домашний кинотеатр", 
				"кладовые"];
	var street = 'ул.Первая, д.1', town = 'г.Петушки';

	rmdRooms = rooms[getRandom(0,rooms.length-1)];
	if (rmdRooms != undefined) {
		return rmdRooms + ' ' + street + ' ' + town;
	}
}
function actuatorsMO4() { //формат 1;Лампочка1;3;0;2.25;0
	// ******************
	var name = ["датчик температуры", "датчик влажности", "датчик ИК",
				"датчик уровня громкости звука", "датчик освещенности"];
	var ports = [5,6,3,2,4];
	var ret = name[getRandom(0,name.length-1)];
	return name.indexOf(ret) + '; ' + ret + '; ' + 'порт ' + ports[name.indexOf(ret)];

}

function M05() { // формат: 1;1;0;0;-0.24926686217;255
	
}

function stepControlA00() {
	//формат:  YYYY, MM, DD, HH:MM:SS 2014, 08, 14, 15:00:00 
	var month = ["Января","Февраля","Марта","Апреля","Мая","Июня",
				"Июля","Августа","Сентября","Октября","Ноября","Декабря"];
	//var day = ["Понедельник","Вторник","Среда","Четверг","Пятница","Суббота","Воскресенье"];
	var time, h, m, s, y, mt, week, d;
		time = new Date();
		h = time.getHours();
		m = time.getMinutes();
		s = time.getSeconds();
		y = time.getFullYear();
		mt = time.getMonth();
		week = time.getDay();
		d = time.getDate();
		
		return y + ' ' + d + ' ' + month[mt] + ' ' + h + ':' + m + ':' + s;
}
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
////////////////                           ////////////////
////////////////     /      ////// /   /   ////////////////
////////////////    / /     /    /  / /    ////////////////
////////////////   /   /    /    /   /     ////////////////
////////////////  /     /   /    /  / /    ////////////////
//////////////// /       /  ////// /   /   ////////////////
////////////////                           ////////////////
////////////////                           ////////////////
////////////////                           ////////////////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////


// отросить целую часть
function getDecimal(num) {
	var str = "" + num;
	var zeroPos = str.indexOf(".");
	if (zeroPos == -1) return 0;
		str = str.slice(zeroPos);
	return + str;
}


// function numberDigits(num) {
// 	var numb = String(num);
// 	return Number(numb.slice(-4));
// }

function getRandom(num) {
	var numb = String(num);
	return Number(numb.slice(-6));
}

// метод конгруэнций
function linearСongruence() {
	// var core = 7872;
	// var multiplier = 3729;

	var rand = new Array();
	var p = 9007199254740992;
	var a = 22695477;
	var d = new Date();
	var per2;

	rand[0] = d.getTime();

	for (var i = 1; i < 100000; i++) 
	{
		rand[i] = (rand[i - 1] * a) % (p);
		a = Number(((rand[i] / 10000) .toFixed(4)).substr(-4));
		// console.log(rand[i]);
		// console.log(a);
		// rand[i] = Number(((rand[i] / 10000) .toFixed(4)).substr(-4));
		
	}

	// for (var i = 0; i < 5000; i++) {
	// 	coreMiddle = 
	// 	//console.log('рандом = ' + coreMiddle);
	// 	multiplier = getDecimal((multiplier * core) / 10000) * 10000;
	// 	//console.log('множ = ' + multiplier);
	// 	rand[i] = coreMiddle;
	// }
	return rand;
}

// показ текущего значения датчика или лампы
var mas = linearСongruence();
// console.log(mas);

var countMas = 1;
var parametr = 500;

function showMustGo(){

	// var imgBool = false;
	// var imgBool2 = false;
	// setInterval(function () {

	// 	var img = document.getElementById("one");
	// 	var img2 = document.getElementById("two");

	// 	var srcLamp2 = img2.src
	// 	var arr2 = srcLamp2.split('');

	// 	var srcLamp = img.src;
	// 	var arr = srcLamp.split('');

	// 	if (arr[arr.length-1] == 'with-light.png') {
	// 		imgBool = true;
	// 	}
	// 	if (arr2[arr.length-1] == 'with-light.png') {
	// 		imgBool2 = true;
	// 	}
	// }, 1000);

	//console.log(imgBool);

	var img = document.getElementById("one");
	var srcLamp = img.src;
	var arr = srcLamp.split('/');

	var img1 = document.getElementById("two");
	var srcLamp1 = img1.src;
	var arr1 = srcLamp1.split('/');

	if(countMas == linearСongruence().length)
		countMas = 0;
	var timer1 = document.getElementById('container');

	if(arr[arr.length-1] == "with-light.png")
		timer1.innerHTML = timer1.innerHTML + '<strong>Датчик 1:</strong> ' + mas[countMas] + '<br>';
	else 
		timer1.innerHTML = timer1.innerHTML + '<strong>Датчик 1:</strong> 0' + '<br>';

	if(arr1[arr.length-1] == "with-light.png")
		timer1.innerHTML = timer1.innerHTML + '<strong>Датчик 2:</strong> ' + mas[countMas] + '<br>';
	else 
		timer1.innerHTML = timer1.innerHTML + '<strong>Датчик 2:</strong> 0' + '<br>';


	var wrap = $('#container');
	wrap.scrollTop(1000000000000000000000);
	countMas = countMas + 1;
}

var bool = false;
function playSystem() {
	if (!bool) {
		timer = setInterval('showMustGo()', parametr);
		bool = true;
	}
}

function stopSystem() {
	clearInterval(timer);
	bool = false;
}


// console.log(typeof());
console.log();



//console.log(linearСongruence());