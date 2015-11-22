function swap_class(elementClass){
	if (elementClass === "fa fa-plus-square") {
		return "fa fa-minus-square";
	}else if (elementClass === "fa fa-minus-square") {
		return "fa fa-plus-square";
	}else if (elementClass === "fa fa-chevron-down") {
		return "fa fa-chevron-up";
	}else if (elementClass === "fa fa-chevron-up") {
		return "fa fa-chevron-down";
	}
}

function swap_display(elementDisplay){
	if (elementDisplay === "" || elementDisplay === "none") {
		return "block";
	}else if (elementDisplay === "block") {
		return "none";
	}
}

var elements = document.getElementsByClassName("fa fa-plus-square");
for (var i = elements.length - 1; i >= 0; i--) {
	elements[i].addEventListener("click",
				function(){
					this.className = swap_class(this.className);
					this.querySelector('.doi-links').style.display = swap_display(this.querySelector('.doi-links').style.display);
				});
};

function drop(element, value){
	element.querySelector('i').className = swap_class(element.querySelector('i').className);
	document.querySelector(value).style.display = swap_display(document.querySelector(value).style.display);
}
