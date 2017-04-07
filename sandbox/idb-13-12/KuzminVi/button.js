function create()
{
	var x = document.getElementById("label").value;
if (x != "")
{
	var label = document.createElement("p");
	var text = document.createTextNode(x);
	label.appendChild(text);
	document.getElementById("newform").appendChild(label);
	
	var f = document.createElement("input");
	f.type = "text";
	document.getElementById("newform").appendChild(f);
}
	
	
}
	
function finish()
{
	var x = document.createElement("input");
	x.type="submit";
	x.value="Отправить";
	document.getElementById("newform").appendChild(x);
	var newform = document.getElementById("newformcode");
	var code = newform.innerHTML;
	document.getElementById("textcode").innerHTML = code;
	
document.getElementById("newform").innerHTML = ""
}
